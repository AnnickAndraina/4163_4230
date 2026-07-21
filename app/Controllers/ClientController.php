<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperationModel;

class ClientController extends BaseClientController
{
    public function home()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientModel = new ClientModel();
        $operationModel = new OperationModel();

        $client = $clientModel->find($this->session->get('client_id'));

        if (!$client) {
            $this->session->remove('client_id');
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
                $this->session->setFlashdata('popup_frais', $message);
            }
        } catch (\Exception $e) {}

        return view('home_client', [
            'client' => $client,
            'historique' => $historique
        ]);
    }
}