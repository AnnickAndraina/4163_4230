<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operation';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'type_operation_id',
        'client_id',
        'client_destinataire_id',
        'operateur_destination_id',
        'commission',
        'montant',
        'frais_applique',
        'montant_total',
        'solde_avant',
        'solde_apres',
        'statut'
    ];

    public function getHistoriqueClient($clientId)
    {
        return $this->select('operation.*, type_operation.libelle as type_libelle')
                    ->join('type_operation', 'type_operation.id = operation.type_operation_id')
                    ->where('client_id', $clientId)
                    ->orWhere('client_destinataire_id', $clientId)
                    ->orderBy('date_operation', 'DESC')
                    ->findAll();
    }
}