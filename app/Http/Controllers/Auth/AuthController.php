<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // if($user->status == 'inactive'){
        //     return redirect()->route('login')->with('success', 'You will be notified via email when your account has been activated.');
        // }

      
        Auth::login($user);
       
        if ($user->roles_id == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-view');
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->role,
            'status' => 'active',
        ]);

        if($user){
            return redirect('/login-view')->with('success', 'Account created successfully. Please login.');
        }else{
            return redirect('/login-view')->with('error', 'Sorry, something went wrong.');
        }
        
    }
}
