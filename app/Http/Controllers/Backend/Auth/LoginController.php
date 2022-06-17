<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\LoginRequest;

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
	protected $redirectTo = 'admin/dashboard';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		// $this->middleware('admin')->except('logout');
		$this->middleware('guest:admin', ['except' => ['logout']]);
	}

	protected function guard() {
		return Auth::guard('admin');
	}

	public function showLoginForm() {
		return view('backend.auth.login');
	}

	public function login(LoginRequest $request) {
		// $this->validate($request, [
		// 	'email' => 'required',
		// 	'password' => 'required',
		// ]);
		if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
			$role = Auth::guard('admin')->user()->role_id;
			if ($role == 1) {
				$user = User::where('email', $request->email)->first();
				Auth::guard('admin')->login($user);
				return redirect()->route('admin.dashboard');
			} else {
				Auth::guard('admin')->logout();
				return redirect()->back()->with('error', 'Invalid Login');
			}
		} else {
			return redirect()->route('login');
		}
	}

	public function logout() {
		Auth::guard('admin')->logout();
		return redirect()->route('login');
	}
}
