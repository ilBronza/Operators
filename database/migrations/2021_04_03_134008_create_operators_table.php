<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('operators.models.operator.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('slug')->nullable();
            $table->string('code', 64)->nullable();

            $table->string('vat', 12)->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on(config('accountmanager.models.user.table'));

            $table->uuid('userdata_id')->nullable();
            $table->foreign('userdata_id')->references('id')->on(config('accountmanager.models.userdata.table'));

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table(config('operators.models.operator.table'), function (Blueprint $table) {
            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on(config('operators.models.operator.table'));

        });

        Schema::create(config('operators.models.skill.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('slug')->nullable();
            $table->string('name');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table(config('operators.models.skill.table'), function (Blueprint $table) {        
            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on(config('operators.models.skill.table'));
        });

        Schema::create(config('operators.models.contracttype.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('slug')->nullable();
            $table->string('name');

            $table->text('description')->nullable();
            $table->string('istat_code')->nullable();

            $table->decimal('cost_company_hour', 8, 2)->nullable();
            $table->decimal('cost_gross_hour', 8, 2)->nullable();
            $table->decimal('cost_neat_hour', 8, 2)->nullable();

            $table->decimal('cost_company_day', 8, 2)->nullable();
            $table->decimal('cost_gross_day', 8, 2)->nullable();
            $table->decimal('cost_neat_day', 8, 2)->nullable();

            $table->decimal('cost_charge_coefficient', 5, 2)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create(config('operators.models.clientOperator.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('operator_id')->nullable();
            $table->foreign('operator_id')->references('id')->on(config('operators.models.operator.table'));

            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on(config('clients.models.client.table'));

            $table->unsignedBigInteger('employment_id')->nullable();
            $table->foreign('employment_id')->references('id')->on(config('operators.models.employment.table'));

            $table->uuid('contracttype_id')->nullable();
            $table->foreign('contracttype_id')->references('id')->on(config('operators.models.contracttype.table'));

            $table->string('level')->nullable();
            $table->string('social_security_institution')->nullable();
            $table->string('social_security_code')->nullable();

            $table->decimal('cost_company_hour', 8, 2)->nullable();
            $table->decimal('cost_gross_hour', 8, 2)->nullable();
            $table->decimal('cost_neat_hour', 8, 2)->nullable();

            $table->decimal('cost_company_day', 8, 2)->nullable();
            $table->decimal('cost_gross_day', 8, 2)->nullable();
            $table->decimal('cost_neat_day', 8, 2)->nullable();

            $table->decimal('cost_charge_coefficient', 5, 2)->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('operators.models.operatorSkill.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('operator_id')->nullable();
            $table->foreign('operator_id')->references('id')->on(config('operators.models.operator.table'));

            $table->uuid('skill_id')->nullable();
            $table->foreign('skill_id')->references('id')->on(config('operators.models.skill.table'));

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

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
        Schema::dropIfExists(config('operators.models.clientOperators.table'));
        Schema::dropIfExists(config('operators.models.operatorSkill.table'));
        Schema::dropIfExists(config('operators.models.contracttype.table'));
        Schema::dropIfExists(config('operators.models.skill.table'));
        Schema::dropIfExists(config('operators.models.operator.table'));
    }
}
