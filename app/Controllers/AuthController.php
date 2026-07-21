<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperateurModel;

class AuthController extends BaseController
{
    public function index()
    {
        return view('login', [
            'values' => ['telephone' => '0331234567'],
            'errors' => ['telephone' => '']
        ]);
    }

    public function login()
    {
        $telephoneRaw = $this->request->getPost('telephone');
        $telephone = str_replace(' ', '', $telephoneRaw);

        $values = ['telephone' => $telephoneRaw];
        $errors = ['telephone' => ''];

        if (strlen($telephone) !== 10 || !ctype_digit($telephone)) {
            $errors['telephone'] = 'Le numéro doit comporter exactement 10 chiffres.';
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $prefixe = substr($telephone, 0, 3);

        $operateurModel = new OperateurModel();
        $operateur = $operateurModel->where('prefixe', $prefixe)->where('actif', 1)->first();

        if (!$operateur) {
            $errors['telephone'] = 'Opérateur non supporté (préfixe invalide).';
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $model = new ClientModel();
        $client = $model->autoLogin($telephone);

        if ($operateur['type'] !== 'LOCAL') {
            $errors['telephone'] = 'Cet opérateur ne permet pas la connexion à l\'application.';
            $values['telephone'] = $telephoneRaw;
            return view('login', ['values' => $values, 'errors' => $errors]);
        }

        $this->session->set('client_id', $client['id']);
        $this->session->set('client_telephone', $client['numero_telephone']);
        $this->session->set('client_nom', $client['nom']);
        $this->session->set('client_solde', $client['solde']);
        $this->session->set('client_operateur_id', $operateur['id']);

        return redirect()->to('client/home');
    }

    public function loginAdmin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username === 'admin' && $password === 'admin123') {
            $this->session->set('admin_connecte', true);
            return redirect()->to('admin/dashboard');
        }

        $this->session->setFlashdata('admin_error_flag', true);
        return redirect()->back()->with('error', 'Identifiants Admin incorrects.');
    }

    public function logout()
    {
        $this->session->remove('client_id');
        $this->session->remove('client_telephone');
        $this->session->remove('client_nom');
        $this->session->remove('client_solde');
        $this->session->remove('client_operateur_id');
        $this->session->remove('admin_connecte');
        return redirect()->to('/');
    }
}