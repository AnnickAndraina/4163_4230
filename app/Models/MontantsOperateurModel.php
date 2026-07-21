<?php

namespace App\Models;

use CodeIgniter\Model;

class MontantsOperateurModel extends Model
{
    protected $table      = 'vue_situation_montants_operateurs';
    protected $primaryKey = 'operateur_libelle';

    public function getSituation()
    {
        return $this->orderBy('operateur_libelle', 'ASC')->findAll();
    }
}