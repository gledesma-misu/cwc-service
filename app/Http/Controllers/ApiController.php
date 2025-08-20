<?php

namespace App\Http\Controllers;
use App\Models\Division;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function getAllDivisions()
    {
        return response()->json(Division::get());
    }
    public function getAllRoles()
    {
        return response()->json(Role::get());
    }
    public function getAllPermissions()
    {
        return response()->json(Permission::where('deleted', 0)->get());
    }
}
