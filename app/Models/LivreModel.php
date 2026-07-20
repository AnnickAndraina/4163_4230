<?php
namespace App\Models;
use CodeIgniter\Model;

class LivreModel extends Model
{
    protected $table = 'livres';
    protected $primaryKey = 'id_livre';


    protected $allowedFields = [
        'titre',
        'auteur',
        'ISBN',
        'annee_publication',
        'categorie',
        'resume',
        'fichier_couverture',
        'statut'
    ];
}