<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
	public function up(): void
	{
		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table) {
			$table->decimal('cost_per_day', 6, 2)->nullable();
			$table->decimal('cost_per_hour', 4, 2)->nullable();

			$table->decimal('revenue_per_day', 6, 2)->nullable();
			$table->decimal('revenue_per_hour', 4, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table(config('operators.models.contracttype.table'), function (Blueprint $table) {
			$table->dropColumn('cost_per_day');
			$table->dropColumn('cost_per_hour');

			$table->dropColumn('revenue_per_day');
			$table->dropColumn('revenue_per_hour');
		});
	}
};
