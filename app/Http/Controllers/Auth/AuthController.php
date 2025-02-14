<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\DealInvitation;
use App\Models\Deal;
use App\Services\GcsStorageService;
use Google\Service\Drive\Permission;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Str;

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
        } elseif ($user->roles_id == 3) {
            return redirect()->route('seller.index');
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

        if ($user) {
            return redirect('/login-view')->with('success', 'Account created successfully. Please login.');
        } else {
            return redirect('/login-view')->with('error', 'Sorry, something went wrong.');
        }
    }

    public function showSellerRegistrationForm($token)
    {
        $invitation = DealInvitation::where('token', $token)->firstOrFail();

        if ($invitation->accepted == 1) {
            return redirect()->route('login-view')->with('info', 'You are already registered.');
        }

        return view('auth.seller.register', ['token' => $token, 'email' => $invitation->email]);
    }

    public function registerSeller(Request $request, GcsStorageService $gcsStorageService)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);


        $invitation = DealInvitation::where('token', $request->token)->firstOrFail();

        if ($invitation->accepted == 1) {
            return redirect()->route('login-view')->with('success', 'You are already registered, Please Login.');
        }

        $seller = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'roles_id' => 3,
            'status' => 'active',
        ]);


        $invitation->update(['accepted' => 1, 'token' => null]);


        if ($seller) {
            return redirect('/login-view')->with('success', 'You are registered as Seller. Please login.');
        } else {
            return redirect('/login-view')->with('error', 'Sorry, something went wrong.');
        }
    }

    public function forgetPassword()
    {

        return view('auth.forgot');
    }

    public function requestReset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        $user = User::where('email', $request->email)->first();

        $password = Str::random(rand(8, 10));

        $user->password = Hash::make($password);
        $user->save();

        $mail =  Mail::to($user->email)->send(new PasswordResetMail($user, $password));

        if ($mail) {
            return redirect('/login-view')->with('success', 'Your password is reset, please check yoour email.');
        } else {
            return redirect('/login-view')->with('error', 'Sorry, Something went wrong!');
        }
    }
}
