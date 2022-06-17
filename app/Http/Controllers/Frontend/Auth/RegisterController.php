<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Mail\QueueRegisterMail;
use App\Models\User;
use App\Models\UserMeta;
use App\Providers\RouteServiceProvider;
use Auth;
use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Http\Requests\Frontend\RegisterRequest;

class RegisterController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Register Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles the registration of new users as well as their
		    | validation and creation. By default this controller uses a trait to
		    | provide this functionality without requiring any additional code.
		    |
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = RouteServiceProvider::HOME;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest');
	}

	public function frontendShowRegisterForm() {
		return view('frontend.auth.register');
	}

	public function register(RegisterRequest $request) {
		DB::beginTransaction();
		try {
			$user           = new User();
			$user->name     = $request->fname;
			$user->email    = $request->email;
			$user->password = Hash::make($request->password);
			$user->save();

			$user_meta          = new UserMeta();
			$user_meta->role_id = 2;
			$user_meta->user_id = $user->id;
			$user_meta->lname   = $request->lname;
			$user_meta->save();
			Auth::guard('users')->login($user);
			$message = "You are registered successfully.";
			$details = [
				'name'  => $request->fname,
				'lname' => $request->lname,
			];
			Mail::to($request->email)->send(new QueueRegisterMail($details));
			DB::commit();
			return redirect()->route('front.home')->with(['content' => $message, 'level' => 'success']);
			// all good
		} catch (\Exception $e) {
			DB::rollback();
			// something went wrong
			return redirect()->route('register')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}
}
