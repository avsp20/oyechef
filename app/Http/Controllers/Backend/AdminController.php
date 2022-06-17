<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\MenuCategory;
use App\Models\MenuProduct;
use App\Models\State;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ProfileRequest;

class AdminController extends Controller {
	public function index() {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		$users = User::with(['user_meta' => function ($query) {
			$query->where('role_id', 2);
		},
		])->where('deleted_at', null)->count();
		$categories = MenuCategory::count();
		$menus = User::with('user_meta.products')->whereHas('user_meta', function ($query) {
			$query->where('role_id', 2);
		})->has('user_meta.products')->count();
		$dishes = MenuProduct::count();
		return view('backend.dashboard', compact('users', 'categories', 'menus', 'dishes'));
	}

	public function editProfile(Request $request) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		$user = User::find(Auth::guard('admin')->user()->id);
		if ($user) {
			$user_id = Auth::guard('admin')->user()->id;
			$countries = Country::get();
			$states = State::where('country_id', Auth::guard('admin')->user()->country)->get();
			$cities = City::where('state_id', Auth::guard('admin')->user()->state)->get();
			$user = User::where('id', $user_id)->first();
			return view('backend.profile', compact('user', 'countries', 'states', 'cities'));
		} else {
			return redirect()->route('admin.dashboard');
		}
	}

	public function updateProfile(ProfileRequest $request, $id) {
		if (Auth::guard('admin')->user()->role_id != 1) {
			if (Auth::guard('admin')->user()->id != $id) {
				return redirect()->back();
			}
		}
		$user = User::find($id);
		$imageName = $user->user_image;
		if ($request->file('user_image')) {
			$imageName = time() . '.' . $request->user_image->extension();
			$request->user_image->move(public_path('/backend/images/user/'), $imageName);
		}
		// $user->email = $request->email;
		$user->name = $request->name;
		$user->lname = $request->lname;
		$user->phone = $request->phone;
		$user->address = $request->address;
		$user->zipcode = $request->zipcode;
		// $user->password = Hash::make($request->password);
		$user->country = $request->country;
		$user->state = $request->state;
		$user->city = $request->city;
		$user->user_image = $imageName;

		if ($user->save()) {
			return redirect()->back()->with('status', 'Profile Updated Successfully');
		} else {
			return redirect()->back()->with('error', 'Something went wrong, try again later.');
		}
	}
}
