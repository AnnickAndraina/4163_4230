<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table         = 'bareme_frais';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['type_operation_id', 'montant_min', 'montant_max', 'frais', 'actif'];

    public function getActifsAvecType()
    {
        return $this->select('bareme_frais.*, type_operation.libelle as type_libelle, type_operation.code as type_code')
                    ->join('type_operation', 'type_operation.id = bareme_frais.type_operation_id')
                    ->where('bareme_frais.actif', 1)
                    ->orderBy('type_operation.id', 'ASC')
                    ->orderBy('bareme_frais.montant_min', 'ASC')
                    ->findAll();
    }

    public function remplacerTranche($typeOperationId, $montantMin, $montantMax, $frais)
    {
        $this->where('type_operation_id', $typeOperationId)
             ->where('actif', 1)
             ->set(['actif' => 0])
             ->update();

        return $this->insert([
            'type_operation_id' => $typeOperationId,
            'montant_min'       => $montantMin,
            'montant_max'       => $montantMax,
            'frais'             => $frais,
            'actif'             => 1,
        ]);
    }
}