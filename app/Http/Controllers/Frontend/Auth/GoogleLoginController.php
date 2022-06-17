<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Socialite;

class GoogleLoginController extends Controller {
	use AuthenticatesUsers;

	protected $redirectTo = '/';

	public function __construct() {
		$this->middleware('guest:users')->except('logout');
	}

	public function redirectToGoogle() {
		try {
			return Socialite::driver('google')->redirect();
		} catch (Exception $e) {
			return redirect('auth/google');
		}
	}

	public function handleGoogleCallback() {
		try {
			$user_social = Socialite::driver('google')->stateless()->user();
			$google_id = $user_social->id;
			$find_user = User::whereHas('user_meta', function ($query) use ($google_id) {
				$query->where('google_key', $google_id);
			})->first();
			if ($find_user) {
				$user = $find_user;
			} else {
				$exist_user = User::where('email', $user_social->email)->first();
				if (empty($exist_user)) {
					$user = User::create([
						'name' => $user_social->name,
						'email' => $user_social->email,
						'password' => Hash::make($user_social->name),
					]);
					$user_meta = UserMeta::create([
						'user_id' => $user->id,
						'role_id' => 2,
						'google_key' => $user_social->id,
					]);
				} else {
					$exist_user = UserMeta::where('user_id', $exist_user->id)->first();
					$exist_user->google_key = $user_social->id;
					$exist_user->save();
					$user = $exist_user;
				}
			}
			// dd($user);
			Auth::guard('users')->login($user);
			return redirect('/');

		} catch (Exception $e) {
			return redirect('auth/google');
		}

	}
}
