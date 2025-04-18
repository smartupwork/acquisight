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
use App\Mail\InformAccessMail;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showBuyerForm()
    {

        $deals = Deal::where('listing_type', 'public')->get();
        return view('auth.buyer.buyerregistration', ['deals' => $deals]);
    }

    public function save_buyer(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        DealInvitation::create([
            'deal_id' => $request->deal_id,
            'email' => $request->email,
            'token' => Null,
            'accepted' => 1,
            'user_type' => 4
        ]);

        $buyer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => 4,
            'status' => 'active',
            'ip_address' => $request->ip()
        ]);

        Auth::login($buyer);

        return redirect()->route('buyer.detail.show', ['id' => $request->deal_id])
            ->with('success', 'Registration successful! Redirecting to deal details.');
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
        }elseif($user->roles_id == 2){
            return redirect()->route('broker.index');
        }
        if ($user->roles_id == 4) {
            return redirect()->route('buyer.detail.show', ['id' => $user->dealInvitation->deal_id ?? 0]);
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => 2,
            'status' => 'active',
            'ip_address' => $request->ip()
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

        return view('auth.seller.register', ['token' => $token, 'email' => $invitation->email, 'roles_id' => $invitation->user_type]);
    }

    public function registerSeller(Request $request, GcsStorageService $gcsStorageService)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles_id' => 'required'
        ]);


        $invitation = DealInvitation::where('token', $request->token)->firstOrFail();

        if ($invitation->accepted == 1) {
            return redirect()->route('login-view')->with('success', 'You are already registered, Please Login.');
        }

        $seller = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id,
            'status' => 'active',
            'ip_address' => $request->ip()
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

    public function showCopyForm($deal_id)
    {

        return view('auth.buyer.register', ['deal_id' => $deal_id]);
    }

    public function registerBuyer(Request $request)
    {
        $deal_id = $request->deal_id . '/';
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'roles_id' => 'required'
        ]);

        $deal = Deal::where('gcs_deal_id', $deal_id)->first();

        $existingUser = User::where('email', $request->email)->first();

        $existingInvitation = DealInvitation::where('email',  $request->email)
            ->where('deal_id', $deal->id)
            ->first();

        if ($existingUser || $existingInvitation) {
            DealInvitation::create([
                'deal_id' => $deal->id,
                'email' => $request->email,
                'token' => NULL,
                'accepted' => 1,
                'user_type' => $existingUser->roles_id
            ]);

            Mail::to($request->email)->send(new InformAccessMail($deal));
            return redirect()->route('login-view')->with('success', 'Invitation sent successfully!');
        }

        $buyer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id,
            'status' => 'active',
            'ip_address' => $request->ip()
        ]);


        DealInvitation::create([
            'deal_id' => $deal->id,
            'email' => $request->email,
            'token' => NULL,
            'accepted' => 1,
            'user_type' => $request->roles_id
        ]);


        if ($buyer) {
            return redirect('/login-view')->with('success', 'You are registered. Please login.');
        } else {
            return redirect('/login-view')->with('error', 'Sorry, something went wrong.');
        }
    }
}
