<?php

Route::group(['namespace' => 'Abs\SmsPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'sms-pkg'], function () {

	//SMS TEMPLATES
	Route::get('/sms-templates/get-list', 'SmsTemplateController@getSmsTemplateList')->name('getSmsTemplateList');
	Route::get('/sms-template/get-form-data/{id?}', 'SmsTemplateController@getSmsTemplateFormData')->name('getSmsTemplateFormData');
	Route::post('/sms-template/save', 'SmsTemplateController@saveSmsTemplate')->name('saveSmsTemplate');
	Route::get('/sms/delete/{id}', 'SmsTemplateController@deleteSmsTemplate')->name('deleteSmsTemplate');

	//SMS EVENTS
	Route::get('/sms-events/get-list', 'SmsEventController@getSmsEventList')->name('getSmsEventList');
	Route::get('/sms-event/get-form-data/{id?}', 'SmsEventController@getSmsEventFormData')->name('getSmsEventFormData');
	Route::post('/sms-event/save', 'SmsEventController@saveSmsEvent')->name('saveSmsEvent');
	Route::get('/sms-event/delete/{id}', 'SmsEventController@deleteSmsEvent')->name('deleteSmsEvent');

	//SMS LOGS
	Route::get('/sms-logs/get-list', 'SmsLogController@getSmsLogList')->name('getSmsLogList');

});