<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follower extends Model {
	use HasFactory, SoftDeletes;

	protected $table = 'followers';

	protected $fillable = [
		'follower_id', 'following_id',
	];

	public function user() {
		return $this->belongsTo(User::class, 'following_id', 'id');
	}
}
