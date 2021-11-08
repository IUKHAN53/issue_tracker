<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitLab\Facades\GitLab;
use GrahamCampbell\GitLab\GitLabManager;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
