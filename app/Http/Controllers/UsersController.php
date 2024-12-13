<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(){

        $users = User::orderBy('id', 'ASC')->paginate(10);
        // dd($users);
        return view('backend.admin.user.user_index')->with('users', $users);
    }

    public function create(){
         return view('backend.admin.user.user_create');
    }

    public function store(Request $request)
    {
   
        $validatedData = $request->validate([
            'name' => 'string|required|max:30',
            'email' => 'string|required|unique:users',
            'role' => 'required|integer|in:1,2,3,4,5',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = $validatedData['password'];
        $user->roles_id = $validatedData['role'];
        $user->status = $validatedData['status'];
        $status = $user->save();
        // dd($status);
        if ($status) {
            request()->session('success', 'Successfully added user');
        } else {
            request()->session('error', 'Error occurred while adding user');
        }
        return redirect()->route('users.index');
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('backend.admin.user.user_edit')->with('user', $user);
    }

    public function update(Request $request, $id)
    {

        // dd($request);
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'string|required|max:30',
            'email' => 'string|required',
            'role' => 'required|integer|in:1,2,3,4,5',
            'status' => 'required|in:active,inactive',
            // 'password' => 'required|string|min:8|confirmed',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        // $user->password = $validatedData['password'];
        $user->roles_id = $validatedData['role'];
        $user->status = $validatedData['status'];
        
        $status= $user->update();
        if ($status) {
            request()->session('success', 'Successfully updated');
        } else {
            request()->session('error', 'Error occured while updating');
        }
        return redirect()->route('users.index');
    }

    public function delete($id){
        $delete = User::findorFail($id);
        $status = $delete->delete();
        if ($status) {
            request()->session('success', 'User Successfully deleted');
        } else {
            request()->session('error', 'There is an error while deleting users');
        }
        return redirect()->route('users.index');
    }
}
