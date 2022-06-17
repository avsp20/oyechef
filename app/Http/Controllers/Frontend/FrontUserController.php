<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\MenuController;
use App\Models\MenuProduct;
use App\Models\User;
use App\Models\UserMeta;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\FrontUserRequest;

class FrontUserController extends Controller {
	public function demoFunction() {
		return view('frontend.demo');
	}

	public function cc($amount) {
		global $geoPlugin_array;
		$ip_addr = '223.252.19.130';
		// $ip_addr = $_SERVER['REMOTE_ADDR'];
		$geoPlugin_array = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip_addr));
		if (isset($geoPlugin_array['geoplugin_currencyCode']) && $geoPlugin_array['geoplugin_currencyCode'] != 'USD') {
			return $geoPlugin_array['geoplugin_currencySymbol_UTF8'] . round(($amount * $geoPlugin_array['geoplugin_currencyConverter']), 2);
		} else {
			return $geoPlugin_array['geoplugin_currencySymbol_UTF8'] . round($amount, 2);
		}
		return false;
	}
	public function currencyFunction() {
		return view('frontend.curr');
	}

	public function home() {
		$locations = $this->getNearbyPlaces();
		$lat_long  = null;
		if (Auth::guard('users')->check()) {
			$lat_long = UserMeta::select('latitude', 'longitude', 'address')->where('user_id', Auth::guard('users')->user()->id)->first();
		}
		$products = MenuProduct::with(['user.user_meta' => function ($query) {
			$query->where('is_store_open_or_close', 1);
		}])->inRandomOrder()->take(12)->get();
		return view('frontend.home', compact('products', 'lat_long', 'locations'));
	}

	public function loadContentOnScroll(Request $request, $page) {
		$ids      = array();
		$lat_long = array();
		if (Auth::guard('users')->check()) {
			$lat_long = UserMeta::select('latitude', 'longitude', 'address')->where('user_id', Auth::guard('users')->user()->id)->first();
			if (!empty($lat_long)) {
				if ($lat_long->latitude == null && $lat_long->longitude == null) {
					$lat_long = $this->getLatLongFromIP();
				}
			}
		} else {
			$lat_long = $this->getLatLongFromIP();
		}
		$products = MenuProduct::with('user.user_meta')->select("products.*", "user_meta.*", "user_meta.user_id", "products.id as product_id"
			, DB::raw("6371 * acos(cos(radians(" . $lat_long['latitude'] . "))
			                * cos(radians(user_meta.latitude))
			                * cos(radians(user_meta.longitude) - radians(" . $lat_long['longitude'] . "))
			                + sin(radians(" . $lat_long['latitude'] . "))
			                * sin(radians(user_meta.latitude))) AS distance"))
			->join('user_meta', 'products.user_id', '=', 'user_meta.user_id')
			->where('user_meta.is_store_open_or_close', 1)
			->having("distance", "<", 2000)
			->has('user.user_meta')->inRandomOrder()->take(12)->get();
		if (count($products) > 0) {
			foreach ($products as $product) {
				$ids[] = $product->product_id;
			}
		}
		if ($page > 1) {
			$products = MenuProduct::with('user.user_meta')->select("products.*", "user_meta.*", "user_meta.user_id", "products.id as product_id"
				, DB::raw("6371 * acos(cos(radians(" . $lat_long['latitude'] . "))
			                * cos(radians(user_meta.latitude))
			                * cos(radians(user_meta.longitude) - radians(" . $lat_long['longitude'] . "))
			                + sin(radians(" . $lat_long['latitude'] . "))
			                * sin(radians(user_meta.latitude))) AS distance"))
				->join('user_meta', 'products.user_id', '=', 'user_meta.user_id')
				->whereNotIn('products.id', $ids)
				->where('user_meta.is_store_open_or_close', 1)
				->having("distance", "<", 2000)
				->has('user.user_meta')->inRandomOrder()->take(12)->get();
		}
		if ($request->ajax()) {
			return view('frontend.home-section', compact('products'));
		}
	}

	public function getProductIds(Request $request) {
		if ($request->ids == null) {
			return response()->json(array('data' => null, 'responseMessage' => 'No data found.'), 200);
		}
		// $ids = implode(",", $request->ids);
		/*->whereRaw('id NOT IN(' . $ids . ')')*/
		$products = MenuProduct::whereHas('user.user_meta', function ($query) {
			$query->where('is_store_open_or_close', 1);
		})->whereNotIn('id', $request->ids)->has('user.user_meta')->inRandomOrder()->take(12)->get();
		if ($request->search_data != null) {
			$search_data = $request->search_data;
			$products    = MenuProduct::whereHas('user.user_meta', function ($query) use ($search_data) {
				$query->where('name', 'like', '%' . $search_data . '%');
				$query->orWhere('lname', 'like', '%' . $search_data . '%');
				$query->where('is_store_open_or_close', 1);
			})->orWhere('product_name', 'like', '%' . $search_data . '%')->whereNotIn('id', $request->ids)->inRandomOrder()->take(12)->get();
		}
		if ($request->check_ids != null) {
			$query     = MenuProduct::query();
			$check_ids = $request->check_ids;
			if (array_intersect([1, 2, 3, 4, 5], $check_ids)) {
				$query->whereIn('category_id', $check_ids);
			}
			if (in_array(6, $check_ids)) {
				$query->where("price_type", 0);
			}
			if (in_array(7, $check_ids)) {
				$pick_up = 0;
				$query->whereHas('user.user_meta', function ($q) use ($pick_up) {
					$q->where('store_delivery_option', 'like', '%' . $pick_up . '%');
					$q->where('is_store_open_or_close', 1);
				});
			}
			if (in_array(8, $check_ids)) {
				$delivery = 1;
				$query->whereHas('user.user_meta', function ($q) use ($delivery) {
					$q->where('store_delivery_option', 'like', '%' . $delivery . '%');
					$q->where('is_store_open_or_close', 1);
				});
			}
			$products = $query->whereNotIn('id', $request->ids)->whereHas('user.user_meta', function ($query) {
				$query->where('is_store_open_or_close', 1);
			})->inRandomOrder()->take(12)->get();
		}
		$returnHTML = view('frontend.home-section', compact('products'))->render();
		return response()->json(array('html' => $returnHTML));
	}

	public function filterOption(Request $request) {
		if ($request->ids == null) {
			$products = MenuProduct::with(['user.user_meta' => function ($query) {
				$query->where('is_store_open_or_close', 1);
			}])->inRandomOrder()->take(12)->get();
		} else {
			$products = $this->commonCheckboxSidebarFunction($request->ids);
		}
		$returnHTML = view('frontend.home-section', compact('products'))->render();
		return response()->json(array('html' => $returnHTML));
	}

	public function index(Request $request) {
		$user_data = new MenuController;
		if (Auth::guard('users')->check()) {
			$user         = User::where('id', Auth::guard('users')->user()->id)->first();
			$user_ratings = $user_data->userMenuRatings(Auth::guard('users')->user()->id);
			if ($user) {
				$user_meta = UserMeta::where('user_id', Auth::guard('users')->user()->id)->first();
				if ($user_meta) {
					$user['last_name']          = ($user_meta->lname != null) ? $user_meta->lname : '';
					$user['mobile']             = ($user_meta->phone != null) ? $user_meta->phone : '';
					$user['street_address']     = ($user_meta->address != null) ? $user_meta->address : '';
					$user['image']              = ($user_meta->user_image != null) ? $user_meta->user_image : '';
					$user['open_close']         = isset($user_meta->is_store_open_or_close) ? $user_meta->is_store_open_or_close : 1;
					$user['delivery_option']    = isset($user_meta->store_delivery_option) ? $user_meta->store_delivery_option : 1;
					$user['username']           = $user_meta->username;
					$user['is_username_active'] = $user_meta->is_username_active;
				}
				return view('frontend.my-account', compact('user', 'user_ratings'));
			} else {
				return redirect()->route('front.login');
			}
		} else {
			return redirect()->route('front.login');
		}
	}

	public function editProfile(FrontUserRequest $request) {
		DB::beginTransaction();
		try {
			$user        = User::find(Auth::guard('users')->user()->id);
			$user->name  = $request->fname;
			$user->email = $request->email;
			$user->save();

			$user_meta                     = UserMeta::where('user_id', Auth::guard('users')->user()->id)->first();
			$user_meta->lname              = $request->lname;
			$user_meta->username           = $request->username;
			$user_meta->is_username_active = isset($request->is_username_active) ? 1 : 0;
			$user_meta->phone              = $request->phone;
			$user_meta->address            = $request->address;
			if ($request->file('profileimage')) {
				$imageName = time() . '.' . $request->profileimage->extension();
				$request->profileimage->move(public_path('/frontend/img/user_profiles/'), $imageName);
				$user_meta->user_image = $imageName;
			}
			if (!empty($request->address)) {
				$lat_long             = $this->googleLatLongFunction($request->address);
				$lat_long             = explode(",", $lat_long);
				$user_meta->latitude  = $lat_long[0];
				$user_meta->longitude = $lat_long[1];
			}
			$user_meta->save();
			DB::commit();
			$message = "Your profile updated successfully.";
			return redirect()->route('front.my-account')->with(['content' => $message, 'level' => 'success']);
			// all good
		} catch (\Exception $e) {
			DB::rollback();
			// something went wrong
			return redirect()->route('front.my-account')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function removeFavorites(Request $request) {
		$response = response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, try again later.'), 400);
		if (!$request->id) {
			return $response;
		}
		if (!Auth::guard('users')->check()) {
			return response()->json(array('data' => 'error', 'responseMessage' => 'You need to first login.'), 400);
		}
		$get_user = UserMeta::where('user_id', Auth::guard('users')->user()->id)->first();
		if (!$get_user) {
			return $response;
		}
		$user_data = new CommonController;
		$user_id   = $user_data->decrypt($request->id);
		if ($get_user->user_favorites_id != null) {
			$fav_user = json_decode($get_user->user_favorites_id);
			if (in_array($user_id, $fav_user)) {
				$pos = array_search($user_id, $fav_user);
				unset($fav_user[$pos]);
				UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
					'user_favorites_id' => array_values($fav_user),
				]);
				return response()->json(array('data' => 'success', 'responseMessage' => 'Favorite removed successfully'), 200);
			} else {
				return response()->json(array('data' => 'success', 'responseMessage' => 'Favorite chef not found.'), 200);
			}
		} else {
			return $response;
		}
	}

	public function changeMainBanner(Request $request) {
		if ($request->banner_image != null) {
			$imageName = time() . '.' . $request->banner_image->extension();
			$request->banner_image->move(public_path('/frontend/img/user_banners/'), $imageName);

			UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
				'banner_image' => $imageName,
			]);
			return response()->json(array('data' => 'success', 'responseMessage' => 'Your banner updated successfully.'), 200);
		} else {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, try again later.'), 400);
		}
	}

	public function searchItems(Request $request) {
		$products = MenuProduct::with('user.user_meta')->inRandomOrder()->take(12)->get();
		if ($request->search_data != null) {
			$search_data = $request->search_data;
			$products    = MenuProduct::whereHas('user.user_meta', function ($query) use ($search_data) {
				$query->where('is_store_open_or_close', 1);
				$query->where('name', 'like', '%' . $search_data . '%');
				$query->orWhere('lname', 'like', '%' . $search_data . '%');
			})->orWhere('product_name', 'like', '%' . $search_data . '%')->inRandomOrder()->take(12)->get();
		}
		$returnHTML = view('frontend.home-section', compact('products'))->render();
		return response()->json(array('html' => $returnHTML));
	}

	public function giveRatings(Request $request) {
		$user_data   = new CommonController;
		$ratings_arr = array();
		if (!Auth::guard('users')->check()) {
			return response()->json(array('data' => 'error', 'responseMessage' => 'You need to first login.'), 400);
		}
		$user_id       = $user_data->decrypt($request->user_id);
		$login_user_id = Auth::guard('users')->user()->id;
		$get_ratings   = UserMeta::select('user_ratings_id')->where('user_id', $user_id)->first();
		if ($get_ratings) {
			if ($get_ratings->user_ratings_id == null) {
				$ratings_arr = [
					$login_user_id => $request->rating,
				];
				$ratings_arr = serialize($ratings_arr);
			} else {
				$unserialize = unserialize($get_ratings->user_ratings_id);
				if (count($unserialize) > 0) {
					foreach ($unserialize as $key => $value) {
						if ($key == $login_user_id) {
							$unserialize[$key] = $request->rating;
							$ratings_arr       = serialize($unserialize);
						} else {
							$unserialize[$login_user_id] = $request->rating;
							$ratings_arr                 = serialize($unserialize);
						}
					}
				}
			}
			UserMeta::where('user_id', $user_id)->update([
				'user_ratings_id' => $ratings_arr,
			]);
			return response()->json(array('data' => 'success', 'responseMessage' => 'Your ratings saved successfully.'), 200);
		} else {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, try again later.'), 400);
		}
	}

	public function googleLatLongFunction($address) {
		$address = str_replace(" ", "+", $address);
		$json    = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=AIzaSyBnCj3IWhjiuan4RaQdzjOWQWvDEt4pKxk&sensor=false");
		$json    = json_decode($json);
		$lat     = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$long    = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		return $lat . ',' . $long;
	}

	public function getNearbyPlaces() {
		$location_arr = array();
		$menu_data    = new MenuController;
		$common_data  = new CommonController;
		$users        = User::whereHas('user_meta', function ($query) {
			$query->where('is_store_open_or_close', 1);
			$query->where('latitude', '!=', null);
			$query->where('longitude', '!=', null);
		})->has('user_meta')->get();
		if (count($users) > 0) {
			foreach ($users as $value) {
				if ($value->user_meta != null) {
					if (($value->user_meta->latitude != null) && ($value->user_meta->longitude != null)) {
						$location_arr[] = array(
							'lat'        => $value->user_meta->latitude,
							'lng'        => $value->user_meta->longitude,
							'user_name'  => $value->name . ' ' . $value->user_meta->lname,
							'user_image' => $value->user_meta->user_image,
							'rating_cnt' => $menu_data->totalRatingsCount($value->id),
							'mnu_rating' => $menu_data->menuRatings($value->id),
							'user_id'    => $common_data->encrypt($value->id),
							'address'    => $value->user_meta->address,
						);
					}
				}
			}
		}
		return $location_arr;
	}

	public function searchMenuLatitudeAndLongitude(Request $request) {
		$lat_long_arr = array();
		if (!$request->search_data) {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Please enter menu to search.'), 400);
		}
		$search_data = $request->search_data;
		$latlong     = MenuProduct::whereHas('user.user_meta', function ($query) use ($search_data) {
			$query->where('is_store_open_or_close', 1);
			$query->where('name', 'like', '%' . $search_data . '%');
			$query->orWhere('lname', 'like', '%' . $search_data . '%');
		})->orWhere('product_name', 'like', '%' . $search_data . '%')->get();
		if (count($latlong)) {
			foreach ($latlong as $key => $value) {
				if (!empty($value->user->user_meta)) {
					if ($value->user->user_meta->address != null && $value->user->user_meta->latitude != null && $value->user->user_meta->longitude != null) {
						$lat_long_arr[$key]['address']   = $value->user->user_meta->address;
						$lat_long_arr[$key]['latitude']  = $value->user->user_meta->latitude;
						$lat_long_arr[$key]['longitude'] = $value->user->user_meta->longitude;
						$lat_long_arr                    = array_values($lat_long_arr);
					}
				} else {
					return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, try again later.'), 400);
				}
			}
			return response()->json(array('data' => $lat_long_arr, 'status' => 'success', 'responseMessage' => 'data found.'), 200);
		} else {
			return response()->json(array('data' => null, 'status' => 'success', 'responseMessage' => 'No items found as per your search.'), 200);
		}
	}

	public function leftSidebarGoogleMapSearch(Request $request) {
		$lat_long_arr = array();
		if ($request->ids == null) {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Please filter option to search.'), 400);
		} else {
			$products = $this->commonCheckboxSidebarFunction($request->ids);
			if (count($products) > 0) {
				foreach ($products as $key => $value) {
					if (!empty($value->user->user_meta)) {
						if ($value->user->user_meta->latitude != null && $value->user->user_meta->longitude != null) {
							$lat_long_arr[$key]['address']   = $value->user->user_meta->address;
							$lat_long_arr[$key]['latitude']  = $value->user->user_meta->latitude;
							$lat_long_arr[$key]['longitude'] = $value->user->user_meta->longitude;
						}
					} else {
						return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, try again later.'), 400);
					}
				}
				$lat_long_arr = array_values($lat_long_arr);
				$lat_long_arr = array_map("unserialize", array_unique(array_map("serialize", $lat_long_arr)));
				$lat_long_arr = array_values($lat_long_arr);
				return response()->json(array('data' => $lat_long_arr, 'status' => 'success', 'responseMessage' => 'data found.'), 200);
			} else {
				return response()->json(array('data' => null, 'status' => 'success', 'responseMessage' => 'No items found as per your search.'), 200);
			}
		}
	}

	public function commonCheckboxSidebarFunction($checkbox_ids) {
		$ids_arr = $checkbox_ids;
		$query   = MenuProduct::query();
		if (array_intersect([1, 2, 3, 4, 5], $ids_arr)) {
			$query->whereIn('category_id', $ids_arr);
		}
		if (in_array(6, $ids_arr)) {
			$query->where("price_type", 0);
		}
		if (in_array(7, $ids_arr)) {
			$pick_up = 0;
			$query->whereHas('user.user_meta', function ($q) use ($pick_up) {
				$q->where('store_delivery_option', 'like', '%' . $pick_up . '%');
				$q->where('is_store_open_or_close', 1);
			});
		}
		if (in_array(8, $ids_arr)) {
			$delivery = 1;
			$query->whereHas('user.user_meta', function ($q) use ($delivery) {
				$q->where('store_delivery_option', 'like', '%' . $delivery . '%');
				$q->where('is_store_open_or_close', 1);
			});
		}
		if (array_intersect([1, 2, 3, 4, 5], $ids_arr) && in_array(6, $ids_arr)) {
			$query->orWhere("price_type", 0);
		}
		if (array_intersect([1, 2, 3, 4, 5], $ids_arr) && in_array(7, $ids_arr)) {
			$pick_up = 0;
			$query->whereHas('user.user_meta', function ($q) use ($pick_up) {
				$q->orWhere('store_delivery_option', 'like', '%' . $pick_up . '%');
				$q->where('is_store_open_or_close', 1);
			});
			$query->orWhereIn('category_id', $ids_arr);
		}
		if (array_intersect([1, 2, 3, 4, 5], $ids_arr) && in_array(8, $ids_arr)) {
			$delivery = 1;
			$query->whereHas('user.user_meta', function ($q) use ($delivery) {
				$q->orWhere('store_delivery_option', 'like', '%' . $delivery . '%');
				$q->where('is_store_open_or_close', 1);
			});
			$query->orWhereIn('category_id', $ids_arr);
		}
		if (in_array(6, $ids_arr) && in_array(7, $ids_arr)) {
			$pick_up = 0;
			$query->whereHas('user.user_meta', function ($q) use ($pick_up) {
				$q->orWhere('store_delivery_option', 'like', '%' . $pick_up . '%');
				$q->where('is_store_open_or_close', 1);
			});
			$query->orWhere("price_type", '=', 0);
		}
		if (in_array(6, $ids_arr) && in_array(8, $ids_arr)) {
			$delivery = 1;
			$query->whereHas('user.user_meta', function ($q) use ($delivery) {
				$q->orWhere('store_delivery_option', 'like', '%' . $delivery . '%');
				$q->where('is_store_open_or_close', 1);
			});
			$query->orWhere("price_type", '=', 0);
		}
		$products = $query->inRandomOrder()->take(12)->get();
		return $products;
	}

	public function termsAndConditions() {
		return view('frontend.terms-and-conditions');
	}

	public function privacyPolicy() {
		return view('frontend.privacy-policy');
	}

	public function getLatLongFromIP() {
		// $apiURL = 'https://freegeoip.app/json/' . $_SERVER['REMOTE_ADDR'];
		$apiURL   = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));
		$lat_long = array(
			'latitude'  => $apiURL['geoplugin_latitude'],
			'longitude' => $apiURL['geoplugin_longitude'],
		);
		return $lat_long;
	}

	public function checkDisclaimer() {
		if (!Auth::guard('users')->check()) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		$user = UserMeta::select('is_accepted')->where('user_id', Auth::guard('users')->user()->id)->first();
		if (!$user) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		if ($user->is_accepted == 0) {
			UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
				'is_accepted' => 1,
			]);
			$message = "Thank you for your feedback.";
			return redirect()->route('front.home')->with(['content' => $message, 'level' => 'success']);
		}
	}
}
