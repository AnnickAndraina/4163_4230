<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperationModel;

class ConnexionController extends BaseController
{
    public function index()
    {
        return view('login', [
            'values' => ['telephone' => ''],
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

        session()->set('client_id', $client['id']);

        return redirect()->to('home');
    }

    public function loginAdmin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username === 'admin' && $password === 'admin123') {
            session()->set('admin_connecte', true);
            return redirect()->to('admin/dashboard');
        }

        session()->setFlashdata('admin_error_flag', true);
        return redirect()->back()->with('error', 'Identifiants Admin incorrects.');
    }

    public function home()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find(session()->get('client_id'));
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
        $montant = (float) $this->request->getPost('montant');
        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find(session()->get('client_id'));
        $frais = $this->getFrais(1, $montant);
        $montantTotal = $montant + $frais;

        $soldeAvant = $client['solde'];
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

        return redirect()->to('home');
    }

    public function retrait()
    {
        $montant = (float) $this->request->getPost('montant');
        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find(session()->get('client_id'));
        $frais = $this->getFrais(2, $montant);
        $montantTotal = $montant + $frais;

        if ($client['solde'] >= $montantTotal) {
            $soldeAvant = $client['solde'];
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
        }

        return redirect()->to('home');
    }

    public function transfert()
    {
        $montant = (float) $this->request->getPost('montant');
        $destinataireTelRaw = $this->request->getPost('destinataire');
        
        $destinataireTel = str_replace(' ', '', $destinataireTelRaw);

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $expediteur = $clientModel->find(session()->get('client_id'));
        $destinataire = $clientModel->where('numero_telephone', $destinataireTel)->first();
        $frais = $this->getFrais(3, $montant);
        $montantTotal = $montant + $frais;

        if ($destinataire && $expediteur['solde'] >= $montantTotal) {
            
            $soldeAvantExp = $expediteur['solde'];
            $soldeApresExp = $soldeAvantExp - $montantTotal;
            $clientModel->update($expediteur['id'], ['solde' => $soldeApresExp]);

            $soldeAvantDest = $destinataire['solde'];
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
        }

        return redirect()->to('home');
    }
}