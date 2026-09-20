<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::create(config('operators.models.workingCounter.table'), function (Blueprint $table)
		{
			$table->uuid('id')->primary();
			$table->string('type', 16)->nullable();
			$table->date('reset_date')->nullable();
			$table->decimal('amount', 8, 2)->nullable();;
			$table->timestamp('expired_at')->nullable();

			$table->uuid('operator_id');
			$table->foreign('operator_id')->references('id')->on(config('operators.models.operator.table'));

			$table->uuid('client_operator_id')->nullable();
			$table->foreign('client_operator_id')->references('id')->on(config('operators.models.clientOperator.table'));

			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down() : void
	{
		Schema::dropIfExists(config('operators.models.workingCounter.table'));
	}
};
