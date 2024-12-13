<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loggedIn');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('registerIn');

Route::get('/admin/dashboard', [DashboardController::class, 'showAdminDashboard'])->name('admin.dashboard');
Route::get('/user/dashboard', [DashboardController::class, 'showUserDashboard'])->name('user.dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/admin/users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('admin/users/store', [UsersController::class, 'store'])->name('users.store'); 
Route::get('/admin/users/{user}', [UsersController::class, 'show'])->name('users.show'); 
Route::get('/admin/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::put('/admin/users/update/{id}', [UsersController::class, 'update'])->name('users.update');
Route::post('/admin/users/delete/{id}', [UsersController::class, 'delete'])->name('users.destroy');



// Route::get('users', [UserController::class, 'index'])->name('users.index'); // Show all users
// Route::get('users/create', [UserController::class, 'create'])->name('users.create'); // Show create user form
// Route::get('users/{user}', [UserController::class, 'show'])->name('users.show'); // Show a single user
// Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Show edit user form
// Route::put('users/{user}', [UserController::class, 'update'])->name('users.update'); // Update user data
// Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete a user