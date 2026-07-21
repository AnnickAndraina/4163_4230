<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;
use App\Models\GainsModel;
use App\Models\ComptesModel;
use App\Models\ConfigurationModel;
use App\Models\MontantsOperateurModel;

class AdminController extends BaseController
{
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

        $operateurModel = new OperateurModel();
        $typeModel      = new TypeOperationModel();
        $gainsModel     = new GainsModel();
        $comptesModel   = new ComptesModel();
        $configModel    = new ConfigurationModel();
        $montantsModel  = new MontantsOperateurModel();

        return view('admin_dashboard', [
            'prefixes'           => $operateurModel->getAll(),
            'types'              => $typeModel->findAll(),
            'gainsTotaux'        => $gainsModel->getTotaux(),
            'comptes'            => $comptesModel->getSituation(),
            'commission'         => $configModel->getCommission(),
            'montantsOperateurs' => $montantsModel->getSituation(),
        ]);
    }

    public function addPrefixe()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $prefixe = $this->request->getPost('prefixe');

        $operateurModel = new OperateurModel();
        $operateurModel->insert([
            'prefixe' => $prefixe,
            'libelle' => 'Opérateur ' . $prefixe,
            'type'    => 'LOCAL',
            'actif'   => 1,
        ]);

        return redirect()->to('admin/dashboard');
    }

    public function togglePrefixe($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $operateurModel = new OperateurModel();
        $operateur = $operateurModel->find($id);

        if ($operateur) {
            $nouvelEtat = $operateur['actif'] ? 0 : 1;
            $operateurModel->toggleActif($id, $nouvelEtat);
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

    public function gainsDetails($categorie)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        if (!in_array($categorie, ['local', 'externe'])) {
            return redirect()->to('admin/dashboard');
        }

        $gainsModel = new GainsModel();

        return view('admin_gains_details', [
            'categorie' => $categorie,
            'details'   => $gainsModel->getDetailsByCategorie($categorie),
        ]);
    }
}