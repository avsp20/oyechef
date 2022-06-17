<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsfeed extends Model {
	use HasFactory;

	use SoftDeletes;

	protected $table = 'news_feed';

	protected $fillable = [
		'user_id', 'content', 'file',
	];

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function user_like() {
		return $this->hasOne(NewsFeedLikes::class, 'post_id', 'id')->where('user_id', Auth::guard('users')->user()->id);
	}

	public function likes() {
		return $this->hasMany(NewsFeedLikes::class, 'post_id', 'id');
	}

	public function user_following() {
		return $this->hasMany(Follower::class, 'following_id', 'user_id');
	}

	public function post_comments() {
		return $this->hasMany(Comment::class, 'post_id', 'id');
	}
}
