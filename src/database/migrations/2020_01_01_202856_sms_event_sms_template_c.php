<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmsEventSmsTemplateC extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (!Schema::hasTable('sms_event_sms_template')) {
			Schema::create('sms_event_sms_template', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('company_id');
				$table->unsignedInteger('sms_event_id');
				$table->unsignedInteger('sms_template_id');

				$table->unsignedInteger('created_by_id')->nullable();
				$table->unsignedInteger('updated_by_id')->nullable();
				$table->unsignedInteger('deleted_by_id')->nullable();
				$table->timestamps();
				$table->softdeletes();

				$table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE')->onUpdate('cascade');
				$table->foreign('sms_event_id')->references('id')->on('configs')->onDelete('CASCADE')->onUpdate('cascade');
				$table->foreign('sms_template_id')->references('id')->on('sms_templates')->onDelete('CASCADE')->onUpdate('cascade');

				$table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
				$table->foreign('updated_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
				$table->foreign('deleted_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

				$table->unique(["company_id", "sms_event_id"]);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sms_event_sms_template');
	}
}
