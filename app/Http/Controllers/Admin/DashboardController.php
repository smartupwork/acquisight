<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showAdminDashboard()
    {
        return view('backend.admin.dashboard');
    }
    public function showUserDashboard()
    {  
        $users = Auth::user();
        return view('backend.user.dashboard', compact('users'));
    }
}
