<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'numero_telephone', 'solde', 'status'];

    public function autoLogin($telephone)
    {
        $client = $this->where('numero_telephone', $telephone)->first();

        if (!$client) {
            $this->insert([
                'nom'              => 'Client_' . $telephone,
                'numero_telephone' => $telephone,
                'solde'            => 0.00,
                'status'           => 'actif'
            ]);
            return $this->where('numero_telephone', $telephone)->first();
        }

        return $client;
    }
}