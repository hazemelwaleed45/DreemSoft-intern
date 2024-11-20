<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultantController ;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/consultants', [ConsultantController::class, 'index']);

Route::get('/consultant-booking/{id}/{Date}', [ConsultantController::class, 'getConsultantDetails']);
Route::get('/consultant-booking/{id}', [ConsultantController::class, 'getConsultantAllSlots']);
Route::get('/consultant-showWeekly/{id}', [ConsultantController::class, 'GETSLOTS']);
Route::get('/consultants/{id}', [ConsultantController::class, 'show']);

Route::get('/consultants/{consultantId}/weekly-slots', [ConsultantController::class, 'getWeeklySlots']);