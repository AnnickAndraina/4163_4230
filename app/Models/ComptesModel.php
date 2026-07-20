<?php

namespace App\Models;

use CodeIgniter\Model;

class ComptesModel extends Model
{
    protected $table      = 'vue_situation_comptes';
    protected $primaryKey = 'id';

    public function getSituation()
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }
}