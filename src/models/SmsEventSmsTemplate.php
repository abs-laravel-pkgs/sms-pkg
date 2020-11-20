<?php

namespace Abs\SmsPkg;

use Abs\HelperPkg\Traits\SeederTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsEventSmsTemplate extends Model {
	use SeederTrait;
	use SoftDeletes;
	protected $table = 'sms_event_sms_templates';
	protected $fillable = [
		'sms_event_id',
		'sms_template_id',
	];

}
