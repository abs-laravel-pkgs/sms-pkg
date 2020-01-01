<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmsTemplateParametersC extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (!Schema::hasTable('sms_template_parameters')) {
			Schema::create('sms_template_parameters', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('sms_template_id');
				$table->string('name', 191);
				$table->unsignedInteger('type_id');
				$table->string('default_value')->nullable();
				$table->unsignedInteger('field_type_id');
				$table->unsignedMediumInteger('display_order')->default(999);
				$table->unsignedInteger('created_by_id')->nullable();
				$table->unsignedInteger('updated_by_id')->nullable();
				$table->unsignedInteger('deleted_by_id')->nullable();
				$table->timestamps();
				$table->softdeletes();

				$table->foreign('sms_template_id')->references('id')->on('sms_templates')->onDelete('CASCADE')->onUpdate('cascade');

				$table->foreign('type_id')->references('id')->on('configs')->onDelete('CASCADE')->onUpdate('cascade');

				$table->foreign('field_type_id')->references('id')->on('field_types')->onDelete('CASCADE')->onUpdate('cascade');

				$table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
				$table->foreign('updated_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
				$table->foreign('deleted_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sms_template_parameters');
	}
}
