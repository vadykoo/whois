<?php

use App\Http\Controllers\Api\WhoisController;
use Illuminate\Support\Facades\Route;

Route::post('/whois', [WhoIsController::class, 'whoIsInfo']);

