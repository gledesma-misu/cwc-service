<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class StaffController extends Controller
{

    public function searchUser()
    {
        if ($search = \Request::get('name')) {
            $user = User::where(function ($query) use ($search) {
                $query->where('fname', 'LIKE', "%$search%")
                    ->orWhere('mname', 'LIKE', "%$search%")
                    ->orWhere('lname', 'LIKE', "%$search%")
                    ->orWhere('username', 'LIKE', "%$search%");
            })->with('division')->with('roles')->with('permissions')->latest()->paginate(10);
        } else {
            $user = User::with('division')->with('roles')->with('permissions')->latest()->paginate(10);
        }
        return response()->json($user);
    }

    public function index()
    {
        return view('management.staffs.index');
    }

    public function getStaffs()
    {
        return response()->json(User::where('deleted', 0)->with('division')->with('roles')->with('permissions')->latest()->paginate(10));
    }

    public function addStaff(Request $request)
    {
        // return $request->all();
        $request->validate([
            'fname' => 'required',
            'mname' => 'required',
            'lname' => 'required',
            'emp_id' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($request->division_id != '') {
            $division_id = $request->division_id;
        } else {
            $division_id = 0;
        }
        $user = User::create([
            'division_id' => $division_id,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'emp_id' => $request->emp_id,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_text' => $request->password,
        ]);
        $user->syncPermissions($request->selected_permissions);
        $user->syncRoles($request->selected_roles);

        Session::flash('success', 'Employee Added Successfully');
        return response()->json('success');
    }
    public function updateStaff(Request $request, $id)
    {
        $request->validate([
            'fname'             => 'required',
            'lname'             => 'required',
            'email'            => 'required',
            'emp_id'         => 'required',
        ]);

        $user = User::findOrFail($id);

        if ($request->division_id !=  '') {
            $division_id = $request->division_id;
        } else {
            $division_id = 0;
        }

        if ($request->password === null) {
            $password = $user->password;
            $password_text = $user->password_text;
        } else {
            $password = Hash::make($request->password);
            $password_text = $request->password;
        }


        User::where('id', $id)->update([
            'division_id' => $division_id,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'emp_id' => $request->emp_id,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $password,
            'password_text' => $password_text,
        ]);

        $user->syncRoles($request->selected_roles);
        $user->syncPermissions($request->selected_permissions);

        Session::flash('success', 'Employee Updated Successfully');
        return response()->json('success');
    }
    public function deleteStaff(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->permissions()->detach();
        User::where('id', $id)->update([
            'deleted' => 1
        ]);
        return response()->json('success');
    }
}
