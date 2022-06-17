<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (!Schema::hasTable('followers')) {
			Schema::create('followers', function (Blueprint $table) {
				$table->id();
				$table->bigInteger('follower_id')->unsigned()->nullable();
				$table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
				$table->bigInteger('following_id')->unsigned()->nullable();
				$table->foreign('following_id')->references('id')->on('users')->onDelete('cascade');
				$table->timestamps();
				$table->softDeletes();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('followers');
	}
}
