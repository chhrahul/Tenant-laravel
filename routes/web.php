<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('home', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');

Route::prefix('auth')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.user');
    Route::post('register', [RegisterController::class, 'register'])->name('register.newuser');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->prefix('data-entry')->group(function () {
    Route::get('/', [DataEntryController::class, 'showForm'])->name('data.entry')->middleware('checkRole:user');
    Route::post('/', [DataEntryController::class, 'storeData'])->name('store.entry');
    Route::get('/data', [DataEntryController::class, 'getData'])->name('data-entry.data')->middleware('checkRole:admin');
    // Route::get('/user-management', [userManagement::class, 'index'])->name('user.management')->middleware('checkRole:admin');
});


Route::middleware(['auth','checkRole:admin'])->prefix('user-management')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('user.management');
    Route::get('/get-user-data', [UserManagementController::class, 'getUserData'])->name('user.management.data');
    Route::get('/user/{id}', [UserManagementController::class, 'getUserDataById'])->name("get-user-data-by-id");
    Route::put('/user/{id}', [UserManagementController::class, 'updateUserData'])->name('update-user-data');
    Route::delete('/user/{id}', [UserManagementController::class, 'deleteUser'])->name('delete-user');
});


Route::get('/test', function () {
   return "<h1>This is a test page</h1>";
});

Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('report', [DataEntryController::class, 'showReport'])->name('showReport');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');




