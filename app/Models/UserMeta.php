<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model {
	use HasFactory;
	protected $table    = 'user_meta';
	protected $fillable = [
		'role_id',
		'user_id',
		'user_ratings_id',
		'user_favorites_id',
		'lname',
		'username',
		'phone',
		'country',
		'state',
		'city',
		'address',
		'zipcode',
		'user_image',
		'latitude',
		'longitude',
		'facebook_key',
		'google_key',
		'status',
		'is_store_open_or_close',
		'store_delivery_option',
		'banner_image',
	];

	public function user() {
		return $this->belongsTo(User::class, 'id', 'user_id');
	}

	public function products() {
		return $this->hasMany(MenuProduct::class, 'user_id', 'user_id');
	}

	// public function followers() {
	//     return $this->belongsTo(UserFollower::class, 'id', 'user_id');
	// }
}
