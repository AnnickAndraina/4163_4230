<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ConnexionController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $telephone = $this->request->getPost('telephone');

        $model = new ClientModel();
        $client = $model->autoLogin($telephone);

        session()->set('client', $client);

        echo "Connecté ! Numéro : " . esc($client['telephone']) . " | Solde : " . esc($client['solde']) . " Ar";
    }
}