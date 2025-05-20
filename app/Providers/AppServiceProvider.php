<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
       $roles = Role::all();
       foreach($roles as $role){
        Gate::define($role->name, function($user) use ($role) {
            return $user->hasRole($role->name);
        });
       }

       $permissions = Permission::all();
       foreach($permissions as $permission){
        Gate::define($permission->name, function($user) use ($permission) {
            return $user->hasPermission($permission->name);
        });
       }
    }
}
