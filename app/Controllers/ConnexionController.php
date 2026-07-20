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

    public function loginAdmin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username === 'admin' && $password === 'admin123') {
            session()->set('admin_connecte', true);
            
            echo "<h1>Interface Opérateur</h1>";
            echo "* Situation gain via les différents frais (retrait et transfert)<br>";
            echo "* Situation des comptes clients";
            return;
        }

        return redirect()->back()->with('error', 'Identifiants Admin incorrects.');
    }
}