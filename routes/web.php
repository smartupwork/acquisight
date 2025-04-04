<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DealRequestController;
use App\Http\Controllers\DealSettingController;

// Route::get('/', function () {
//     return redirect('/login-view');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login-view', [AuthController::class, 'showLoginForm'])->name('login-view');
Route::post('/login', [AuthController::class, 'login'])->name('loggedIn');

// general register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/buyerregistration', [AuthController::class, 'showBuyerForm'])->name('buyerregistration.register');
Route::post('/buyerregistration/save', [AuthController::class, 'save_buyer'])->name('buyer.save');
Route::post('/register', [AuthController::class, 'register'])->name('registerIn');

// seller register
Route::get('/seller/register/{token}', [AuthController::class, 'showSellerRegistrationForm'])->name('seller.register');
Route::post('/seller/register', [AuthController::class, 'registerSeller'])->name('seller.register.submit');

Route::get('/copy/link/{deal_id}', [AuthController::class, 'showCopyForm'])->name('copy.register');
Route::post('/store/link', [AuthController::class, 'registerBuyer'])->name('buyer.register.submit');

//forget password
Route::get('/forgot-password', [AuthController::class, 'forgetPassword'])->name('forgot');
Route::post('/reset-password', [AuthController::class, 'requestReset'])->name('reset');



Route::middleware(['customAuth'])->group(function () {

    Route::get('/test-gcs', [DashboardController::class, 'uploadFile']);
    Route::post('/submit-gcs', [DashboardController::class, 'submit'])->name('submit-file');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin/profile/{id}', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/user/profile/{id}', [ProfileController::class, 'user_view'])->name('user.profile.view');

    Route::post('profile-save', [ProfileController::class, 'store'])->name('profile.submit');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    Route::get('/admin/dashboard', [DashboardController::class, 'showAdminDashboard'])->name('admin.dashboard');
    Route::get('/user/dashboard', [DashboardController::class, 'showUserDashboard'])->name('user.dashboard');


    Route::get('/admin/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('admin/users/store', [UsersController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/admin/users/delete/{id}', [UsersController::class, 'delete'])->name('users.destroy');



    Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
    Route::get('/deals/detail/{deal}', [DealController::class, 'deal_details'])->name('deals.detail');
    Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create'); // Show create user form
    Route::post('/deals/store', [DealController::class, 'store'])->name('deals.store');
    Route::post('/deals/delete/{id}', [DealController::class, 'delete'])->name('deals.destroy');
    Route::get('/deals/{deal}/invite-contact', [DealController::class, 'showInviteContactForm'])->name('deals.inviteView');
    Route::post('/deals/{deal}/invite-contact', [DealController::class, 'sendInvite'])->name('deals.sendInvite');
    Route::get('/deals/{deal}/view-deal', [DealController::class, 'viewDeal'])->name('deals.view');
    Route::get('/deals/files/{id}', [FileController::class, 'viewFolderFiles'])->name('deal.file.list');
    Route::post('/file/delete', [FileController::class, 'deleteFile'])->name('file.delete');
    Route::post('/delete-folder', [FolderController::class, 'deleteFolder'])->name('folder.delete');
    Route::get('/files/view/{id}', [FileController::class, 'viewFile']);
    Route::get('/deals/{id}/edit', [DealController::class, 'edit'])->name('deals.edit');
    Route::post('/deals/update', [DealController::class, 'update'])->name('deals.update');


    // deal setting routes for admin

    Route::get('/admin/deals/settings', [DealSettingController::class, 'index'])->name('deals.settings');
    Route::put('/settings/list-type/{deal}', [DealSettingController::class, 'update_list_type'])->name('settings.list.type');
    // folder routes 

    Route::post('/deals/folder/update', [FileController::class, 'store'])->name('folder.upload');
    Route::post('/deals/folder/new', [FolderController::class, 'new_folder_store'])->name('new.folder.store');

    //admin log routes
    Route::post('/log-file-view', [FileController::class, 'logFileView'])->name('log.file.view');
    Route::get('/view-file-log', [DashboardController::class, 'view_logs'])->name('view.files');

    // admin requests logs 

    Route::get('/admin/deal-requests', [DealRequestController::class, 'getAdminDealRequests'])->name('admin.request');


    // seller routes

    Route::get('/seller/deals', [SellerController::class, 'index'])->name('seller.index');
    Route::get('/seller/deals/{id}', [SellerController::class, 'deals_detail'])->name('seller.detail.show');
    Route::get('/seller/deals/{deal}/view-deal', [SellerController::class, 'viewDeal'])->name('seller.deals.view');
    Route::get('/seller/deals/files/{id}', [SellerController::class, 'viewFolderFiles'])->name('seller.deal.file.list');

    //buyer routes
    Route::get('/buyer/deals/{id}', [BuyerController::class, 'deals_detail'])->name('buyer.detail.show');
    Route::post('/buyer/deals/{id}/request', [DealRequestController::class, 'store'])->name('buyer.deals.request');
    Route::get('/buyer/deals/{deal}/view-deal', [BuyerController::class, 'viewDeal'])->name('buyer.deals.view');
    Route::get('/buyer/deals/files/{id}', [BuyerController::class, 'viewFolderFiles'])->name('buyer.deal.file.list');


    //broker routes

    Route::get('/broker/index', [BrokerController::class, 'index'])->name('broker.index');
    Route::get('/brokers/deal-requests', [DealRequestController::class, 'getBrokerDealRequests'])->name('broker.request');
    Route::put('/deal-requests/{id}/update-status', [DealRequestController::class, 'updateStatus'])->name('deal-requests.update-status');
    Route::get('/broker/deals/{id}', [BrokerController::class, 'deals_detail'])->name('broker.detail.show');
    Route::get('/broker/deals/{deal}/view-deal', [BrokerController::class, 'viewDeal'])->name('broker.deals.view');
    Route::get('/broker/deals/files/{id}', [BrokerController::class, 'viewFolderFiles'])->name('broker.deal.file.list');
});
