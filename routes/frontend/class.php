<?php

Route::group(array('prefix' => 'class/{code}','as' => 'class::','namespace' => 'Frontend\AhamClass'), function () {

	Route::get('/', ['as' => 'index', 'uses' => 'ClassController@index']);

	Route::post('leave_feedback', ['as' => 'feedback', 'uses' => 'ClassController@leaveFeedback']);

});

Route::group(array('prefix' => 'series/{slug}','as' => 'series::','namespace' => 'Frontend\GuestSeries'), function () {

	// Route::get('/', ['as' => 'show', 'uses' => 'GuestSeriesController@show']);
	Route::get('/', ['as' => 'show', 'uses' => 'GuestSeriesController@index']);
	Route::get('enroll-to-level/{id}', ['as' => 'enroll-to-level', 'uses' => 'EnrollToLevelController@showEnrollPage']);

	Route::post('enroll-free/{id}', ['as' => 'enroll-free', 'uses' => 'EnrollToLevelController@freeEnroll']);

	Route::post('enroll-as-student/{id}', ['as' => 'enroll-as-student', 'uses' => 'EnrollToLevelController@studentEnroll']);

	Route::post('enroll-as-user/{id}', ['as' => 'enroll-as-user', 'uses' => 'EnrollToLevelController@userEnroll']);

	Route::get('enroll/{episode}', ['as' => 'enroll', 'uses' => 'GuestSeriesController@enroll']);
	Route::get('cancel_enroll/{episode}', ['as' => 'cancel_enroll', 'uses' => 'GuestSeriesController@cancelEnroll']);

});