<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperationModel;

class RetraitController extends BaseClientController
{
    public function retrait()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $montant = (float) $this->request->getPost('montant');
        if ($montant <= 0) {
            $this->session->setFlashdata('popup_frais', "Le montant doit être supérieur à 0.");
            return redirect()->to('client/home');
        }

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();
        $client = $clientModel->find($this->session->get('client_id'));

        $frais = $this->getFrais(2, $montant);
        $montantTotal = $montant + $frais;

        if ((float)$client['solde'] < $montantTotal) {
            $this->session->setFlashdata('popup_frais', "Solde insuffisant.");
            return redirect()->to('client/home');
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

        $this->session->set('client_solde', $soldeApres);
        $this->session->setFlashdata('popup_frais', "Retrait réussi. Frais : " . number_format($frais, 0, ',', ' ') . " Ar.");
        return redirect()->to('client/home');
    }
}