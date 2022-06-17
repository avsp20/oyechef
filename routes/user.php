<?php
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\FrontUserController;
use App\Http\Controllers\Frontend\MenuController;
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
// Route::get('/', function () {
// 	return view('frontend.home');
// });
Route::get('/', [FrontUserController::class, 'home'])->name('front.home');
// Route::get('/home', [FrontUserController::class, 'home'])->name('front.home');

Route::group(['namespace' => 'Frontend'], function () {
/* Front Authentication */
	Route::get('load-page-on-scroll/{page}', [FrontUserController::class, 'loadContentOnScroll'])->name('front.load-page-on-scroll');
	Route::post('get-product-ids', [FrontUserController::class, 'getProductIds'])->name('front.get-product-ids');
	Route::post('filter-option', [FrontUserController::class, 'filterOption'])->name('front.filter-option');

	Route::group(['namespace' => 'Auth'], function () {
		Route::get('login', [LoginController::class, 'frontendShowLoginForm'])->name('front.login');
		Route::post('login', [LoginController::class, 'login'])->name('login.submit');
		Route::post('logout', [LoginController::class, 'logout'])->name('front.logout');
		Route::get('register', [RegisterController::class, 'frontendShowRegisterForm'])->name('register');
		Route::post('register/submit', [RegisterController::class, 'register'])->name('register.submit');

		// Password Reset Routes
		Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('front.password.request');
		Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('front.password.email');

		Route::get('menu', [MenuController::class, 'index'])->name('front.menu');
		Route::post('filter-category', [MenuController::class, 'filterMenuCategory'])->name('front.filter-category');
		Route::post('filter-menu-item', [MenuController::class, 'filterMenuItems'])->name('front.filter-menu-item');
	});
	Route::group(['middleware' => 'user'], function () {
		Route::get('my-account', [FrontUserController::class, 'index'])->name('front.my-account');
		Route::post('edit-profile', [FrontUserController::class, 'editProfile'])->name('front.edit-profile');

		// Menu Routes
		// Route::get('create-menu', [MenuController::class, 'createMenu'])->name('front.create-menu');
		Route::get('edit-menu', [MenuController::class, 'createMenu'])->name('front.edit-menu');
		Route::post('save-product', [MenuController::class, 'saveProduct'])->name('front.save-product');
		// Route::get('edit-menu', [MenuController::class, 'editMenu'])->name('front.edit-menu');
	});
});
