<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeOperateurModel extends Model
{
    protected $table         = 'prefixe_operateur';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['prefixe', 'actif'];

    // Liste tous les préfixes (actifs et inactifs), pour affichage admin
    public function getAll()
    {
        return $this->orderBy('prefixe', 'ASC')->findAll();
    }

    public function toggleActif($id, $actif)
    {
        return $this->update($id, ['actif' => $actif]);
    }
}