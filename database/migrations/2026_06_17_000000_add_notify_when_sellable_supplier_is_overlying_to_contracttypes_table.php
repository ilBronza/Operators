<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table)
		{
			$table->boolean('notify_when_sellable_supplier_is_overlying')->nullable()->after('hex_rgba');
		});
	}

	public function down() : void
	{
		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table)
		{
			$table->dropColumn('notify_when_sellable_supplier_is_overlying');
		});
	}
};
