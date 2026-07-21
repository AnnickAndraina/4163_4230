<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;
use App\Models\GainsModel;
use App\Models\ComptesModel;
use App\Models\ConfigurationModel;
use App\Models\MontantsOperateurModel;

class EpargneController extends BaseAdminController
{
    public function getEpargne()
    {
        $epargne $this->request->getPost('epargne');
        
    }
}