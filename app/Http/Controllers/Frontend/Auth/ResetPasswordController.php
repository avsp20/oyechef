<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Requests\Frontend\ResetPasswordRequest;

class ResetPasswordController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Password Reset Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller is responsible for handling password reset requests
		    | and uses a simple trait to include this behavior. You're free to
		    | explore this trait and override any methods you wish to tweak.
		    |
	*/

	use ResetsPasswords;

	/**
	 * Where to redirect users after resetting their password.
	 *
	 * @var string
	 */
	// protected $redirectTo = RouteServiceProvider::HOME;
	protected $guards = ['users'];

	public function __construct() {
		$this->middleware('guest')->except('logout');

	}

	public function redirectTo() {
		foreach ($this->guards as $guard) {
			if (Auth::guard($guard)->check()) {
				if ($guard == 'users') {
					return redirect()->route('front.home')->with(['content' => 'Password reset successfully!', 'level' => 'success']);
				}

			}
		}
		return redirect('/');
	}

	public function showResetForm(Request $request, $token = null) {
		return view('frontend.auth.passwords.reset')->with(
			['token' => $token, 'email' => $request->email]
		);
	}

	public function reset(ResetPasswordRequest $request) {
		$user = User::where('email', $request->email)->first();
		if ($user) {
			$user->password = Hash::make($request->password);
			$user->setRememberToken(Str::random(60));
			$user->save();
			Auth::guard('users')->login($user);
			return redirect()->route('front.home')->with(['content' => 'Password reset successfully!', 'level' => 'success']);
		} else {
			return redirect()->back()->withErrors(['email' => 'Email is not registered with us.'])->withInput();
		}
	}
}
