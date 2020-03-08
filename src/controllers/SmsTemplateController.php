<?php

namespace Abs\SmsPkg;
use Abs\AttributePkg\FieldType;
use Abs\BasicPkg\Config;
use Abs\SmsPkg\SmsTemplate;
use Abs\SmsPkg\SmsTemplateParameter;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class SmsTemplateController extends Controller {

	public function __construct() {
		$this->data['theme'] = config('custom.admin_theme');
	}

	public function getSmsTemplateList(Request $request) {
		$sms_list = SmsTemplate::withTrashed()
			->select(
				'sms_templates.*',
				DB::raw('IF(sms_templates.deleted_at IS NULL,"Active","Inactive") as status')
			)
			->where('sms_templates.company_id', Auth::user()->company_id)
			->where(function ($query) use ($request) {
				if (!empty($request->sms_name)) {
					$query->where('sms_templates.name', 'LIKE', '%' . $request->sms_name . '%');
				}
			})
			->orderby('sms_templates.id', 'desc');

		return Datatables::of($sms_list)
			->addColumn('name', function ($sms_list) {
				$status = $sms_list->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $sms_list->name;
			})
			->addColumn('action', function ($sms_list) {
				$edit = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow.svg');
				$edit_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow-active.svg');
				$delete = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-default.svg');
				$delete_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-active.svg');
				return '<a href="#!/sms-pkg/sms-template/edit/' . $sms_list->id . '">
						<img src="' . $edit . '" alt="Edit" class="img-responsive" onmouseover=this.src="' . $edit_active . '" onmouseout=this.src="' . $edit . '" ></a>
						<a href="javascript:;" data-toggle="modal" data-target="#delete_sms"
						onclick="angular.element(this).scope().deleteSmsTemplate(' . $sms_list->id . ')" dusk = "delete-btn" title="Delete">
						<img src="' . $delete . '" alt="Delete" class="img-responsive" onmouseover=this.src="' . $delete_active . '" onmouseout=this.src="' . $delete . '" >
						</a>';
			})
			->make(true);
	}

	public function getSmsTemplateFormData(Request $request) {
		$id = $request->id;
		if (!$id) {
			$sms_template = new SmsTemplate;
			$sms_template->params = [];
			$action = 'Add';
		} else {
			$sms_template = SmsTemplate::withTrashed()->where('id', $id)->with([
				'params',
			])->first();
			$action = 'Edit';
		}
		$this->data['extras'] = [
			'type_list' => collect(Config::where('config_type_id', 5)->select('name', 'id')->get())->prepend(['name' => 'Select Type', 'id' => '']),
			'field_type_list' => collect(FieldType::select('name', 'id')->get())->prepend(['name' => 'Select Field Type', 'id' => '']),
		];
		$this->data['sms_template'] = $sms_template;
		$this->data['action'] = $action;
		$this->data['theme'];

		return response()->json($this->data);
	}

	public function saveSmsTemplate(Request $request) {
		// dd($request->all());
		try {
			$error_messages = [
				'name.required' => 'SMS Template Name is Required',
				'name.unique' => 'SMS Template Name is already taken',
				'description.required' => 'Description is Required',
				'content.required' => 'Content is Required',
			];
			$validator = Validator::make($request->all(), [
				'name' => [
					'required',
					'unique:sms_templates,name,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
				],
				'description' => 'required',
				'content' => 'required',
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			//VALIDATE FOR SMS-TEMPLATE-PARAMETERS
			if (isset($request->params) && !empty($request->params)) {
				$error_messages_1 = [
					'name.required' => 'Name is required',
					'type_id.required' => 'Type is required',
					'default_value.required' => 'Default Value is required',
					'field_type_id.required' => 'Field Type is required',
				];

				foreach ($request->params as $column_key => $param) {
					$validator_1 = Validator::make($param, [
						'name' => 'required',
						'type_id' => 'required',
						'default_value' => 'required',
						'field_type_id' => 'required',
					], $error_messages_1);

					if ($validator_1->fails()) {
						return response()->json(['success' => false, 'errors' => $validator_1->errors()->all()]);
					}
				}
			}

			DB::beginTransaction();
			if (!$request->id) {
				$sms_template = new SmsTemplate;
				$sms_template->created_by_id = Auth::user()->id;
				$sms_template->created_at = Carbon::now();
				$sms_template->updated_at = NULL;
			} else {
				$sms_template = SmsTemplate::withTrashed()->find($request->id);
				$sms_template->updated_by_id = Auth::user()->id;
				$sms_template->updated_at = Carbon::now();
			}
			$sms_template->fill($request->all());
			$sms_template->company_id = Auth::user()->company_id;
			if ($request->status == 'Inactive') {
				$sms_template->deleted_at = Carbon::now();
				$sms_template->deleted_by_id = Auth::user()->id;
			} else {
				$sms_template->deleted_by_id = NULL;
				$sms_template->deleted_at = NULL;
			}
			$sms_template->save();

			//DELETE SMS-TEMPLATE-PARAMETERS
			if (!empty($request->sms_template_removal_ids)) {
				$sms_template_removal_ids = json_decode($request->sms_template_removal_ids, true);
				SmsTemplateParameter::withTrashed()->whereIn('id', $sms_template_removal_ids)->forcedelete();
			}

			if (isset($request->params) && !empty($request->params)) {
				foreach ($request->params as $key => $param) {
					$sms_template_parameter = SmsTemplateParameter::withTrashed()->firstOrNew(['id' => $param['id']]);
					$sms_template_parameter->fill($param);
					if (!is_null($param['display_order'])) {
						$sms_template_parameter->display_order = $param['display_order'];
					}
					$sms_template_parameter->sms_template_id = $sms_template->id;
					if (empty($param['id'])) {
						$sms_template_parameter->created_by_id = Auth::user()->id;
						$sms_template_parameter->created_at = Carbon::now();
					} else {
						$sms_template_parameter->updated_by_id = Auth::user()->id;
						$sms_template_parameter->updated_at = Carbon::now();
					}
					$sms_template_parameter->save();
				}
			}

			DB::commit();
			if (!($request->id)) {
				return response()->json(['success' => true, 'message' => ['SMS Template Details Added Successfully']]);
			} else {
				return response()->json(['success' => true, 'message' => ['SMS Template Details Updated Successfully']]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}
	public function deleteSmsTemplate(Request $request) {
		DB::beginTransaction();
		try {
			SmsTemplate::withTrashed()->where('id', $request->id)->forceDelete();
			DB::commit();
			return response()->json(['success' => true, 'message' => 'SMS Template deleted successfully']);
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}
}
