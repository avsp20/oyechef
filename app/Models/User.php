<?php

namespace App\Models;

use App\Models\UserMeta;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'role_id',
		'name',
		'lname',
		'username',
		'email',
		'phone',
		'password',
		'user_image',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function user_meta() {
		return $this->belongsTo(UserMeta::class, 'id', 'user_id');
	}

	public function followers() {
		return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')->where('follower_id', Auth::guard('users')->user()->id)->where('followers.deleted_at',null);
	}

	public function follower() {
	    return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')->where('followers.deleted_at',null);
	}

	public function following() {
	    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
	}

	// public function following() {
	// 	return $this->belongsTo(NewsFeedFollowing::class, 'id', 'following_user_id');
	// }
}
