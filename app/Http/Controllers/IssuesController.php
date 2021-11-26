<?php

namespace App\Http\Controllers;

use Gitlab\Client;

class IssuesController extends Controller
{
    public function index()
    {
        return view('issues_home')->with('gitlab');
    }

    public function activeIssues()
    {
        return view('active_issues')->with('gitlab');
    }

    public function danglingIssues()
    {
        return view('dangling_issues')->with('gitlab');
    }
}
