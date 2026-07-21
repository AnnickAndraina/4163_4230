<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class BaseClientController extends BaseController
{
    protected function checkAuth()
    {
        if (!$this->session->has('client_id')) {
            return redirect()->to('/');
        }
        return null;
    }

    protected function getFrais($typeOperationId, $montant)
    {
        $db = \Config\Database::connect();
        $bareme = $db->table('bareme_frais')
            ->where('type_operation_id', $typeOperationId)
            ->where('actif', 1)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->get()
            ->getRow();

        return $bareme ? (float)$bareme->frais : 0.0;
    }

    protected function getOperateurByTelephone($telephone)
    {
        $prefixe = substr(str_replace(' ', '', $telephone), 0, 3);
        $model = new OperateurModel();
        return $model->where('prefixe', $prefixe)->where('actif', 1)->first();
    }
}