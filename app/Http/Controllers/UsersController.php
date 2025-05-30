<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserAccountActivatedMail;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function index()
    {

        $users = User::orderBy('id', 'ASC')->get();
        return view('backend.admin.user.user_index')->with('users', $users);
    }

    public function create()
    {
        return view('backend.admin.user.user_create');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|unique:users',
            'role' => 'required|integer|in:1,2,3,4,5',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = $validatedData['password'];
        $user->roles_id = $validatedData['role'];
        $user->status = $validatedData['status'];
        $user->ip_address = $request->ip();
        $status = $user->save();

        if ($status) {
            return redirect()->route('users.index')->with('success', 'User Created successfully!');
        } else {
            return redirect()->route('users.index')->with('success', 'Sorry, something went wrong.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.admin.user.user_edit')->with('user', $user);
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'string|required|max:30',
            'email' => 'string|required',
            'role' => 'required|integer|in:1,2,3,4,5',
            'status' => 'required|in:active,inactive',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->roles_id = $validatedData['role'];
        $user->status = $validatedData['status'];
        $user->ip_address = $request->ip();

        $save = $user->update();
        if ($save) {
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        } else {
            request()->session('error', 'Error occured while updating');
        }
        return redirect()->route('users.index')->with('error', 'Sorry, something wrong.');
    }

    public function delete($id)
    {
        $delete = User::findorFail($id);
        $status = $delete->delete();
        if ($status) {
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->route('users.index')->with('error', 'Sorry, something went wrong!');
        }
        return redirect()->route('users.index');
    }
}
