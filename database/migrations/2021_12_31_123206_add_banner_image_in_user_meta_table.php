<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerImageInUserMetaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('user_meta', function (Blueprint $table) {
			$table->text('banner_image')->nullable()->after('store_delivery_option');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('user_meta', function (Blueprint $table) {
			$table->dropColumn('banner_image');
		});
	}
}
