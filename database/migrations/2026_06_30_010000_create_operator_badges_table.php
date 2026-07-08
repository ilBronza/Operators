<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::create(config('operators.models.operatorBadge.table'), function (Blueprint $table)
		{
			$table->uuid('id')->primary();

			$table->uuid('operator_id')->nullable();
			$table->foreign('operator_id')
				->references('id')
				->on(config('operators.models.operator.table'))
				->onDelete('set null');

			$table->string('name', 64)->unique();
			$table->boolean('active')->nullable()->default(true);
			$table->timestamp('valid_from')->nullable();
			$table->timestamp('valid_to')->nullable();
			$table->text('notes')->nullable();

			$table->softDeletes();
			$table->timestamps();

			$table->index(['operator_id', 'active']);
		});
	}

	public function down() : void
	{
		Schema::dropIfExists(config('operators.models.operatorBadge.table'));
	}
};
