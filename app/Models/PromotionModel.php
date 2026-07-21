<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table = 'promotion';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['commission_meme_operateur'];
    protected $useTimestamps = false;

    public function getPromotion()
    {
        $config = $this->first();
        return $config ? $config['commission_meme_operateur'] : 0;
    }

    public function updatePromotion($pourcentage)
    {
        $config = $this->first();
        if ($config) {
            return $this->update($config['id'], ['commission_meme_operateur' => $pourcentage]);
        } else {
            return $this->insert(['commission_meme_operateur' => $pourcentage]);
        }
    }
}