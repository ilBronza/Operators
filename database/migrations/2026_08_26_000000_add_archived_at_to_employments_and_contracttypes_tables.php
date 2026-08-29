<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table(config('operators.models.employment.table'), function (Blueprint $table)
		{
			$table->timestamp('archived_at')->nullable()->index();
		});

		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table)
		{
			$table->timestamp('archived_at')->nullable()->index();
		});
	}

	public function down(): void
	{
		Schema::table(config('operators.models.employment.table'), function (Blueprint $table)
		{
			$table->dropColumn('archived_at');
		});

		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table)
		{
			$table->dropColumn('archived_at');
		});
	}
};
