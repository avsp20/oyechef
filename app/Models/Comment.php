<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;

    use HasFactory, SoftDeletes;

	protected $table = 'post_comments';

	protected $fillable = [
		'user_id', 'post_id', 'comment'
	];

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function news_feed() {
		return $this->hasOne(Newsfeed::class, 'id', 'post_id');
	}
}
