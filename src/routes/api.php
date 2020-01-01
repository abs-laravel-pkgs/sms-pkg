<?php
Route::group(['namespace' => 'Abs\SmsPkg\Api', 'middleware' => ['api']], function () {
	Route::group(['prefix' => 'sms-pkg/api'], function () {
		Route::group(['middleware' => ['auth:api']], function () {
			// Route::get('taxes/get', 'TaxController@getTaxes');
		});
	});
});