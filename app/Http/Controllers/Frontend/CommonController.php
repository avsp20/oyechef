<?php

namespace App\Http\Controllers\Frontend;

use App\Constant;
use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\MenuCategory;
use App\Models\MenuProduct;
use App\Models\User;
use App\Models\UserMeta;
use Auth;
use DB;
// use Illuminate\Http\Request;

class CommonController extends Controller {
	public function userData() {
		if (Auth::guard('users')->check()) {
			$user = Auth::guard('users')->user();
		}
		return $user;
	}

	public function userDetails($id) {
		$user = User::with('user_meta')->where('id', $id)->first();
		return $user;
	}

	public function userMenu() {
		$menu      = 0;
		$user_data = new CommonController;
		$id        = $user_data->decrypt(request()->route('id'));
		$menu      = MenuProduct::where('user_id', $id)->count();
		return $menu;
	}

	public function editUserMenu() {
		$menu = 0;
		if (Auth::guard('users')->check()) {
			$menu = MenuProduct::where('user_id', Auth::guard('users')->user()->id)->count();
		}
		return $menu;
	}

	public function MenuCategory() {
		$menu = MenuCategory::get();
		return $menu;
	}

	public static function encrypt($input) {
		$encrypt = str_replace('/', '_', openssl_encrypt($input, "AES-128-ECB", Constant::SECRET_HASH));
		return $encrypt;
	}

	public static function decrypt($input) {
		$decrypt = str_replace('_', '/', $input);
		return openssl_decrypt($decrypt, "AES-128-ECB", Constant::SECRET_HASH);
	}

	public function userFavoriteMenus() {
		$fav_menu = array();
		if (Auth::guard('users')->check()) {
			$user_meta = UserMeta::select('user_favorites_id')->where('user_id', Auth::guard('users')->user()->id)->first();
			if ($user_meta) {
				if ($user_meta->user_favorites_id) {
					$fav_menu = User::with('user_meta')->whereIn('id', json_decode($user_meta->user_favorites_id))->get();
				}
			}
		}
		return $fav_menu;
	}

	public function totalFavorites($id) {
		$total_fav = 0;
		$user_meta = UserMeta::select('user_favorites_id')->get();
		if (count($user_meta) > 0) {
			foreach ($user_meta as $value) {
				if ($value->user_favorites_id != null) {
					if (in_array($id, json_decode($value->user_favorites_id))) {
						$total_fav++;
					}
				}
			}
		}
		return $total_fav;
	}

	public function getLoginUserFavoriteMenu($id) {
		$fav_user = 0;
		$common   = new CommonController;
		if (Auth::guard('users')->check()) {
			$id       = $common->decrypt($id);
			$get_user = UserMeta::select('user_favorites_id')->where('user_id', Auth::guard('users')->user()->id)->first();
			if ($get_user) {
				if ($get_user->user_favorites_id != null) {
					if (in_array($id, json_decode($get_user->user_favorites_id))) {
						$fav_user = 1;
					}
				}
			}
		}
		return $fav_user;
	}

	public function checkDisclaimer() {
		$accept_flag = 0;
		if (Auth::guard('users')->check()) {
			$user = UserMeta::select('is_accepted')->where('user_id', Auth::guard('users')->user()->id)->first();
			if ($user->is_accepted == 1) {
				$accept_flag = 1;
			}
		}
		return $accept_flag;
	}

	public function userBanner() {
		$common = new CommonController;
		$path   = public_path('frontend/img/user_banners/');
		if (request()->segment(1) == 'my-account' || request()->segment(1) == 'edit-menu') {
			if (Auth::guard('users')->check()) {
				if (!empty(Auth::guard('users')->user()->user_meta)) {
					if (Auth::guard('users')->user()->user_meta->banner_image != null) {
						$banner_image = Auth::guard('users')->user()->user_meta->banner_image;
						$banner       = "background-image: url($banner_image) !important";
					} else {
						$banner_image = asset('public/frontend/img/oyechef-banner.jpg');
						$banner       = "background-image: url($banner_image) !important";
					}
				} else {
					$banner_image = asset('public/frontend/img/oyechef-banner.jpg');
					$banner       = "background-image: url($banner_image) !important";
				}
			}
		} elseif (request()->segment(1) == "menu") {
			$encrypt      = $common->decrypt(request()->route('id'));
			$user_details = $common->userDetails($encrypt);
			if ($user_details->user_meta->banner_image != null) {
				$banner_image = $user_details->user_meta->banner_image;
				$banner       = "background-image: url($banner_image) !important";
			} else {
				$banner_image = asset('public/frontend/img/oyechef-banner.jpg');
				$banner       = "background-image: url($banner_image) !important";
			}
		}
		if (!file_exists(public_path('frontend/img/user_banners/' . $banner_image))) {
			$banner_image = asset('public/frontend/img/oyechef-banner.jpg');
			$banner       = "background-image: url($banner_image) !important";
		} else {
			$banner_image = asset('public/frontend/img/user_banners/' . $banner_image);
			$banner       = "background-image: url($banner_image) !important";
		}
		return $banner;
	}

