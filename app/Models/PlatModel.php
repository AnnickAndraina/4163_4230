<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatModel extends Model
{
    protected $table = 'plats';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'name',
        'emoji',
        'img',
        'cat',
        'time',
        'cal',
        'rating',
        'description',
    ];
}
