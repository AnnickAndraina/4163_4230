<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table = 'configuration';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['commission_autre_operateur'];
    protected $useTimestamps = false;

    public function getCommission()
    {
        $config = $this->first();
        return $config ? $config['commission_autre_operateur'] : 0;
    }

    public function updateCommission($pourcentage)
    {
        $config = $this->first();
        if ($config) {
            return $this->update($config['id'], ['commission_autre_operateur' => $pourcentage]);
        } else {
            return $this->insert(['commission_autre_operateur' => $pourcentage]);
        }
    }
}
