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
        return view('auth.login'); // Create a login.blade.php view
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();
            $roleName = Role::where('id', $user->roles_id)->value('name');

            // Redirect based on roles_id
            switch ($roleName) {
                case 'Admin': // Admin
                    return redirect()->route('admin.dashboard');
                default:
                    if (in_array($roleName, ['Employee', 'Seller', 'Buyer', 'Broker'])) {
                        return redirect()->route('user.dashboard');
                    }
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirect to the login page
    }


    public function showRegisterForm()
    {
        return view('auth.register'); // Create a register.blade.php view
    }

    public function register(Request $request)
    {


        // dd($request);
        // exit;
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Account created successfully. Please login.');
    }
}
