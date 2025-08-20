<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Auth;
use Session;
use Hash;
use Validator;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => ['required'],
        //     'email' => ['required', 'email', 'unique:users,email,'.$id]
        // ]);

        $validation = Validator::make($request->all(), [
            'fname' => ['required'],
            'mname' => ['required'],
            'lname' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,' . $id]
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }


        User::where('id', $id)->update([
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        Session::flash('success-message', 'Information updated successfully');
        return redirect()->route('profileIndex');
    }

    public function passwordUpdate(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'password'  => ['required', 'confirmed'],
            'password_confirmation' => ['required']
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }

        User::find($id)->update([
            'password' => Hash::make($request->password),
            'password_text' => $request->password
        ]);

        Session::flash('success', 'Password Updated Successfully');
        return redirect()->route('profileIndex');
    }
}
