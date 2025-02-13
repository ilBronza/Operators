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
		Schema::table(config('operators.models.operator.table'), function (Blueprint $table) {
			$table->unsignedSmallInteger('internal_approval_rating')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table(config('operators.models.operator.table'), function (Blueprint $table) {
			$table->dropColumn('internal_approval_rating');
		});
	}
};
