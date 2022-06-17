<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$users = [
			[
				'id' => 1,
				'role_id' => 1,
				'name' => 'Admin',
				'lname' => 'Admin',
				'email' => 'admin@admin.com',
				'password' => \Hash::make('password'),
				'remember_token' => null,
				'created_at' => date('y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
		];

		User::insert($users);
	}
}
