<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		$tableName = config('operators.models.employment.table');
		$columnAdded = false;

		if (! Schema::hasColumn($tableName, 'sorting_index'))
		{
			$columnAdded = true;

			Schema::table($tableName, function (Blueprint $table)
			{
				$table->unsignedInteger('sorting_index')->nullable()->after('name');
			});
		}

		if (! $columnAdded)
			return;

		$sortingIndex = 1;

		DB::table($tableName)
			->whereNull('sorting_index')
			->orderBy('created_at')
			->orderBy('name')
			->orderBy('id')
			->select('id')
			->get()
			->each(function ($employment) use (&$sortingIndex, $tableName)
			{
				DB::table($tableName)
					->where('id', $employment->id)
					->update(['sorting_index' => $sortingIndex ++]);
			});
	}

	public function down() : void
	{
		$tableName = config('operators.models.employment.table');

		if (Schema::hasColumn($tableName, 'sorting_index'))
			Schema::table($tableName, function (Blueprint $table)
			{
				$table->dropColumn('sorting_index');
			});
	}
};
