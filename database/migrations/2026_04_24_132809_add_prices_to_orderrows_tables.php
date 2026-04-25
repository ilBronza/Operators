<?php

use IlBronza\Products\Models\Orders\Orderrow;
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
		$tableName = Orderrow::gpc()::make()->extraFields()->make()->getTable();

		foreach ($this->getFields() as $field) {
			if (! Schema::hasColumn($tableName, $field)) {
				Schema::table($tableName, function (Blueprint $table) use ($field) {
					$table->decimal($field, 9, 2)->nullable();
				});
			}
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		$tableName = Orderrow::gpc()::make()->extraFields()->make()->getTable();

		foreach ($this->getFields() as $field) {
			if (Schema::hasColumn($tableName, $field)) {
				Schema::table($tableName, function (Blueprint $table) use ($field) {
					$table->dropColumn($field);
				});
			}
		}
	}

	/**
	 * Get the decimal extra fields handled by this migration.
	 */
	private function getFields() : array
	{
		return [
			'stored_cost_per_day',
			'stored_cost_per_hour',
			'stored_revenue_per_day',
			'stored_revenue_per_hour',
		];
	}
};