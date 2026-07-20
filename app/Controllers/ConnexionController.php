<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperationModel;

class ConnexionController extends BaseController
{
    public function index()
    {
        return view('login', [
            'values' => ['telephone' => '0331234567'],
            'errors' => ['telephone' => '']
        ]);
    }

    public function login()
    {
        $telephoneRaw = $this->request->getPost('telephone');
        $telephone = str_replace(' ', '', $telephoneRaw);

        $values = ['telephone' => $telephoneRaw];
        $errors = ['telephone' => ''];

        if (strlen($telephone) !== 10 || !ctype_digit($telephone)) {
            $errors['telephone'] = 'Le numéro doit comporter exactement 10 chiffres.';
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $prefixe = substr($telephone, 0, 3);

        $db = \Config\Database::connect();
        $prefixeValide = $db->query("SELECT * FROM prefixe_operateur WHERE prefixe = ? AND actif = 1", [$prefixe])->getRow();

        if (!$prefixeValide) {
            $errors['telephone'] = 'Opérateur non supporté (Préfixe invalide).';
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $model = new ClientModel();
        $client = $model->autoLogin($telephone);

        $session = session();
        $session->set('client_id', $client['id']);
        $session->set('client_telephone', $client['numero_telephone']);
        $session->set('client_nom', $client['nom']);
        $session->set('client_solde', $client['solde']);

        return redirect()->to('home');
    }

    public function loginAdmin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username === 'admin' && $password === 'admin123') {
            $session = session();
            $session->set('admin_connecte', true);
            return redirect()->to('admin/dashboard');
        }

        $session = session();
        $session->setFlashdata('admin_error_flag', true);
        return redirect()->back()->with('error', 'Identifiants Admin incorrects.');
    }

