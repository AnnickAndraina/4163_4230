<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperationModel;
use App\Models\OperateurModel;
use App\Models\ConfigurationModel;

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

        $operateurModel = new OperateurModel();
        $operateur = $operateurModel->where('prefixe', $prefixe)->where('actif', 1)->first();

        if (!$operateur) {
            $errors['telephone'] = 'Opérateur non supporté (préfixe invalide).';
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $model = new ClientModel();
        $client = $model->autoLogin($telephone);

        if ($operateur['type'] !== 'LOCAL') {
            $errors['telephone'] = 'Cet opérateur ne permet pas la connexion à l\'application.';
            $values['telephone'] = $telephoneRaw;
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $session = session();
        $session->set('client_id', $client['id']);
        $session->set('client_telephone', $client['numero_telephone']);
        $session->set('client_nom', $client['nom']);
        $session->set('client_solde', $client['solde']);
        $session->set('client_operateur_id', $operateur['id']);

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

        try {
            $dernierTransfert = $operationModel
                ->where('client_destinataire_id', $client['id'])
                ->where('type_operation_id', 3)
                ->orderBy('date_operation', 'DESC')
                ->first();

            if ($dernierTransfert && strtotime($dernierTransfert['date_operation']) > time() - 60) {
                $frais = (float)$dernierTransfert['frais_applique'];
                $montantRecu = (float)$dernierTransfert['montant'];

                $expediteur = $clientModel->find($dernierTransfert['client_id']);
                $nomExpediteur = $expediteur ? $expediteur['nom'] : 'Inconnu';

                $message = $frais > 0
                    ? "Vous avez reçu " . number_format($montantRecu, 0, ',', ' ') . " Ar de " . $nomExpediteur . " (frais déduits)."
                    : "Vous avez reçu " . number_format($montantRecu, 0, ',', ' ') . " Ar de " . $nomExpediteur . ".";
                $session->setFlashdata('popup_frais', $message);
            }
        } catch (\Exception $e) {}

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

    private function getOperateurByTelephone($telephone)
    {
        $prefixe = substr(str_replace(' ', '', $telephone), 0, 3);
        $model = new OperateurModel();
        return $model->where('prefixe', $prefixe)->where('actif', 1)->first();
    }

    public function depot()
    {
        $session = session();
        if (!$session->has('client_id')) return redirect()->to('/');

        $montant = (float) $this->request->getPost('montant');
        if ($montant <= 0) {
            $session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();
        $client = $clientModel->find($session->get('client_id'));

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

        $session->set('client_solde', $soldeApres);
        $session->setFlashdata('popup_frais', "Dépôt réussi. Frais : " . number_format($frais, 0, ',', ' ') . " Ar.");
        return redirect()->to('home');
    }

    public function retrait()
    {
        $session = session();
        if (!$session->has('client_id')) return redirect()->to('/');

        $montant = (float) $this->request->getPost('montant');
        if ($montant <= 0) {
            $session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();
        $client = $clientModel->find($session->get('client_id'));

        $frais = $this->getFrais(2, $montant);
        $montantTotal = $montant + $frais;

        if ((float)$client['solde'] < $montantTotal) {
            $session->setFlashdata('popup_frais', "Solde insuffisant.");
            return redirect()->to('home');
        }

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

        $session->set('client_solde', $soldeApres);
        $session->setFlashdata('popup_frais', "Retrait réussi. Frais : " . number_format($frais, 0, ',', ' ') . " Ar.");
        return redirect()->to('home');
    }

    public function transfert()
    {
        $session = session();
        if (!$session->has('client_id')) return redirect()->to('/');

        $montantTotalInput = (float) $this->request->getPost('montant');
        $inclureFrais = $this->request->getPost('inclure_frais') === 'on';
        $destinatairesRaw = str_replace(' ', '', $this->request->getPost('destinataire'));

        if ($montantTotalInput <= 0) {
            $session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('home');
        }

        $destinatairesList = array_filter(array_unique(explode(',', $destinatairesRaw)));
        if (empty($destinatairesList)) {
            $session->setFlashdata('popup_frais', "Numéro de destinataire invalide.");
            return redirect()->to('home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();
        $configModel = new ConfigurationModel();

        $expediteur = $clientModel->find($session->get('client_id'));
        if (!$expediteur) return redirect()->to('/');

        $expOperateur = $this->getOperateurByTelephone($expediteur['numero_telephone']);
        if (!$expOperateur) {
            $session->setFlashdata('popup_frais', "Opérateur de l'expéditeur non reconnu.");
            return redirect()->to('home');
        }

        $nombreDest = count($destinatairesList);
        $montantParDest = floor($montantTotalInput / $nombreDest);
        if ($montantParDest <= 0) {
            $session->setFlashdata('popup_frais', "Montant par destinataire trop faible suite à la division.");
            return redirect()->to('home');
        }

        $destinatairesData = [];

        foreach ($destinatairesList as $tel) {
            if (strlen($tel) !== 10 || !ctype_digit($tel)) {
                $session->setFlashdata('popup_frais', "Format de numéro invalide : " . $tel);
                return redirect()->to('home');
            }
            if ($tel === $expediteur['numero_telephone']) {
                $session->setFlashdata('popup_frais', "Vous ne pouvez pas vous transférer de l'argent à vous-même.");
                return redirect()->to('home');
            }

            $destOperateur = $this->getOperateurByTelephone($tel);
            if (!$destOperateur) {
                $session->setFlashdata('popup_frais', "Opérateur du destinataire non reconnu : " . $tel);
                return redirect()->to('home');
            }

            $dest = $clientModel->where('numero_telephone', $tel)->first();
            if (!$dest) {
                $session->setFlashdata('popup_frais', "Destinataire introuvable dans la base : " . $tel);
                return redirect()->to('home');
            }

            $destinatairesData[] = [
                'dest'          => $dest,
                'tel'           => $tel,
                'operateur'     => $destOperateur,
                'estLocal'      => ($destOperateur['type'] === 'LOCAL'),
                'memeOperateur' => ($destOperateur['libelle'] == $expOperateur['libelle'])
            ];
        }

        if ($nombreDest > 1) {
            foreach ($destinatairesData as $dData) {
                if (!$dData['memeOperateur']) {
                    $session->setFlashdata('popup_frais', "L'envoi multiple n'est autorisé que vers des numéros du même opérateur que l'expéditeur.");
                    return redirect()->to('home');
                }
            }
        }

        $commissionRate = (float)$configModel->getCommission() / 100.0;
        $montantTotalADebiter = 0;

        foreach ($destinatairesData as &$dData) {
            $dest = $dData['dest'];
            $memeOperateur = $dData['memeOperateur'];

            $fraisTransfert = $this->getFrais(3, $montantParDest);

            if ($memeOperateur) {
                $commissionInter = 0;
                $fraisRetrait = $inclureFrais ? $this->getFrais(2, $montantParDest) : 0;
                $fraisTotalParDest = $fraisTransfert + $fraisRetrait;
                $montantRecuParDest = $montantParDest;
            } else {
                $fraisRetrait = 0;
                $commissionInter = round($montantParDest * $commissionRate, 0);
                $fraisTotalParDest = $fraisTransfert + $commissionInter;
                $montantRecuParDest = $montantParDest;
            }

            $montantTotalADebiter += $montantParDest + $fraisTotalParDest;

            $dData['fraisTransfert'] = $fraisTransfert;
            $dData['fraisRetrait'] = $fraisRetrait;
            $dData['commissionInter'] = $commissionInter;
            $dData['fraisTotalParDest'] = $fraisTotalParDest;
            $dData['montantRecuParDest'] = $montantRecuParDest;
        }
        unset($dData);

        if ((float)$expediteur['solde'] < $montantTotalADebiter) {
            $session->setFlashdata('popup_frais', "Solde insuffisant pour effectuer ce transfert.");
            return redirect()->to('home');
        }

        $soldeAvantExp = (float)$expediteur['solde'];
        $soldeApresExp = $soldeAvantExp - $montantTotalADebiter;
        $clientModel->update($expediteur['id'], ['solde' => $soldeApresExp]);

        foreach ($destinatairesData as $dData) {
            $dest = $dData['dest'];
            $soldeAvantDest = (float)$dest['solde'];
            $soldeApresDest = $soldeAvantDest + $dData['montantRecuParDest'];
            $clientModel->update($dest['id'], ['solde' => $soldeApresDest]);

            $operationModel->insert([
                'type_operation_id'      => 3,
                'client_id'              => $expediteur['id'],
                'client_destinataire_id' => $dest['id'],
                'operateur_destination_id' => $dData['operateur']['id'],
                'commission'             => $dData['commissionInter'],
                'montant'                => $montantParDest,
                'frais_applique'         => $dData['fraisTotalParDest'],
                'montant_total'          => $montantParDest + $dData['fraisTotalParDest'],
                'solde_avant'            => $soldeAvantExp,
                'solde_apres'            => $soldeApresExp,
                'statut'                 => 'reussie',
                'date_operation'         => gmdate('Y-m-d H:i:s', time() + 10800)
            ]);
        }

        $session->set('client_solde', $soldeApresExp);

        $msg = "Transfert réussi vers " . $nombreDest . " destinataire(s).";
        if ($inclureFrais && $destinatairesData[0]['memeOperateur']) {
            $msg .= " Frais de retrait inclus.";
        }
        $session->setFlashdata('popup_frais', $msg);
        return redirect()->to('home');
    }

    public function logout()
    {
        $session = session();
        $session->remove('client_id');
        $session->remove('client_telephone');
        $session->remove('client_nom');
        $session->remove('client_solde');
        $session->remove('client_operateur_id');
        return redirect()->to('/');
    }
}