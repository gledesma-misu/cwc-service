<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('storeDivision', [DivisionController::class, 'storeDivision'])->middleware('auth:api');
Route::post('updateDivision/{id}', [DivisionController::class, 'updateDivision'])->middleware('auth:api');
Route::post('deleteDivision/{id}', [DivisionController::class, 'deleteDivision'])->middleware('auth:api');
Route::get('getDivisions', [DivisionController::class, 'getDivisions'])->middleware('auth:api');

Route::get('getAllDivisions', [ApiController::class, 'getAllDivisions'])->middleware('auth:api');
Route::get('getAllRoles', [ApiController::class, 'getAllRoles'])->middleware('auth:api');
Route::get('getAllPermissions', [ApiController::class, 'getAllPermissions'])->middleware('auth:api');


Route::post('addStaff', [StaffController::class, 'addStaff'])->middleware('auth:api');
Route::get('getStaffs', [StaffController::class, 'getStaffs'])->middleware('auth:api');