       public function home()
    {
        $session = session();
        if (!$session->has('client_id')) {
            return redirect()->to('/');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find($session->get('client_id'));
        
        if (!$client) {
            $session->remove('client_id');
            return redirect()->to('/');
        }

        $historique = $operationModel->getHistoriqueClient($client['id']);

        return view('home_client', [
            'client' => $client,
            'historique' => $historique
        ]);
    }
    
    private function getFrais($typeOperationId, $montant)
    {
        $db = \Config\Database::connect();
        $bareme = $db->table('bareme_frais')
            ->where('type_operation_id', $typeOperationId)
            ->where('actif', 1)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->get()
            ->getRow();

        return $bareme ? (float)$bareme->frais : 0.0;
    }

    public function depot()
    {
        $session = session();
        if (!$session->has('client_id')) {
            return redirect()->to('/');
        }

        $montant = (float) $this->request->getPost('montant');
        
        if ($montant <= 0) {
            $session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find($session->get('client_id'));
        
        if (!$client) {
            $session->remove('client_id');
            return redirect()->to('/');
        }

        $frais = $this->getFrais(1, $montant);
        $montantTotal = $montant + $frais;

        $soldeAvant = (float)$client['solde'];
        $soldeApres = $soldeAvant + $montantTotal;

        $clientModel->update($client['id'], ['solde' => $soldeApres]);

        $operationModel->insert([
            'type_operation_id' => 1,
            'client_id'         => $client['id'],
            'montant'           => $montant,
            'frais_applique'    => $frais,
            'montant_total'     => $montantTotal,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeApres,
            'statut'            => 'reussie',
            'date_operation'    => gmdate('Y-m-d H:i:s', time() + 10800)
        ]);

        $session->set('client_id', $client['id']);
        $session->set('client_solde', $soldeApres);
        $session->setFlashdata('popup_frais', "Votre frais pour le dépôt est de " . number_format($frais, 0, ',', ' ') . " Ar.");
        
        return redirect()->to('home');
    }

    public function retrait()
    {
        $session = session();
        if (!$session->has('client_id')) {
            return redirect()->to('/');
        }

        $montant = (float) $this->request->getPost('montant');
        
        if ($montant <= 0) {
            $session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find($session->get('client_id'));
        
        if (!$client) {
            $session->remove('client_id');
            return redirect()->to('/');
        }

        $frais = $this->getFrais(2, $montant);
        $montantTotal = $montant + $frais;

        if ((float)$client['solde'] >= $montantTotal) {
            $soldeAvant = (float)$client['solde'];
            $soldeApres = $soldeAvant - $montantTotal;

            $clientModel->update($client['id'], ['solde' => $soldeApres]);

            $operationModel->insert([
                'type_operation_id' => 2,
                'client_id'         => $client['id'],
                'montant'           => $montant,
                'frais_applique'    => $frais,
                'montant_total'     => $montantTotal,
                'solde_avant'       => $soldeAvant,
                'solde_apres'       => $soldeApres,
                'statut'            => 'reussie',
                'date_operation'    => gmdate('Y-m-d H:i:s', time() + 10800)
            ]);

            $session->set('client_id', $client['id']);
            $session->set('client_solde', $soldeApres);
            $session->setFlashdata('popup_frais', "Votre frais pour le retrait est de " . number_format($frais, 0, ',', ' ') . " Ar.");
        } else {
            $session->setFlashdata('popup_frais', "Solde insuffisant pour effectuer ce retrait.");
        }

        return redirect()->to('home');
    }

    public function transfert()
    {
        $session = session();
        if (!$session->has('client_id')) {
            return redirect()->to('/');
        }

        $montant = (float) $this->request->getPost('montant');
        
        if ($montant <= 0) {
            $session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('home');
        }

        $destinataireTelRaw = $this->request->getPost('destinataire');
        $destinataireTel = str_replace(' ', '', $destinataireTelRaw);

        if (empty($destinataireTel) || strlen($destinataireTel) !== 10) {
            $session->setFlashdata('popup_frais', "Numéro de destinataire invalide.");
            return redirect()->to('home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $expediteur = $clientModel->find($session->get('client_id'));
        
        if (!$expediteur) {
            $session->remove('client_id');
            return redirect()->to('/');
        }

        if ($destinataireTel === $expediteur['numero_telephone']) {
            $session->setFlashdata('popup_frais', "Vous ne pouvez pas vous transférer à vous-même.");
            return redirect()->to('home');
        }

        $destinataire = $clientModel->where('numero_telephone', $destinataireTel)->first();
        $frais = $this->getFrais(3, $montant);
        $montantTotal = $montant + $frais;

        if ($destinataire && (float)$expediteur['solde'] >= $montantTotal) {
            
            $soldeAvantExp = (float)$expediteur['solde'];
            $soldeApresExp = $soldeAvantExp - $montantTotal;
            $clientModel->update($expediteur['id'], ['solde' => $soldeApresExp]);

            $soldeAvantDest = (float)$destinataire['solde'];
            $soldeApresDest = $soldeAvantDest + $montant;
            $clientModel->update($destinataire['id'], ['solde' => $soldeApresDest]);

            $operationModel->insert([
                'type_operation_id'        => 3,
                'client_id'                => $expediteur['id'],
                'client_destinataire_id'   => $destinataire['id'],
                'montant'                  => $montant,
                'frais_applique'           => $frais,
                'montant_total'            => $montantTotal,
                'solde_avant'              => $soldeAvantExp,
                'solde_apres'              => $soldeApresExp,
                'statut'                   => 'reussie',
                'date_operation'           => gmdate('Y-m-d H:i:s', time() + 10800)
            ]);

            $session->set('client_id', $expediteur['id']);
            $session->set('client_solde', $soldeApresExp);
            $session->setFlashdata('popup_frais', "Votre frais pour le transfert est de " . number_format($frais, 0, ',', ' ') . " Ar.");
        } else {
            $session->setFlashdata('popup_frais', "Transfert impossible : solde insuffisant ou destinataire introuvable.");
        }

        return redirect()->to('home');
    }

    public function logout()
    {
        $session = session();
        $session->remove('client_id');
        $session->remove('client_telephone');
        $session->remove('client_nom');
        $session->remove('client_solde');
        return redirect()->to('/');
    }
}