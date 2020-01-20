<?php
namespace Abs\SmsPkg\Database\Seeds;

use App\Permission;
use Illuminate\Database\Seeder;

class SmsPkgPermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$permissions = [
			//SMS TEMPLATES
			[
				'display_order' => 99,
				'parent' => null,
				'name' => 'sms-templates',
				'display_name' => 'SMS Templates',
			],
			[
				'display_order' => 1,
				'parent' => 'sms-templates',
				'name' => 'add-sms-template',
				'display_name' => 'Add',
			],
			[
				'display_order' => 2,
				'parent' => 'sms-templates',
				'name' => 'delete-sms-template',
				'display_name' => 'Edit',
			],
			[
				'display_order' => 3,
				'parent' => 'sms-templates',
				'name' => 'delete-sms-template',
				'display_name' => 'Delete',
			],

			//SMS EVENTS
			[
				'display_order' => 99,
				'parent' => null,
				'name' => 'sms-events',
				'display_name' => 'SMS Events',
			],
			[
				'display_order' => 1,
				'parent' => 'sms-events',
				'name' => 'add-sms-event',
				'display_name' => 'Add',
			],
			[
				'display_order' => 2,
				'parent' => 'sms-events',
				'name' => 'delete-sms-event',
				'display_name' => 'Edit',
			],
			[
				'display_order' => 3,
				'parent' => 'sms-events',
				'name' => 'delete-sms-event',
				'display_name' => 'Delete',
			],

		];
		Permission::createFromArrays($permissions);

	}
}