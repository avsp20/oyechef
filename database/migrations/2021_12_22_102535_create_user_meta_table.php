<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMetaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (!Schema::hasTable('user_meta')) {
			Schema::create('user_meta', function (Blueprint $table) {
				$table->id();
				$table->integer('role_id')->unsigned()->nullable();
				$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
				$table->bigInteger('user_id')->unsigned()->nullable();
				$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
				$table->longText('user_ratings_id')->nullable();
				$table->longText('user_favorites_id')->nullable();
				$table->string('lname')->nullable();
				$table->string('username', 100)->nullable();
				$table->string('phone', 15)->nullable();
				$table->integer('country')->unsigned()->nullable();
				$table->foreign('country')->references('id')->on('countries')->onDelete('cascade');
				$table->integer('state')->unsigned()->nullable();
				$table->foreign('state')->references('id')->on('states')->onDelete('cascade');
				$table->integer('city')->unsigned()->nullable();
				$table->foreign('city')->references('id')->on('cities')->onDelete('cascade');
				$table->text('address')->nullable();
				$table->string('zipcode', 10)->nullable();
				$table->text('user_image')->nullable();
				$table->string('latitude', 100)->nullable();
				$table->string('longitude', 100)->nullable();
				$table->text('facebook_key')->nullable();
				$table->text('google_key')->nullable();
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
		Schema::dropIfExists('user_meta');
	}
}