	public function getUsername() {
		$common = new CommonController;
		if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) {
			$flag = 1;
		} else {
			$flag = 0;
		}
		if (request()->segment(1) == "edit-menu" || request()->segment(1) == "my-account") {
			$user       = Auth::guard('users')->user();
			$username   = ucfirst($user->name);
			$word_count = strlen($user->name . ' ' . $user->user_meta->lname);
			if ($user->user_meta != null) {
				if($user->user_meta->is_username_active == 1){
					$username = $user->user_meta->username;
				}else{
					if ($flag == 1) {
						if ($word_count >= 12) {
							$username .= ' ' . ucfirst($user->user_meta->lname[0]);
						} else {
							$username .= ' ' . ucfirst($user->user_meta->lname);
						}
					} else {
						$username .= ' ' . ucfirst($user->user_meta->lname);
					}
				}
			}
		} elseif (request()->segment(1) == "menu" || request()->segment(1) == "profile") {
			if(request()->route('id') == null){
				$user_details = $common->userDetails(Auth::guard('users')->user()->id);
			}else{
				$encrypt      = $common->decrypt(request()->route('id'));
				$user_details = $common->userDetails($encrypt);
			}
			$username     = ucfirst($user_details->name);
			$word_count   = strlen($user_details->name . ' ' . $user_details->user_meta->lname);
			if ($user_details->user_meta != null) {
				if($user_details->user_meta->is_username_active == 1){
					$username = $user_details->user_meta->username;
				}else{
					if ($flag == 1) {
						if($user_details->user_meta->lname != null){
							if ($word_count >= 12) {
								$username .= ' ' . ucfirst($user_details->user_meta->lname[0]);
							} else {
								$username .= ' ' . ucfirst($user_details->user_meta->lname);
							}
						}
					} else {
						$username .= ' ' . ucfirst($user_details->user_meta->lname);
					}
				}
			}
		}
		return $username;
	}

	public function getUserimage() {
		$common = new CommonController;
		$img    = asset('public/frontend/img/no-profile-img.jpg');
		if (request()->segment(1) == "edit-menu" || request()->segment(1) == "my-account") {
			if (Auth::guard('users')->user()->user_meta != null) {
				if (Auth::guard('users')->user()->user_meta->user_image != null) {
					$img = asset('public/frontend/img/user_profiles/' . Auth::guard('users')->user()->user_meta->user_image);
				}
			}
		} elseif (request()->segment(1) == "menu" || request()->segment(1) == "profile") {
			$encrypt      = $common->decrypt(request()->route('id'));
			$user_details = $common->userDetails($encrypt);
			if ($user_details->user_meta->user_image != null) {
				$img = asset('public/frontend/img/user_profiles/' . $user_details->user_meta->user_image);
			}
		}
		return $img;
	}

	public function userFollowing() {
		$following = array();
		if (Auth::guard('users')->check()) {
			$following = Follower::with('user.user_meta')->where('follower_id', Auth::guard('users')->user()->id)->get();
		}
		return $following;
	}

	public function profileCommon($id)
	{
		$display_flag = 0;
		$common = new CommonController;
		$user_id = $common->decrypt($id);
		if(Auth::guard('users')->check()){
			if(Auth::guard('users')->user()->id == $user_id){
				$display_flag = 1;
			}elseif(Auth::guard('users')->user()->id != $user_id){
				$display_flag = 2;
			}
		}
		return $display_flag;
	}

	public function profileFollowerCount($user_id)
	{
		$followers_count = User::withCount('follower')->where('id',$user_id)->first();
		return $followers_count;
	}

	public function userFollowerStatus($user_id)
	{
		$follow_flag = 0;
		$common = new CommonController;
		$user_id = $common->decrypt($user_id);
		$following = Follower::with('user.user_meta')->where('following_id', $user_id)->first();
		if(Auth::guard('users')->check()){
			$following = Follower::with('user.user_meta')->where('follower_id', Auth::guard('users')->user()->id)->where('following_id',$user_id)->first();
		}
		if($following){
			$follow_flag = 1;
		}
		return $follow_flag;
	}

	public function randomFollowers()
	{
		$topUsersJoinQuery = DB::table('followers')
            ->select('following_id', DB::raw('COUNT(following_id) AS count'))
            ->groupBy('following_id');
		$top_users = DB::table('users')->select('*')->join('user_meta','users.id','=','user_meta.user_id')
            ->join(DB::raw('(' . $topUsersJoinQuery->toSql() . ') i'), function ($join)
            {
                $join->on('i.following_id', '=', 'users.id');
            })
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();
        return $top_users;
	}
}
