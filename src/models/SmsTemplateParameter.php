<?php

namespace Abs\SmsPkg;

use Abs\AttributePkg\FieldType;
use Abs\HelperPkg\Traits\SeederTrait;
use Abs\SmsPkg\SmsTemplate;
use App\Company;
use App\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplateParameter extends Model {
	use SeederTrait;
	use SoftDeletes;
	protected $table = 'sms_template_parameters';
	protected $fillable = [
		'sms_template_id',
		'name',
		'type_id',
		'default_value',
		'field_type_id',
		// 'display_order',
	];

	public static function createFromObject($record_data) {

		$errors = [];
		$company = Company::where('code', $record_data->company)->first();
		if (!$company) {
			dump('Invalid Company : ' . $record_data->company);
			return;
		}

		$admin = $company->admin();
		if (!$admin) {
			dump('Default Admin user not found');
			return;
		}

		$type = Config::where('name', $record_data->type)->where('config_type_id', 89)->first();
		if (!$type) {
			$errors[] = 'Invalid Tax Type : ' . $record_data->type;
		}

		if (count($errors) > 0) {
			dump($errors);
			return;
		}

		$record = self::firstOrNew([
			'company_id' => $company->id,
			'name' => $record_data->tax_name,
		]);
		$record->type_id = $type->id;
		$record->created_by_id = $admin->id;
		$record->save();
		return $record;
	}

	public static function createMultipleFromArray($records) {
		foreach ($records as $data) {
			$sms_template = SmsTemplate::where([
				'name' => $data['sms_template'],
			])->first();
			$data['sms_template_id'] = $sms_template->id;

			$type = Config::where([
				'name' => $data['type_id'],
				'config_type_id' => 8,
			])->first();
			$data['type_id'] = $type->id;

			$field_type = FieldType::where([
				'name' => $data['field_type_id'],
			])->first();
			$data['field_type_id'] = $field_type->id;

			unset($data['sms_template']);
			$record = self::firstOrNew([
				'sms_template_id' => $data['sms_template_id'],
				'name' => $data['name'],
				'type_id' => $data['type_id'],
			]);
			$record->fill($data);
			$record->save();

		}
	}

}
