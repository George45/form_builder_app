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
		Schema::create('form_fields', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('form_id');
			$table->string('field_type');
			$table->string('name');
			$table->text('description')->nullable();
			$table->text('config')->nullable();
			$table->boolean('required');
			$table->timestamps();

			$table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
			$table->foreign('field_type')->references('type')->on('field_types');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('form_fields');
	}
};
