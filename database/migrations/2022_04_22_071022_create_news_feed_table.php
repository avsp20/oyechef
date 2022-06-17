<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsFeedTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (!Schema::hasTable('news_feed')) {
			Schema::create('news_feed', function (Blueprint $table) {
				$table->id();
				$table->bigInteger('user_id')->unsigned()->nullable();
				$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
				$table->longText('content')->nullable();
				$table->text('file')->comment('Image/Video')->nullable();
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
		Schema::dropIfExists('news_feed');
	}
}
