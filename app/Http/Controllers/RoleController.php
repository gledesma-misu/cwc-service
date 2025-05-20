<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Session;
  
class RoleController extends Controller
{
    //
    public function index(){

        $roles = Role::where('deleted', 0)->get();
        return view('management.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('management.roles.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);
        Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);
        Session::flash('success', 'Role created successfully');
        return redirect()->route('rolesIndex');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return view('management.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);
        Role::where('id', $id)->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);
        Session::flash('success', 'Role updated successfully');
        return redirect()->route('rolesIndex');
    }

    public function delete(Request $request, $id)
    {   
        
        Role::where('id', $id)->update([
            'deleted' => 1
        ]);
        Session::flash('success', 'Role deleted successfully');
        return redirect()->route('rolesIndex');
    }
}
