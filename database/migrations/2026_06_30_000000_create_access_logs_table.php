<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::create(config('operators.models.accessLog.table'), function (Blueprint $table)
		{
			$table->uuid('id')->primary();

			$table->char('hash', 128)->unique();
			$table->string('badge_code', 64)->index();

			$table->uuid('access_gate_id')->nullable();
			$table->foreign('access_gate_id')
				->references('id')
				->on(config('operators.models.accessGate.table'))
				->onDelete('set null');

			$table->enum('access_type', ['entrata', 'uscita'])->index();
			$table->timestamp('accessed_at')->index();

			$table->text('raw_line')->nullable();
			$table->json('parsed_payload')->nullable();

			$table->string('source_path')->nullable();
			$table->unsignedInteger('source_row_number')->nullable();

			$table->string('processing_status', 32)->nullable()->index();
			$table->timestamp('processed_at')->nullable();
			$table->text('processing_error')->nullable();

			$table->timestamps();
			$table->softDeletes();

			$table->index(['badge_code', 'accessed_at']);
			$table->index(['access_gate_id', 'accessed_at']);
			$table->index(['access_type', 'accessed_at']);
		});
	}

	public function down() : void
	{
		Schema::dropIfExists(config('operators.models.accessLog.table'));
	}
};
