<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Frontend\ForgotPasswordRequest;

class ForgotPasswordController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Password Reset Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller is responsible for handling password reset emails and
		    | includes a trait which assists in sending these notifications from
		    | your application to your users. Feel free to explore this trait.
		    |
	*/

	use SendsPasswordResetEmails;

	protected function guard() {
		return Auth::guard('users');
	}
	public function __construct() {
		$this->middleware('guest:users');
	}
	protected function broker() {
		return Password::broker('users');
	}

	public function showLinkRequestForm() {
		return view('frontend.auth.passwords.email');
	}

	public function sendResetLinkEmail(ForgotPasswordRequest $request) {
		$user = User::with('user_meta')->where('email', $request->email)->first();
		if (!$user) {
			return redirect()->back()->with(['content' => 'This email is not registered with us!', 'level' => 'error']);
		}
		if (empty($user->user_meta)) {
			return redirect()->back()->with(['content' => 'Something went wrong, try again later.', 'level' => 'error']);
		}
		if ($user->user_meta->role_id == 1) {
			return redirect()->back()->with(['content' => 'The selected email is invalid.', 'level' => 'success']);
		} else {
			$response = $this->broker()->sendResetLink(
				$request->only('email')
			);
			return redirect()->back()->with(['content' => trans($response), 'level' => 'success']);
		}
	}
}
