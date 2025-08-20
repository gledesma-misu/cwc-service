<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Session;
use Validator;

class RoleController extends Controller
{
    //
    public function search(Request $request){

        if($request->search_type == 'name'){
            $search_value = $request->search_value;
            $roles = Role::where(function($query) use ($search_value){
                $query->where('name' , 'LIKE', "%$search_value%")
               ->orWhere('display_name' , 'LIKE', "%$search_value%");
               
            })->orderBy('id','desc')->paginate(10);
        }
        return view('management.roles.index', compact('roles', 'search_value'));
   
    }
    public function index()
    {
        $roles = Role::latest()->orderBy('id', 'desc')->paginate(10);
        return view('management.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('management.roles.create');
    }
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'           => 'required',
            'display_name'   => 'required',
            'description'    => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }

        // Check if the role name already exists and is not soft deleted
        $checkDuplicate = Role::where('name', $request->name)->whereNull('deleted_at')->first();

        if ($checkDuplicate) {
            // Role exists and is active, so return validation error
            return back()->withErrors(['name' => 'A role with that name already exists.'])->withInput();
        }

        $existing = Role::withTrashed()->where('name', $request->name)->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Restore the soft-deleted role
                $existing->restore();

                // Update with new values
                $existing->update([
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                ]);
                Session::flash('success', 'Role created successfully');
            } else {
                Session::flash('error', 'Role with that name already exists.');
                return response();
            }
        } else {
            Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);
            Session::flash('success', 'Role created successfully');
        }

       

        return redirect()->route('rolesIndex');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return view('management.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'           => 'required',
            'display_name'   => 'required',
            'description'    => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }

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

        $role = Role::findOrFail($id);

        $role->delete();
        // Role::where('id', $id)->update([
        //     'deleted' => 1
        // ]);
        Session::flash('success', 'Role deleted successfully');
        return redirect()->route('rolesIndex');
    }
}
