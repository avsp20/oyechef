<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (!Schema::hasTable('products')) {
			Schema::create('products', function (Blueprint $table) {
				$table->id();
				$table->bigInteger('category_id')->unsigned()->nullable();
				$table->foreign('category_id')->references('id')->on('menu_categories')->onDelete('cascade');
				$table->bigInteger('user_id')->unsigned()->nullable();
				$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
				$table->string('product_name')->nullable();
				$table->boolean('price_type')->default(1);
				$table->float('product_price', 8, 2)->nullable();
				$table->text('product_image')->nullable();
				$table->timestamps();
				$table->timestamp('deleted_at')->nullable();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('products');
	}
}
