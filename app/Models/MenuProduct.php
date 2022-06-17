<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuProduct extends Model {
	use HasFactory;

	protected $table = 'products';
	protected $fillable = [
		'category_id',
		'user_id',
		'product_name',
		'price_type',
		'product_price',
		'product_image',
	];

	public function user() {
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function user_meta() {
		return $this->hasOne(UserMeta::class, 'user_id', 'user_id');
	}
}
