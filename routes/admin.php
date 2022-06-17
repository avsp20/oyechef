<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CommonController;
use App\Http\Controllers\Backend\MenuCategoryController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('state/{id}', [CommonController::class, 'getStates']);
Route::get('city/{id}', [CommonController::class, 'getCities']);
// Admin Routes
Route::group(['namespace' => 'Backend'], function () {
/* Admin Authentication */
	Route::group(['namespace' => 'Auth'], function () {
		Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
		Route::post('login', [LoginController::class, 'login'])->name('admin.login');
		Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
	});
	// Route::group(['middleware' => 'auth', 'admin'], function () {
	Route::group(['middleware' => 'admin'], function () {
		Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
		Route::get('profile', [AdminController::class, 'editProfile'])->name('admin.profile');
		Route::post('update-profile/{id}', [AdminController::class, 'updateProfile'])->name('admin.update-profile');

		Route::get('users', [UserController::class, 'index'])->name('admin.manage-users');
		Route::get('delete-user/{id}', [UserController::class, 'deleteUser'])->name('admin.delete-user');
		Route::get('user-status/{id}/{status}', [UserController::class, 'userStatus'])->name('admin.user-status');
		Route::get('category', [MenuCategoryController::class, 'index'])->name('admin.manage-category');
		Route::post('add-category', [MenuCategoryController::class, 'saveCategory'])->name('admin.add-category');
		Route::get('edit-category/{id}/edit', [MenuCategoryController::class, 'editCategory'])->name('admin.edit-category');
		Route::post('update-category', [MenuCategoryController::class, 'updateCategory'])->name('admin.update-category');
		Route::get('delete-category/{id}', [MenuCategoryController::class, 'deleteCategory'])->name('admin.delete-category');
	});
});