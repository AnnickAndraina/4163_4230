<?php

namespace App\Models;

use CodeIgniter\Model;

class GainsModel extends Model
{
    protected $table      = 'vue_situation_gains';
    protected $primaryKey = 'code_operation'; 

    public function getSituation()
    {
        $data = $this->findAll();

        $result = [
            'local' => [],
            'externe' => []
        ];

        foreach ($data as $row) {
            if (isset($row['type_operateur']) && $row['type_operateur'] === 'LOCAL') {
                $result['local'][] = $row;
            } elseif (isset($row['type_operateur']) && $row['type_operateur'] === 'EXTERNE') {
                $result['externe'][] = $row;
            }
        }
        return $result;
    }

    public function getTotaux()
    {
        $data = $this->findAll();

        $totaux = ['local' => 0, 'externe' => 0];

        foreach ($data as $row) {
            if ($row['type_operateur'] === 'LOCAL') {
                $totaux['local'] += (float) $row['total_gains'];
            } elseif ($row['type_operateur'] === 'EXTERNE') {
                $totaux['externe'] += (float) $row['total_gains'];
            }
        }

        return $totaux;
    }

    public function getDetailsByCategorie($categorie)
    {
        $typeOperateur = strtoupper($categorie) === 'LOCAL' ? 'LOCAL' : 'EXTERNE';
        $data = $this->where('type_operateur', $typeOperateur)->findAll();

        $details = [];
        foreach ($data as $row) {
            $code = $row['code_operation'];
            if (!isset($details[$code])) {
                $details[$code] = [
                    'type_operation'    => $row['type_operation'],
                    'total_gains'       => 0,
                    'nombre_operations' => 0,
                ];
            }
            $details[$code]['total_gains']       += (float) $row['total_gains'];
            $details[$code]['nombre_operations'] += (int) $row['nombre_operations'];
        }

        return $details;
    }
}