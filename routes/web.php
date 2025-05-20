<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\HomeController; // Import the controller
use  App\Http\Controllers\DivisionController; // Import the controller
use  App\Http\Controllers\StaffController; // Import the controller
use  App\Http\Controllers\RoleController; // Import the controller
use  App\Http\Controllers\PermissionController; // Import the controller
use Laratrust\Http\Controllers\RolesController;

Route::get('/', [HomeController::class, 'dashboard'])->middleware('auth');

Route::get('divisions/index', [DivisionController::class, 'index'])->name('divisionsIndex')->middleware('auth');
Route::get('divisions/create', [DivisionController::class, 'create'])->name('divisionsCreate')->middleware('auth');
Route::post('divisions/store', [DivisionController::class, 'store'])->name('divisionsStore')->middleware('auth');
Route::get('divisions/edit/{id}', [DivisionController::class, 'edit'])->name('divisionsEdit')->middleware('auth');
Route::post('divisions/update/{id}', [DivisionController::class, 'update'])->name('divisionsUpdate')->middleware('auth');

Route::get('staffs/index', [StaffController::class,'index'])->name('staffsIndex'); 

Route::get('roles/index', [RoleController::class, 'index'])->name('rolesIndex')->middleware('auth');
Route::get('roles/create', [RoleController::class, 'create'])->name('rolesCreate')->middleware('auth');
Route::post('roles/store', [RoleController::class, 'store'])->name('rolesStore')->middleware('auth');
Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('rolesEdit')->middleware('auth');
Route::post('roles/update/{id}', [RoleController::class, 'update'])->name('rolesUpdate')->middleware('auth');
Route::post('roles/delete/{id}', [RoleController::class, 'delete'])->name('rolesDelete')->middleware('auth');


Route::get('permissions/index', [PermissionController::class, 'index'])->name('permissionsIndex')->middleware('auth');
Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissionsCreate')->middleware('auth');
Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissionsStore')->middleware('auth');
Route::get('permissions/edit/{id}', [PermissionController::class, 'edit'])->name('permissionsEdit')->middleware('auth');
Route::post('permissions/update/{id}', [PermissionController::class, 'update'])->name('permissionsUpdate')->middleware('auth');
Route::post('permissions/delete/{id}', [PermissionController::class, 'delete'])->name('permissionsDelete')->middleware('auth');