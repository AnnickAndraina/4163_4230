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
            if ($row['type_operateur'] === 'LOCAL') {
                $result['local'][] = $row;
            } elseif ($row['type_operateur'] === 'EXTERNE') {
                $result['externe'][] = $row;
            }
        }

        return $result;
    }
}