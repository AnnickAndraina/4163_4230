<?php

namespace App\Controllers;

use Config\Database;

class ConnexionController extends BaseController

{
    public function connexion()
    {
        try {
            $db = Database::connect();



            if ($db->connect()) {
                echo "Connexion réussie !";
            }
        } catch (\Exception $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }
}
