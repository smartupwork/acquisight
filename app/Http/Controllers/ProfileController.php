<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function view($id)
    {
        $user = User::with('role')->first();
        return view('backend.profile.view', compact('user'));
    }

    public function store(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:500',
        ]);


        if ($user) {
            $user->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'dob' => $request->input('dob'),
                'address' => $request->input('address'),
            ]);

            return back()->with('success', 'Profile updated successfully.');
        }

        return back()->with('error', 'User not found.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function user_view($id)
    {
        $user = User::with('role')->find($id);
        return view('backend.profile.user-view', compact('user'));
    }
    
}
