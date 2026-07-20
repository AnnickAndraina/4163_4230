<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table         = 'operateur';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['libelle', 'prefixe', 'type', 'actif'];

    public function getAll()
    {
        return $this->orderBy('prefixe', 'ASC')->findAll();
    }

    public function toggleActif($id, $actif)
    {
        return $this->update($id, ['actif' => $actif]);
    }

    public function getLocalActifs()
    {
        return $this->where('type', 'LOCAL')->where('actif', 1)->findAll();
    }

    public function getByPrefixe($prefixe)
    {
        return $this->where('prefixe', $prefixe)->where('actif', 1)->first();
    }
}