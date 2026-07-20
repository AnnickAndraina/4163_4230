<?php

namespace App\Controllers;

use Config\Database;
use App\Models\PlatModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login.php'); 
    }
    public function home(): string
    {
        return view('home.html');
    }
    public function stats(): string
    {
        return view('stats.html');
    }
    public function inscription(): string
    {
        return view('inscription.php');
    }
}