<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$roles = [
			[
				'id' => 1,
				'name' => 'Admin',
				'created_at' => date('y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id' => 2,
				'name' => 'User',
				'created_at' => date('y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
		];

		Role::insert($roles);
	}
}
