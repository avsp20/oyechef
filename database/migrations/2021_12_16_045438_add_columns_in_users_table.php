<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('users', function (Blueprint $table) {
			$table->integer('role_id')->unsigned()->nullable()->after('id');
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			$table->string('lname')->nullable()->after('name');
			$table->string('username', 100)->nullable()->after('lname');
			$table->string('phone', 15)->nullable()->after('email');
			$table->integer('country')->unsigned()->nullable()->after('phone');
			$table->foreign('country')->references('id')->on('countries')->onDelete('cascade');
			$table->integer('state')->unsigned()->nullable()->after('country');
			$table->foreign('state')->references('id')->on('states')->onDelete('cascade');
			$table->integer('city')->unsigned()->nullable()->after('state');
			$table->foreign('city')->references('id')->on('cities')->onDelete('cascade');
			$table->text('address')->nullable()->after('city');
			$table->string('zipcode', 15)->nullable()->after('address');
			$table->text('user_image')->nullable()->after('remember_token');
			$table->string('latitude', 100)->nullable()->after('user_image');
			$table->string('longitude', 100)->nullable()->after('latitude');
			$table->text('facebook_key')->nullable()->after('longitude');
			$table->text('google_key')->nullable()->after('facebook_key');
			$table->timestamp('deleted_at')->nullable()->after('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('users', function (Blueprint $table) {
			$table->dropForeign('users_role_id_foreign');
			$table->dropColumn('role_id');
			$table->dropColumn('lname');
			$table->dropColumn('username');
			$table->dropColumn('phone');
			$table->dropForeign('users_country_foreign');
			$table->dropColumn('country');
			$table->dropForeign('users_state_foreign');
			$table->dropColumn('state');
			$table->dropForeign('users_city_foreign');
			$table->dropColumn('city');
			$table->dropColumn('address');
			$table->dropColumn('zipcode');
			$table->dropColumn('user_image');
			$table->dropColumn('latitude');
			$table->dropColumn('longitude');
			$table->dropColumn('facebook_key');
			$table->dropColumn('google_key');
			$table->dropColumn('deleted_at');
		});
	}
}
