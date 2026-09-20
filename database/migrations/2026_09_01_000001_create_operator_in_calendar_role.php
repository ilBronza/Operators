<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
	public function up() : void
	{
		Role::firstOrCreate([
			'name' => 'operatorInCalendar',
			'guard_name' => 'web'
		]);

		app(PermissionRegistrar::class)->forgetCachedPermissions();
	}

	public function down() : void
	{
		Role::where('name', 'operatorInCalendar')
			->where('guard_name', 'web')
			->delete();

		app(PermissionRegistrar::class)->forgetCachedPermissions();
	}
};
