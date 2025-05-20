<?php

use App\Http\Controllers\DivisionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('storeDivision', [DivisionController::class, 'storeDivision'])->middleware('auth:api');
Route::post('updateDivision/{id}', [DivisionController::class, 'updateDivision'])->middleware('auth:api');
Route::post('deleteDivision/{id}', [DivisionController::class, 'deleteDivision'])->middleware('auth:api');
Route::get('getDivisions', [DivisionController::class, 'getDivisions'])->middleware('auth:api');