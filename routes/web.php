<?php
use App\Http\Controllers\Frontend\Auth\FacebookLoginController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\GoogleLoginController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\CommonController;
use App\Http\Controllers\Frontend\FrontUserController;
use App\Http\Controllers\Frontend\MenuController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\NewsFeedController;
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
// Route::get('/', [FrontUserController::class, 'home'])->name('front.home');
Route::get('/',function(){
	return redirect()->route('news-feed.index');
});
Route::get('dms', [FrontUserController::class, 'demoFunction']);
Route::get('curr', [FrontUserController::class, 'currencyFunction']);
Route::get('/home', [FrontUserController::class, 'home'])->name('front.home');

Route::group(['namespace' => 'Frontend'], function () {
/* Front Authentication */

	Route::get('encrypt/{string}', [CommonController::class, 'encrypt'])->name('front.encrypt');
	Route::get('decrypt/{string}', [CommonController::class, 'decrypt'])->name('front.decrypt');

	Route::get('load-page-on-scroll/{page}', [FrontUserController::class, 'loadContentOnScroll'])->name('front.load-page-on-scroll');
	Route::post('get-product-ids', [FrontUserController::class, 'getProductIds'])->name('front.get-product-ids');
	Route::post('filter-option', [FrontUserController::class, 'filterOption'])->name('front.filter-option');

	// Route::get('menu', [MenuController::class, 'index'])->name('front.menu');
	Route::post('filter-category', [MenuController::class, 'filterMenuCategory'])->name('front.filter-category');
	Route::post('filter-menu-item/{id}', [MenuController::class, 'filterMenuItems'])->name('front.filter-menu-item');

	Route::post('mobile-filter-option/{id}', [MenuController::class, 'mobileFilterOption'])->name('front.mobile-filter-option');

	Route::post('change-banner', [FrontUserController::class, 'changeMainBanner'])->name('front.change-banner');

	Route::post('home-search', [FrontUserController::class, 'searchItems'])->name('front.home-search');
	Route::post('give-ratings', [FrontUserController::class, 'giveRatings'])->name('front.give-ratings');

	Route::get('forgot-password', [FrontUserController::class, 'forgetPassword'])->name('front.forgot-password');

	Route::post('find-latlong', [FrontUserController::class, 'searchMenuLatitudeAndLongitude'])->name('front.find-latlong');

	Route::post('filter-map', [FrontUserController::class, 'leftSidebarGoogleMapSearch'])->name('front.filter-map');

	Route::get('terms-and-conditions', [FrontUserController::class, 'termsAndConditions'])->name('front.terms-and-conditions');
	Route::get('privacy-policy', [FrontUserController::class, 'privacyPolicy'])->name('front.privacy-policy');

	Route::get('check-disclaimer', [FrontUserController::class, 'checkDisclaimer'])->name('front.check-disclaimer');

	// Phase-2 Routes
	// News Feed
	Route::resource('news-feed', '\App\Http\Controllers\Frontend\NewsFeedController');
	Route::get('like-feed/{id}', [NewsFeedController::class, 'likeUserFeed'])->name('front.like-feed');
	Route::get('show-comment/{id}', [NewsFeedController::class, 'displayComments'])->name('profile.show-comment');		
	Route::post('add-comment', [NewsFeedController::class, 'addComments'])->name('front.add-comment');
	Route::get('search-followers', [NewsFeedController::class, 'getUserFollowers'])->name('front.search-followers');

	Route::post('delete-comment', [NewsFeedController::class, 'deleteComments'])->name('front.delete-comment');
	Route::post('edit-comment', [NewsFeedController::class, 'editComments'])->name('front.edit-comment');

	Route::get('profile/{id?}', [ProfileController::class, 'index'])->name('profile.index');
	Route::get('load-feeds-on-scroll/{page}', [NewsFeedController::class, 'loadFeedsOnScroll'])->name('front.load-feeds-on-scroll');
	Route::post('get-news-feed-ids', [NewsFeedController::class, 'getNewsFeedIds'])->name('front.get-news-feed-ids');

	Route::get('menu/{id}', [MenuController::class, 'index'])->name('front.menu');

	Route::post('get-user-news-feed-ids/{uid}', [ProfileController::class, 'getUserNewsFeedIds'])->name('front.get-user-news-feed-ids');

	Route::group(['namespace' => 'Auth'], function () {
		Route::get('login', [LoginController::class, 'frontendShowLoginForm'])->name('front.login');
		Route::post('login', [LoginController::class, 'login'])->name('login.submit');
		Route::post('logout', [LoginController::class, 'logout'])->name('front.logout');
		Route::get('register', [RegisterController::class, 'frontendShowRegisterForm'])->name('register');
		Route::post('register/submit', [RegisterController::class, 'register'])->name('register.submit');

		// Password Reset Routes
		Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('front.password.request');
		Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

		Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
		Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

		Route::get('auth/google', 'GoogleLoginController@redirectToGoogle');
		Route::get('auth/google/callback', 'GoogleLoginController@handleGoogleCallback');

		Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle']);
		Route::get('auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);

		Route::get('auth/facebook', [FacebookLoginController::class, 'redirectToFacebook']);
		Route::get('auth/facebook/callback', [FacebookLoginController::class, 'handleFacebookCallback']);
	});
	Route::group(['middleware' => 'Dashboard'], function () {
		Route::get('my-account', [FrontUserController::class, 'index'])->name('front.my-account');
		Route::post('edit-profile', [FrontUserController::class, 'editProfile'])->name('front.edit-profile');

		// Menu Routes
		// Route::get('create-menu', [MenuController::class, 'createMenu'])->name('front.create-menu');
		Route::get('edit-menu', [MenuController::class, 'createMenu'])->name('front.edit-menu');
		// Route::get('menu/{id}', [MenuController::class, 'index'])->name('front.menu');

		Route::get('edit-status/{status}', [MenuController::class, 'editUserStoreStatus'])->name('front.edit-status');
		Route::post('edit-delivery-status', [MenuController::class, 'userStoreUpdateDeliveryStatus'])->name('front.edit-delivery-status');
		Route::post('save-product', [MenuController::class, 'saveProduct'])->name('front.save-product');
		Route::post('update-product/{id}', [MenuController::class, 'updateProduct'])->name('front.update-product');
		Route::post('delete-item', [MenuController::class, 'deleteUserMenuItem'])->name('front.delete-item');
		Route::get('add-to-favorites/{user_id}', [MenuController::class, 'addToFavorites'])->name('front.add-to-favorites');
		Route::post('remove-favorites', [FrontUserController::class, 'removeFavorites'])->name('front.remove-favorites');

		Route::post('sort-order', [MenuController::class, 'sortOrder'])->name('front.sort-order');

		// Route::resource('profile', '\App\Http\Controllers\Frontend\ProfileController');
		// Route::get('profile/{id?}', [ProfileController::class, 'index'])->name('profile.index');

		Route::get('follow-feed/{id}', [NewsFeedController::class, 'followUserFeed'])->name('front.follow-feed');
		Route::post('remove-following', [NewsFeedController::class, 'removeFollowing'])->name('front.remove-following');

		// Route::get('edit-menu', [MenuController::class, 'editMenu'])->name('front.edit-menu');
	});
});
