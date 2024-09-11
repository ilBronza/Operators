<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up() : void
	{
		Schema::table(config('operators.models.employment.table'), function (Blueprint $table)
		{
			$table->string('label', 16)->after('slug')->nullable();
			$table->string('hex_rgba', 8)->after('slug')->nullable();
		});

		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table)
		{
			$table->string('label', 8)->after('slug')->nullable();
			$table->string('hex_rgba', 8)->after('slug')->nullable();
		});

		Schema::table(config('operators.models.operator.table'), function (Blueprint $table)
		{
			$table->string('hex_rgba', 8)->after('slug')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		Schema::table(config('operators.models.employment.table'), function (Blueprint $table)
		{
			$table->dropColumn('label');
			$table->dropColumn('hex_rgba');
		});

		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table)
		{
			$table->dropColumn('label');
			$table->dropColumn('hex_rgba');
		});

		Schema::table(config('operators.models.operator.table'), function (Blueprint $table)
		{
			$table->dropColumn('hex_rgba');
		});
	}
};
