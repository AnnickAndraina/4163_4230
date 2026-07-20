<?php

namespace App\Models;

use CodeIgniter\Model;

class GainsModel extends Model
{
    protected $table      = 'vue_situation_gains';
    protected $primaryKey = 'code_operation'; 

    public function getSituation()
    {
        return $this->findAll();
    }
}