<?php 

Route::group(array('prefix' => 'settings', 'as' => 'settings::'), function () {


	Route::get('profile', ['as' => 'profile', 'uses' => 'Frontend\Profile\SettingsController@getSettings']);

	Route::post('profile', ['as' => 'profile', 'uses' => 'Frontend\Profile\SettingsController@postSettings']);

	Route::get('password', ['as' => 'password', 'uses' => 'Frontend\Profile\SettingsController@getPassword']);

	Route::post('update-password', ['as' => 'update-password', 'uses' => 'Frontend\Profile\SettingsController@updatePassword']);

	Route::post('upload_avatar', ['as' => 'upload_avatar', 'uses' => 'Frontend\Profile\SettingsController@uploadAvatar']);

	Route::get('stop_impersonation',['as' => 'stop_impersonation', 'uses' => 'Backend\Users\ImpersonationController@stopImpersonating']);

});
