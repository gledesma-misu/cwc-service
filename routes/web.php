<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\HomeController; // Import the controller
use  App\Http\Controllers\AuthController; // Import the controller
use  App\Http\Controllers\DivisionController; // Import the controller
use  App\Http\Controllers\StaffController; // Import the controller
use  App\Http\Controllers\RoleController; // Import the controller
use  App\Http\Controllers\PermissionController; // Import the controller
use  App\Http\Controllers\ProfileController; // Import the controller
use App\Http\Controllers\TAssistanceController;
use App\Models\Permission;
use Laratrust\Http\Controllers\RolesController;

Route::get('/', function () {
    return redirect('/login');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');

    Route::controller(DivisionController::class)->group(function () {
        Route::get('divisions/index', 'index')->name('divisionsIndex')->middleware('permission:divisions-read');
        Route::get('divisions/create', 'create')->name('divisionsCreate')->middleware('permission:divisions-create');
        Route::post('divisions/store', 'store')->name('divisionsStore')->middleware('permission:divisions-create');
        Route::get('divisions/edit/{id}', 'edit')->name('divisionsEdit')->middleware('permission:divisions-update');
        Route::post('divisions/update/{id}', 'update')->name('divisionsUpdate')->middleware('permission:divisions-update');
    });

    Route::get('staffs/index', [StaffController::class, 'index'])->name('staffsIndex')->middleware('permission:users-read');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile/index', 'index')->name('profileIndex')->middleware('permission:profile-read');
        Route::post('profile/update/{id}', 'update')->name('profileUpdate')->middleware('permission:profile-update');
        Route::post('profile/password/update/{id}', 'passwordUpdate')->name('profilePasswordUpdate')->middleware('permission:profile-password-update');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles/index', 'index')->name('rolesIndex')->middleware('permission:roles-read');
        Route::get('roles/create', 'create')->name('rolesCreate')->middleware('permission:roles-create');
        Route::post('roles/store', 'store')->name('rolesStore')->middleware('permission:roles-create');
        Route::get('roles/edit/{id}', 'edit')->name('rolesEdit')->middleware('permission:roles-update');
        Route::post('roles/update/{id}', 'update')->name('rolesUpdate')->middleware('permission:roles-update');
        Route::post('roles/delete/{id}', 'delete')->name('rolesDelete')->middleware('permission:roles-delete');
        Route::post('roles/search', 'search')->name('rolesSearch')->middleware('permission:roles-read');
    });

    Route::controller(PermissionController::class)->group(function () {
        Route::get('permissions/index', 'index')->name('permissionsIndex')->middleware('permission:permissions-read');
        Route::get('permissions/create', 'create')->name('permissionsCreate')->middleware('permission:permissions-create');
        Route::post('permissions/store', 'store')->name('permissionsStore')->middleware('permission:permissions-create');
        Route::get('permissions/edit/{id}', 'edit')->name('permissionsEdit')->middleware('permission:permissions-update');
        Route::post('permissions/update/{id}', 'update')->name('permissionsUpdate')->middleware('permission:permissions-update');
        Route::post('permissions/delete/{id}', 'delete')->name('permissionsDelete')->middleware('permission:permissions-delete');
        Route::post('permissions/search', 'search')->name('permissionsSearch')->middleware('permission:permissions-read');
    });

    Route::controller(TAssistanceController::class)->group(function () {
        Route::get('techassistance/index', 'index')->name('techAssistanceIndex')->middleware('permission:technicalassistance-read');
        Route::get('techassistance/reports', 'reports')->name('techAssistanceReports')->middleware('permission:reports-read');
    });
});
