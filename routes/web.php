<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DealController;
use App\Http\Middleware\CustomAuthMiddleware;

Route::get('/', function () {
    return redirect('/login-view');
});

Route::get('/login-view', [AuthController::class, 'showLoginForm'])->name('login-view');
Route::post('/login', [AuthController::class, 'login'])->name('loggedIn');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('registerIn');


Route::middleware(['customAuth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin/dashboard', [DashboardController::class, 'showAdminDashboard'])->name('admin.dashboard');
    Route::get('/user/dashboard', [DashboardController::class, 'showUserDashboard'])->name('user.dashboard');

    // ADmin User module routes
    Route::get('/admin/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('admin/users/store', [UsersController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/admin/users/delete/{id}', [UsersController::class, 'delete'])->name('users.destroy');

    // Deals module routes

    Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
    Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create'); // Show create user form
    Route::post('/deals/store', [DealController::class, 'store'])->name('deals.store');
});
