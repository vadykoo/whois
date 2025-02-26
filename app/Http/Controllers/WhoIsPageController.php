<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhoIsPageController extends Controller
{
    public function index()
    {
        return view('whois');
    }
}
