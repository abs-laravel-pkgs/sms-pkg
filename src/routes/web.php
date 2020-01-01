<?php

Route::group(['namespace' => 'Abs\SmsPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'sms-pkg'], function () {
	Route::get('/smss/get-list', 'SmsController@getSmsList')->name('getSmsList');
	Route::get('/sms/get-form-data/{id?}', 'SmsController@getSmsFormData')->name('getSmsFormData');
	Route::post('/sms/save', 'SmsController@saveSms')->name('saveSms');
	Route::get('/sms/delete/{id}', 'SmsController@deleteSms')->name('deleteSms');

});