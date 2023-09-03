<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('getdr_list', [ApiController::class, 'getdr_list']); 

Route::post('getslot_list', [ApiController::class, 'getslot_list']); 

Route::post('save_appointment', [ApiController::class, 'save_appointment']); 

Route::post('patient_list', [ApiController::class, 'patient_list']); 

Route::post('updatePatient_status', [ApiController::class, 'updatePatient_status']); 

Route::post('dr_list', [ApiController::class, 'dr_list']); 

Route::post('updateDr_status', [ApiController::class, 'updateDr_status']); 