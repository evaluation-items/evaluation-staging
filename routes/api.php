<?php
use App\Http\Controllers\CsproDataController;

Route::post('/fetch-cspro-data', [CsproDataController::class, 'fetchData']);
