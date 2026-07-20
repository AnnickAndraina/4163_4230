<?php

namespace App\Controllers;

use App\Models\PrefixeOperateurModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;
use App\Models\GainsModel;
use App\Models\ComptesModel;

class AdminController extends BaseController
{
    // Vérifie que l'admin est bien connecté, sinon renvoie vers le login.
    // Appelé au début de chaque méthode pour éviter la répétition.
    private function checkAuth()
    {
        if (!session()->get('admin_connecte')) {
            return redirect()->to('/');
        }
        return null;
    }

    public function dashboard()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $prefixeModel = new PrefixeOperateurModel();
        $typeModel    = new TypeOperationModel();
        $baremeModel  = new BaremeFraisModel();
        $gainsModel   = new GainsModel();
        $comptesModel = new ComptesModel();

        return view('admin_dashboard', [
            'prefixes' => $prefixeModel->getAll(),
            'types'    => $typeModel->findAll(),
            'baremes'  => $baremeModel->getActifsAvecType(),
            'gains'    => $gainsModel->getSituation(),
            'comptes'  => $comptesModel->getSituation(),
        ]);
    }

    public function addPrefixe()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $prefixe = $this->request->getPost('prefixe');

        $prefixeModel = new PrefixeOperateurModel();
        $prefixeModel->insert([
            'prefixe' => $prefixe,
            'actif'   => 1,
        ]);

        return redirect()->to('admin/dashboard');
    }

    public function togglePrefixe($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $prefixeModel = new PrefixeOperateurModel();
        $prefixe = $prefixeModel->find($id);

        if ($prefixe) {
            $nouvelEtat = $prefixe['actif'] ? 0 : 1;
            $prefixeModel->toggleActif($id, $nouvelEtat);
        }

        return redirect()->to('admin/dashboard');
    }

    public function addBareme()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $baremeModel = new BaremeFraisModel();
        $baremeModel->remplacerTranche(
            $this->request->getPost('type_operation_id'),
            $this->request->getPost('montant_min'),
            $this->request->getPost('montant_max'),
            $this->request->getPost('frais')
        );

        return redirect()->to('admin/dashboard');
    }

    public function logout()
    {
        session()->remove('admin_connecte');
        return redirect()->to('/');
    }
}