<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use DB;
use Hash;
use Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::where('username', $request->username)->first();

            //execute php artisan passport:client --personal
            shell_exec('php ../artisan passport:install'); //execute on new login

            $successToken = $user->createToken('mis_token')->accessToken;
            session()->put('token', $successToken);

            return redirect()->route('dashboard')->with('success', 'You have successfully logged in!');
        } 
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.'
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        DB::table('oauth_access_tokens')->where('id', $request->token_id)->where('user_id', $user->id)->update(['revoked' => 1]);

        Auth::logout();


        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'fname' => ['required'],
            'mname' => ['required'],
            'lname' => ['required'],
            'username' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);


        User::create([

            'fname'   => $request->fname,
            'mname'   => $request->mname,
            'lname'   => $request->lname,
            'username'   => $request->username,
            'email'   => $request->email,
            'password'   => Hash::make($request->password),
            'text_password'   => $request->password,
        ]);

        Session::flash('success-message', 'Account created Successfully');

        return redirect('/login');
    }
}
