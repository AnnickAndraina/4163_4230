<?php

namespace App\Controllers;

class BaseAdminController extends BaseController
{
    protected function checkAuth()
    {
        if (!$this->session->get('admin_connecte')) {
            return redirect()->to('/');
        }
        return null;
    }
}