<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperationModel;
use App\Models\ConfigurationModel;

class TransfertController extends BaseClientController
{
    public function transfert()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $montantTotalInput = (float) $this->request->getPost('montant');
        $inclureFrais = $this->request->getPost('inclure_frais') === 'on';
        $destinatairesRaw = str_replace(' ', '', $this->request->getPost('destinataire'));

        if ($montantTotalInput <= 0) {
            $this->session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('client/home');
        }

        $destinatairesList = array_filter(array_unique(explode(',', $destinatairesRaw)));
        if (empty($destinatairesList)) {
            $this->session->setFlashdata('popup_frais', "Numéro de destinataire invalide.");
            return redirect()->to('client/home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();
        $configModel = new ConfigurationModel();

        $expediteur = $clientModel->find($this->session->get('client_id'));
        if (!$expediteur) return redirect()->to('/');

        $expOperateur = $this->getOperateurByTelephone($expediteur['numero_telephone']);
        if (!$expOperateur) {
            $this->session->setFlashdata('popup_frais', "Opérateur de l'expéditeur non reconnu.");
            return redirect()->to('client/home');
        }

        $nombreDest = count($destinatairesList);
        $montantParDest = floor($montantTotalInput / $nombreDest);
        if ($montantParDest <= 0) {
            $this->session->setFlashdata('popup_frais', "Montant par destinataire trop faible suite à la division.");
            return redirect()->to('client/home');
        }

        $destinatairesData = [];

        foreach ($destinatairesList as $tel) {
            if (strlen($tel) !== 10 || !ctype_digit($tel)) {
                $this->session->setFlashdata('popup_frais', "Format de numéro invalide : " . $tel);
                return redirect()->to('client/home');
            }
            if ($tel === $expediteur['numero_telephone']) {
                $this->session->setFlashdata('popup_frais', "Vous ne pouvez pas vous transférer de l'argent à vous-même.");
                return redirect()->to('client/home');
            }

            $destOperateur = $this->getOperateurByTelephone($tel);
            if (!$destOperateur) {
                $this->session->setFlashdata('popup_frais', "Opérateur du destinataire non reconnu : " . $tel);
                return redirect()->to('client/home');
            }

            $dest = $clientModel->where('numero_telephone', $tel)->first();
            if (!$dest) {
                $this->session->setFlashdata('popup_frais', "Destinataire introuvable dans la base : " . $tel);
                return redirect()->to('client/home');
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
                    $this->session->setFlashdata('popup_frais', "L'envoi multiple n'est autorisé que vers des numéros du même opérateur que l'expéditeur.");
                    return redirect()->to('client/home');
                }
            }
        }

        $commissionRate = (float)$configModel->getCommission() / 100.0;
        $montantTotalADebiter = 0;

        foreach ($destinatairesData as &$dData) {
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
            $this->session->setFlashdata('popup_frais', "Solde insuffisant pour effectuer ce transfert.");
            return redirect()->to('client/home');
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

        $this->session->set('client_solde', $soldeApresExp);

        $msg = "Transfert réussi vers " . $nombreDest . " destinataire(s).";
        if ($inclureFrais && $destinatairesData[0]['memeOperateur']) {
            $msg .= " Frais de retrait inclus.";
        }
        $this->session->setFlashdata('popup_frais', $msg);
        return redirect()->to('client/home');
    }
}