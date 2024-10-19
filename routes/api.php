<?php

use App\Http\Middleware\IpWhitelist;
use App\Models\LuminaireFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/luminaires', function (Request $request) {
    return LuminaireFamily::pluck('sheet_id', 'name');
})->middleware(IpWhitelist::class);