<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CommonController;
use App\Models\MenuCategory;
use App\Models\MenuProduct;
use App\Models\User;
use App\Models\UserMeta;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\StoreProductRequest;
use App\Http\Requests\Frontend\UpdateProductRequest;

class MenuController extends Controller {
	public function index(Request $request, $id) {
		$user_data = new CommonController;
		if ($id) {
			$id       = $user_data->decrypt($id);
			$user_id  = $id;
			$menu     = MenuCategory::get();
			$user     = $user_data->userDetails($id);
			$products = MenuCategory::with(['products' => function ($query) use ($id) {
				$query->where('user_id', $id);
				$query->orderBy('sort_order');
			}])->get();
			$fav_user_count = $user_data->totalFavorites($id);
			$ratings_count  = $this->totalRatingsCount($id);
			$menu_ratings   = $this->menuRatings($id);
			$menu_count     = $user_data->userMenu();
			$user_ratings   = $this->userMenuRatings($id);
			return view('frontend.menu.menu-page', compact('products', 'menu', 'user', 'user_id', 'fav_user_count', 'ratings_count', 'menu_ratings', 'menu_count', 'user_ratings'));
		} else {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function createMenu(Request $request) {
		if (!Auth::guard('users')->check()) {
			return redirect()->route('front.login');
		}
		$menu      = MenuCategory::get();
		$user_data = new CommonController;
		$user      = $user_data->userDetails(Auth::guard('users')->user()->id);
		$products  = MenuCategory::with(['products' => function ($query) {
			$query->where('user_id', Auth::guard('users')->user()->id);
			$query->orderBy('sort_order');
		}])->get();
		$fav_user_count = $user_data->totalFavorites(Auth::guard('users')->user()->id);
		$ratings_count  = $this->totalRatingsCount(Auth::guard('users')->user()->id);
		$menu_ratings   = $this->menuRatings(Auth::guard('users')->user()->id);
		// $menu_count = $user_data->userMenu();
		$menu_count   = $user_data->editUserMenu();
		$user_ratings = $this->userMenuRatings(Auth::guard('users')->user()->id);
		if ($products) {
			return view('frontend.menu.edit-menu', compact('menu', 'products', 'user', 'fav_user_count', 'ratings_count', 'menu_ratings', 'menu_count', 'user_ratings'));
		} else {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function saveProduct(StoreProductRequest $request) {
		if (Auth::guard('users')->check()) {
			$product                = new MenuProduct();
			$product->category_id   = $request->category;
			$product->user_id       = Auth::guard('users')->user()->id;
			$product->product_name  = $request->food_name;
			$product->price_type    = $request->price_type; //($request->price_type == 1) ? 1 : 0;
			$product->product_price = $request->food_price;
			if ($request->file('food_image')) {
				$imageName = time() . '.' . $request->food_image->extension();
				$request->food_image->move(public_path('/frontend/img/user_dishes/'), $imageName);
				$product->product_image = $imageName;
			}
			$product->save();
			$message = "Your dish added successfully..";
			return redirect()->route('front.edit-menu')->with(['content' => $message, 'level' => 'success']);
		} else {
			return redirect()->route('front.edit-menu')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function filterMenuCategory(Request $request)
	{
		$menu      = MenuCategory::get();
		$user_data = new CommonController;
		$user      = $user_data->userData();
		if (!Auth::guard('users')->check()) {
			return redirect()->route('front.login');
		}
		$product = MenuProduct::where('user_id', Auth::guard('users')->user()->id)->count();
		$returnHTML = '<div class="text-center no-result-found">No items match your search.</div>';
		if($product == 0){
			return response()->json(array('html' => $returnHTML));
			// return response()->json(array('data' => $data, 'responseMessage' => 'Currently, No any items found in your menu.'), 200);
		}else{
			$ids = $request->ids;
			$id = Auth::guard('users')->user()->id;
			if ($ids) {
				$products = $this->checkboxSidebarFunction($ids, $id);
			} else {
				$products = MenuCategory::with(['products' => function ($query) use ($id, $ids) {
					$query->where('user_id', $id);
				}])->get();
			}
			if (count($products) > 0) {
				$returnHTML = view('frontend.menu.product-list', compact('products', 'menu', 'user'))->render();
			} else {
				$returnHTML = '<div class="text-center no-result-found">No items match your search.</div>';
			}
			return response()->json(array('html' => $returnHTML));
		}
	}

	public function filterMenuItems(Request $request, $id) {
		if ($id) {
			$user_data = new CommonController;
			$id        = $user_data->decrypt($id);
			$menu      = MenuCategory::get();
			$user      = $user_data->userDetails($id);
			$check_ids = $request->ids;
			$product = MenuProduct::where('user_id', $id)->count();
			$returnHTML = '<div class="text-center no-result-found">No items match your search.</div>';
			if($product == 0){
				return response()->json(array('html' => $returnHTML));
			}else{
				if ($check_ids) {
					$products = $this->checkboxSidebarFunction($check_ids, $id);
				} else {
					$products = MenuCategory::with(['products' => function ($query) use ($id, $check_ids) {
						$query->where('user_id', $id);
					}])->get();
				}
				if (count($products) > 0) {
					$returnHTML = view('frontend.menu.user-items', compact('products', 'menu', 'user'))->render();
				} else {
					$returnHTML = '<div class="text-center no-result-found">No items match your search.</div>';
				}
				return response()->json(array('html' => $returnHTML));
			}
		} else {
			return response()->json(array('error' => 'Something went wrong, Please try again later.'));
		}
	}

	public function mobileFilterOption(Request $request, $id) {
		if ($id) {
			$user_data = new CommonController;
			$id        = $user_data->decrypt($id);
			$menu      = MenuCategory::get();
			$user      = $user_data->userDetails($id);
			$check_ids = $request->ids;
			if ($check_ids) {
				$products = $this->checkboxSidebarFunction($check_ids, $id);
			} else {
				$products = MenuCategory::with(['products' => function ($query) use ($id, $check_ids) {
					$query->where('user_id', $id);
				}])->get();
			}
			if (count($products) > 0) {
				$returnHTML = view('frontend.menu.user-items', compact('products', 'menu', 'user'))->render();
			} else {
				$returnHTML = '<div class="text-center no-result-found">No items match your search.</div>';
			}
			return response()->json(array('html' => $returnHTML));
			// $menu = MenuCategory::get();
			// $user = $user_data->userDetails($id);
			// $check_ids = $request->ids;
			// if ($check_ids) {
			// 	$products = $this->checkboxSidebarFunction($check_ids, $id);
			// } else {
			// 	$products = MenuCategory::with(['products' => function ($query) use ($id, $check_ids) {
			// 		$query->where('user_id', $id);
			// 	}])->get();
			// }
			// if (count($products) > 0) {
			// 	$returnHTML = view('frontend.menu.user-items', compact('products', 'menu', 'user'))->render();
			// } else {
			// 	$returnHTML = '<div class="text-center no-result-found">No items match your search.</div>';
			// }
			// return response()->json(array('html' => $returnHTML));
		} else {
			return response()->json(array('error' => 'Something went wrong, Please try again later.'));
		}
	}

	public function editUserStoreStatus($status) {
		if ($status > 0) {
			UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
				'is_store_open_or_close' => $status,
			]);
			return response()->json(array('data' => 'success', 'responseMessage' => 'Status updated successfully.'), 200);
		} else {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, Please try again later.'), 400);
		}
	}

	public function userStoreUpdateDeliveryStatus(Request $request) {
		if (Auth::guard('users')->check()) {
			UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
				'store_delivery_option' => $request->ids,
			]);
			return response()->json(array('data' => 'success', 'responseMessage' => 'Delivery status updated successfully.'), 200);
		} else {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, Please try again later.'), 400);
		}
	}

	public function updateProduct(UpdateProductRequest $request, $id) {
		if (Auth::guard('users')->check()) {
			$menu_product = MenuProduct::find($id);
			if ($request->hasFile('item_image')) {
				$imageName = time() . '.' . $request->item_image->extension();
				$request->item_image->move(public_path('/frontend/img/user_dishes/'), $imageName);
				$menu_product->product_image = $imageName;
			}
			$menu_product->category_id   = $request->item_category;
			$menu_product->product_name  = $request->item_name;
			$menu_product->price_type    = $request->item_type;
			$menu_product->product_price = ($request->item_type == 1) ? $request->item_price : null;
			$menu_product->save();
			$message = "Your dish updated successfully..";
			return redirect()->route('front.edit-menu')->with(['content' => $message, 'level' => 'success']);
		} else {
			return redirect()->route('front.edit-menu')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function deleteUserMenuItem(Request $request) {
		if (Auth::guard('users')->check()) {
			$products = MenuProduct::find($request->id);
			if ($products) {
				MenuProduct::where('id', $request->id)->delete();
				return response()->json(array('data' => 'success', 'responseMessage' => 'Your dish deleted successfully.'), 200);
			} else {
				return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, Please try again later.'), 400);
			}
		} else {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, Please try again later.'), 400);
		}
	}

	public function addToFavorites($user_id) {
		if (!Auth::guard('users')->check()) {
			return redirect()->route('front.login');
		}
		$user_data    = new CommonController;
		$id           = $user_data->decrypt($user_id);
		$fav_arr      = array();
		$favorite_arr = array();
		array_push($favorite_arr, $id);
		$get_user = UserMeta::where('user_id', Auth::guard('users')->user()->id)->first();
		if ($get_user) {
			if ($get_user->user_favorites_id == null) {
				UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
					'user_favorites_id' => $favorite_arr,
				]);
				return redirect()->route('front.menu', [$user_id])->with(['content' => 'Added to favorites', 'level' => 'success']);
			} else {
				$decode_user = json_decode($get_user->user_favorites_id);
				if (in_array($id, $decode_user)) {
					$pos = array_search($id, $decode_user);
					unset($decode_user[$pos]);
					if (count($decode_user) > 0) {
						$favorite_arr = array_values($decode_user);
					} else {
						$favorite_arr = null;
					}
					UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
						'user_favorites_id' => $favorite_arr,
					]);
					return redirect()->route('front.menu', [$user_id])->with(['content' => 'Removed favorite', 'level' => 'success']);
				} else {
					array_push($decode_user, $id);
					$favorite_arr = $decode_user;
					UserMeta::where('user_id', Auth::guard('users')->user()->id)->update([
						'user_favorites_id' => $favorite_arr,
					]);
					return redirect()->route('front.menu', [$user_id])->with(['content' => 'Added to favorites', 'level' => 'success']);
				}
			}
		} else {
			return redirect()->route('front.edit-menu', [$user_id])->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function totalRatingsCount($user_id) {
		$ratings_count = 0;
		if (!$user_id) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		$user_meta = UserMeta::select('user_ratings_id')->where('user_id', $user_id)->first();
		if (!$user_meta) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		if ($user_meta->user_ratings_id != null) {
			$ratings_count = count(unserialize($user_meta->user_ratings_id));
		}
		return $ratings_count;
	}

	public function menuRatings($user_id) {
		$menu_ratings = 0;
		if (!$user_id) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		$user_meta = UserMeta::select('user_ratings_id')->where('user_id', $user_id)->first();
		if (!$user_meta) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		if ($user_meta->user_ratings_id != null) {
			$unserialize   = unserialize($user_meta->user_ratings_id);
			$ratings_count = count($unserialize);
			foreach ($unserialize as $key => $value) {
				$menu_ratings = $menu_ratings + $value;
			}
			$menu_ratings = round(($menu_ratings / $ratings_count) * 2) / 2;
			$menu_ratings = number_format($menu_ratings, 1);
		}
		return $menu_ratings;
	}

	public function userMenuRatings($user_id) {
		$user_ratings = 0;
		if (!$user_id) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		$user_meta = UserMeta::select('user_ratings_id')->where('user_id', $user_id)->first();
		if (!$user_meta) {
			return redirect()->route('front.home')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
		if ($user_meta->user_ratings_id != null) {
			$unserialize   = unserialize($user_meta->user_ratings_id);
			$ratings_count = count($unserialize);
			foreach ($unserialize as $key => $value) {
				if (Auth::guard('users')->check()) {
					if (Auth::guard('users')->user()->id == $key) {
						$user_ratings = $value;
					}
				}
			}
			$user_ratings = number_format(round($user_ratings), 1);
		}
		return $user_ratings;
	}

	public function sortOrder(Request $request) {
		if (!Auth::guard('users')->check()) {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, Please try again later.'), 400);
		}
		if (!$request->position) {
			return response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, Please try again later.'), 400);
		}
		foreach ($request->position as $key => $value) {
			$key      = $key + 1;
			$products = MenuProduct::where('user_id', Auth::guard('users')->user()->id)->where('id', $value)->update([
				'sort_order' => $key,
			]);
		}
		return response()->json(array('data' => 'success', 'responseMessage' => 'Your menu items reorderd successfully.'), 200);
	}

	public function checkboxSidebarFunction($checkbox_ids, $id) {
		$ids_arr = $checkbox_ids;
		$query   = MenuCategory::query();
		if (array_intersect([1, 2, 3, 4, 5], $ids_arr)) {
			$query->with(['products' => function ($query) use ($id, $ids_arr) {
				$query->where('user_id', $id);
				$query->whereIn('category_id', $ids_arr);
			}]);
		}
		if (in_array(6, $ids_arr)) {
			$query->with(['products' => function ($query) use ($id) {
				$query->where('user_id', $id);
				$query->where("price_type", 0);
			}]);
		}
		if (array_intersect([7, 8], $ids_arr)) {
			$query->with(['products' => function ($query) use ($id) {
				$query->where('user_id', $id);
			}]);
		}
		if (array_intersect([1, 2, 3, 4, 5], $ids_arr) && in_array(6, $ids_arr)) {
			$query->with(['products' => function ($query) use ($id, $ids_arr) {
				$query->whereIn('category_id', $ids_arr);
				$query->where('user_id', $id);
				$query->where("price_type", 0);
			}]);
		}
		$products = $query->get();
		return $products;
	}
}
