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
        Schema::create(config('operators.models.operators.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on(config('operators.models.operators.table'));

            $table->insignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on(config('accountmanager.models.user.table'));

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create(config('operators.models.clientOperators.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('operators_id')->nullable();
            $table->foreign('operators_id')->references('id')->on(config('operators.models.operators.table'));

            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on(config('operators.models.client.table'));

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
        Schema::dropIfExists(config('operators.models.operators.table'));
    }
}
