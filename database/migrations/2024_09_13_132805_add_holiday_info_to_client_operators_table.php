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
        Schema::table(config('operators.models.clientOperator.table'), function (Blueprint $table) {
	        $table->decimal('holidays_reset', 6,2)->nullable();
	        $table->timestamp('holidays_reset_date')->nullable();

	        $table->decimal('flexibility_reset', 6,2)->nullable();
	        $table->timestamp('flexibility_reset_date')->nullable();

	        $table->decimal('rol_reset', 6,2)->nullable();
	        $table->timestamp('rol_reset_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('operators.models.clientOperator.table'), function (Blueprint $table) {
	        $table->dropColumn('holidays_reset');
	        $table->dropColumn('holidays_reset_date');

	        $table->dropColumn('flexibility_reset');
	        $table->dropColumn('flexibility_reset_date');

	        $table->dropColumn('rol_reset');
	        $table->dropColumn('rol_reset_date');
            //
        });
    }
};
