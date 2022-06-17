<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\LoginRequest;

class LoginController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Login Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles authenticating users for the application and
		    | redirecting them to your home screen. The controller uses a trait
		    | to conveniently provide its functionality to your applications.
		    |
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	// protected $redirectTo = RouteServiceProvider::HOME;
	protected $redirectTo = '/';
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		// $this->middleware('guest')->except('logout');
		$this->middleware('guest:users')->except('logout');
	}

	protected function guard() {
		return Auth::guard('users');
	}

	public function frontendShowLoginForm() {
		return view('frontend.auth.login');
	}

	public function login(LoginRequest $request) {
		$remember_me = $request->has('rememberme') ? true : false;
		if (Auth::guard('users')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
			$user = User::where('email', $request->email)->first();
			return redirect()->route('news-feed.index')->with(['content' => 'Welcome ' . ucfirst($user->name) . ' to Oyechef', 'level' => 'success']);
		} else {
			return redirect()->route('front.login')->with(['content' => 'Invalid credentials.', 'level' => 'error']);
		}
	}

	public function logout() {
		Auth::guard('users')->logout();
		return redirect()->route('front.login');
	}
}
