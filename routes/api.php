<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\StaffController;
use App\Http\Middleware\ForceToJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware(['forcetojson', 'auth:api'])->group(function () {

    Route::controller(DivisionController::class)->group(function () {
        Route::get('searchDivision', 'searchDivision')->middleware('permission:divisions-read');
        Route::post('storeDivision', 'storeDivision')->middleware('permission:divisions-create');
        Route::post('updateDivision/{id}', 'updateDivision')->middleware('permission:divisions-update');
        Route::post('deleteDivision/{id}', 'deleteDivision')->middleware('permission:divisions-delete');
        Route::get('getDivisions', 'getDivisions')->middleware('permission:divisions-read');
    });

    Route::controller(ApiController::class)->group(function () {
        Route::get('getAllDivisions', 'getAllDivisions')->middleware('permission:divisions-read');
        Route::get('getAllRoles', 'getAllRoles')->middleware('permission:roles-read');
        Route::get('getAllPermissions', 'getAllPermissions')->middleware('permission:permissions-read');
    });

    Route::controller(StaffController::class)->group(function () {
        Route::get('searchUser', 'searchUser')->middleware('permission:users-read');
        Route::post('addStaff', 'addStaff')->middleware('permission:users-read');
        Route::post('updateStaff/{id}', 'updateStaff')->middleware('permission:users-read');
        Route::post('deleteStaff/{id}', 'deleteStaff')->middleware('permission:users-read');
        Route::get('getStaffs', 'getStaffs')->middleware('permission:users-read');
    });
});
