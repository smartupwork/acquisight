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
use App\Mail\VerifyEmail;


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

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            $existingInvitation = DealInvitation::where('email', $request->email)
                ->where('deal_id', $request->deal_id)
                ->first();

            if ($existingInvitation) {
                return redirect()->back()->with('info', 'You are already registered for this deal.');
            }

            DealInvitation::create([
                'deal_id' => $request->deal_id,
                'email' => $request->email,
                'token' => null,
                'accepted' => 1,
                'user_type' => 4
            ]);

            Auth::login($existingUser);
            return redirect()->route('buyer.index');
        } else {

            $request->validate([
                'deal_id' => 'required|exists:deals,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ]);

            $buyer = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'verification_token' => Str::random(64),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'roles_id' => 4,
                'status' => 'active',
                'ip_address' => $request->ip(),
            ]);

            DealInvitation::create([
                'deal_id' => $request->deal_id,
                'email' => $request->email,
                'token' => null,
                'accepted' => 1,
                'user_type' => 4
            ]);

            Mail::to($request->email)->send(new VerifyEmail($buyer));

            return redirect()->route('verification.notice')->with('message', 'Please check your email to verify your account.');
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/login-view')->with(['error' => 'Invalid verification link.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        Auth::login($user);

        switch ($user->roles_id) {
            case 1:
                return redirect()->route('admin.dashboard');
            case 2:
                return redirect()->route('broker.index');
            case 3:
                return redirect()->route('seller.index');
            case 4:
                return redirect()->route('buyer.index');
            default:
                return redirect()->route('user.dashboard');
        }
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

        if (!$user->email_verified_at) {
            return redirect()->back()->with(['error' => 'Please verify your email before logging in.']);
        }


        Auth::login($user);

        if ($user->roles_id == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles_id == 3) {
            return redirect()->route('seller.index');
        } elseif ($user->roles_id == 2) {
            return redirect()->route('broker.index');
        }
        if ($user->roles_id == 4) {
            return redirect()->route('buyer.index');
            // return redirect()->route('buyer.detail.show', ['id' => $user->dealInvitation->deal_id ?? 0]);
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


        $buyer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'verification_token' => Str::random(64),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id,
            'status' => 'active',
            'ip_address' => $request->ip()
        ]);

        // $seller = User::create([
        //     'name' => $request->name,
        //     'email' => $invitation->email,
        //     'password' => Hash::make($request->password),
        //     'roles_id' => $request->roles_id,
        //     'status' => 'active',
        //     'ip_address' => $request->ip()
        // ]);


        $invitation->update(['accepted' => 1, 'token' => NULL]);

        Mail::to($request->email)->send(new VerifyEmail($buyer));

        return redirect()->route('verification.notice')->with('message', 'Please check your email to verify your account.');

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

        $existingUser = User::where('email', $request->email)->first();

        $deal_id = $request->deal_id . '/';
        $deal = Deal::where('gcs_deal_id', $deal_id)->first();

        if ($existingUser) {

            $existingInvitation = DealInvitation::where('email',  $request->email)
                ->where('deal_id', $deal->id)
                ->first();

            if ($existingInvitation) {
                return redirect()->back()->with('info', 'You are already registered for this deal.');
            }


            DealInvitation::create([
                'deal_id' => $deal->id,
                'email' => $request->email,
                'token' => null,
                'accepted' => 1,
                'user_type' => $request->roles_id
            ]);

            Mail::to($request->email)->send(new InformAccessMail($deal));
            return redirect()->route('login-view')->with('success', 'You have now access to .' . $deal->name . '.. Please login.');
        } else {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
                'roles_id' => 'required'
            ]);

            $buyer = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'verification_token' => Str::random(64),
                'phone' => $request->phone,
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


            Mail::to($request->email)->send(new VerifyEmail($buyer));

            return redirect()->route('verification.notice')->with('message', 'Please check your email to verify your account.');
        }
    }
}
