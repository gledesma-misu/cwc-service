<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TAssistanceController;
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
        Route::get('getBarChartData/{year}', 'getBarChartData');
        Route::get('getChartData/{year}', 'getChartData');
        Route::get('getUnreadNotifications', 'getUnreadNotifications');
        Route::get('getAllNotifications', 'getAllNotifications');
        Route::get('markNotificationAsRead', 'markNotificationAsRead');
        Route::get('clearAllNotifications', 'clearAllNotifications');
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

    Route::controller(TAssistanceController::class)->group(function () {
        Route::post('addRequest', 'addRequest')->middleware('permission:technicalassistance-create');
        Route::post('takeAction', 'takeAction')->middleware('permission:technicalassistance-misu');
        Route::post('completeRequest', 'completeRequest')->middleware('permission:technicalassistance-update');
        Route::post('disregardTask/{id}', 'disregardTask')->middleware('permission:technicalassistance-delete');
        Route::get('countTAPending', 'countTAPending')->middleware('permission:technicalassistance-read');
        Route::get('getPendingRequests', 'getPendingRequests')->middleware('permission:technicalassistance-read');
        Route::get('getAccomplishedRequests', 'getAccomplishedRequests')->middleware('permission:technicalassistance-read');
        Route::get('getTechResponse/{id}', 'getTechResponse')->middleware('permission:technicalassistance-read');
        Route::get('techassistance/list', 'index')->middleware('permission:technicalassistance-read');
    });
});
