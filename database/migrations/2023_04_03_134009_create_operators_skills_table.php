<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorsSkillsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('operators.models.operatorContracttype.table'), function (Blueprint $table)
		{
			$table->uuid('id')->primary();

			$table->uuid('operator_id')->nullable();
			$table->foreign('operator_id')->references('id')->on(config('operators.models.operator.table'));

			$table->uuid('contracttype_id')->nullable();
			$table->foreign('contracttype_id')->references('id')->on(config('operators.models.contracttype.table'));

			$table->string('level')->nullable();
			$table->unsignedSmallInteger('internal_approval_rating')->nullable();
			$table->decimal('cost_company_hour', 8, 2)->nullable();
			$table->decimal('cost_gross_hour', 8, 2)->nullable();
			$table->decimal('cost_neat_hour', 8, 2)->nullable();

			$table->decimal('cost_company_day', 8, 2)->nullable();
			$table->decimal('cost_gross_day', 8, 2)->nullable();
			$table->decimal('operator_neat_day', 8, 2)->nullable();

			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists(config('operators.models.operatorContracttype.table'));
	}
}
