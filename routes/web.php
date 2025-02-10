<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\SellerController;
use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect('/login-view');
});

Route::get('/login-view', [AuthController::class, 'showLoginForm'])->name('login-view');
Route::post('/login', [AuthController::class, 'login'])->name('loggedIn');

// general register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('registerIn');

// seller register
Route::get('/seller/register/{token}', [AuthController::class, 'showSellerRegistrationForm'])->name('seller.register');
Route::post('/seller/register', [AuthController::class, 'registerSeller'])->name('seller.register.submit');

//forget password
Route::get('/forgot-password', [AuthController::class, 'forgetPassword'])->name('forgot');
Route::post('/reset-password', [AuthController::class, 'requestReset'])->name('reset');



Route::middleware(['customAuth'])->group(function () {

    Route::get('/test-gcs', [DashboardController::class, 'uploadFile']);
    Route::post('/submit-gcs', [DashboardController::class, 'submit'])->name('submit-file');
    

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
    Route::post('/deals/delete/{id}', [DealController::class, 'delete'])->name('deals.destroy');
    Route::get('/deals/{deal}/invite-contact', [DealController::class, 'showInviteContactForm'])->name('deals.inviteView');
    Route::post('/deals/{deal}/invite-contact', [DealController::class, 'sendInvite'])->name('deals.sendInvite');
    Route::get('/deals/{deal}/view-deal', [DealController::class, 'viewDeal'])->name('deals.view');
    Route::get('/deals/files/{id}', [FileController::class, 'viewFolderFiles'])->name('deal.file.list');
    Route::get('/files/view/{id}', [FileController::class, 'viewFile']);
    Route::get('/deals/{id}/edit', [DealController::class, 'edit'])->name('deals.edit');
    Route::post('/deals/update', [DealController::class, 'update'])->name('deals.update');

    // Route::get('/test-integration', [DealController::class, 'testIntegration']);

    // folder routes 
    Route::post('/deals/folder/update', [FileController::class, 'store'])->name('folder.upload');
    
    
    // seller routes

    Route::get('/seller/deals', [SellerController::class, 'index'])->name('seller.index');
    Route::get('/seller/deals/{deal}/view-deal', [SellerController::class, 'viewDeal'])->name('seller.deals.view');
    Route::get('/seller/deals/files/{id}', [SellerController::class, 'viewFolderFiles'])->name('seller.deal.file.list');

});
