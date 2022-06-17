<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsFeedLikes extends Model {
	use HasFactory, SoftDeletes;

	protected $table = 'post_likes';

	protected $fillable = [
		'user_id', 'post_id',
	];
}
