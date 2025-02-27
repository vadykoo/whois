<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhoIsPageController extends Controller
{
    /**
     * Display the whois page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('whois');
    }
}
