<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserKitchenStatusInUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('user_meta', function (Blueprint $table) {
			$table->boolean('status')->default(1)->after('google_key');
			$table->boolean('is_store_open_or_close')->default(1)->comment('1=Open,2=close')->after('status');
			$table->string('store_delivery_option', 50)->comment('0=Pickup,1=Delivery,2=Both')->nullable()->after('is_store_open_or_close');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('user_meta', function (Blueprint $table) {
			$table->dropColumn('status');
			$table->dropColumn('is_store_open_or_close');
			$table->dropColumn('store_delivery_option');
		});
	}
}
