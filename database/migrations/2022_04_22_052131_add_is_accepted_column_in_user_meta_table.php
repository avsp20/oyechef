<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAcceptedColumnInUserMetaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('user_meta', function (Blueprint $table) {
			$table->integer('is_accepted')->comment('0-Not Accepted,1-Accepted')->default(0)->after('banner_image');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('user_meta', function (Blueprint $table) {
			$table->dropColumn('is_accepted');
		});
	}
}
