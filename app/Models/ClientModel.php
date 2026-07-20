<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['telephone', 'solde'];

    public function autoLogin($telephone)
    {
        $client = $this->where('telephone', $telephone)->first();

        if (!$client) {
            $this->insert([
                'telephone' => $telephone,
                'solde'     => 0
            ]);
            return $this->where('telephone', $telephone)->first();
        }

        return $client;
    }
}