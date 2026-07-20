<?php

namespace App\Controllers;

use App\Models\PrefixeOperateurModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;
use App\Models\GainsModel;
use App\Models\ComptesModel;
use App\Models\ConfigurationModel;

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
        $gainsModel   = new GainsModel();
        $comptesModel = new ComptesModel();
        $configModel  = new ConfigurationModel();

        return view('admin_dashboard', [
            'prefixes' => $prefixeModel->getAll(),
            'types'    => $typeModel->findAll(),
            'gains'    => $gainsModel->getSituation(),
            'comptes'  => $comptesModel->getSituation(),
            'commission' => $configModel->getCommission(),
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

        $typeId = $this->request->getPost('type_operation_id');
        $baremeModel = new BaremeFraisModel();

        $baremeModel->ajouterTranche(
            $typeId,
            $this->request->getPost('montant_min'),
            $this->request->getPost('montant_max'),
            $this->request->getPost('frais')
        );

        return redirect()->to('admin/bareme/type/' . $typeId);
    }

    public function baremeType($typeId)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $typeModel   = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();

        $type = $typeModel->find($typeId);
        if (!$type) {
            return redirect()->to('admin/dashboard');
        }

        return view('admin_bareme_type', [
            'type'    => $type,
            'baremes' => $baremeModel->getByType($typeId),
        ]);
    }

    public function updateBareme()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $baremeModel = new BaremeFraisModel();
        $id     = $this->request->getPost('id');
        $typeId = $this->request->getPost('type_operation_id');

        $baremeModel->modifierTranche(
            $id,
            $this->request->getPost('montant_min'),
            $this->request->getPost('montant_max'),
            $this->request->getPost('frais')
        );

        return redirect()->to('admin/bareme/type/' . $typeId);
    }

    public function updateCommission()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $pourcentage = $this->request->getPost('commission_autre_operateur');
        $configModel = new ConfigurationModel();
        $configModel->updateCommission($pourcentage);

        return redirect()->to('admin/dashboard');
    }

    public function logout()
    {
        session()->remove('admin_connecte');
        return redirect()->to('/');
    }
}