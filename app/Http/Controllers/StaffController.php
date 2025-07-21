<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class StaffController extends Controller
{
    public function index()
    {
        return view('management.staffs.index');
    }

    public function getStaffs(){
        return response()->json(User::where('deleted', 0)->latest()->get());
    }

    public function addStaff(Request $request)
    {
        // return $request->all();
        $request->validate([
            'fname' => 'required',
            'mname' => 'required',
            'lname' => 'required',
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
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_text' => $request->password,
        ]);
        $user->syncPermissions($request->selected_permissions);
        $user->syncRoles($request->selected_roles);

        return response()->json('success');
    }
}
