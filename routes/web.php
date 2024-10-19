<?php

use App\Http\Controllers\GoogleApiController;
use App\Models\Luminaire;
use App\Models\LuminaireFamily;
use App\Settings\IndividualCardSettings;
use App\Settings\SheetSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;



Route::get('/generate', function (Request $request) {
    Storage::disk("public")->makeDirectory('testowy');
});


