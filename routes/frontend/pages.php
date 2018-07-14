<?php

Route::group(array('as' => 'pages::','namespace' => 'Frontend\Content'), function () {


	Route::get('join-as-a-tutor', ['as' => 'join-as-a-tutor', 'uses' => 'PagesController@joinAsTutor']);

	Route::get('join-as-a-student', ['as' => 'join-as-a-student', 'uses' => 'PagesController@joinAsStudent']);

	Route::get('privacy-policy', ['as' => 'privacy-policy', 'uses' => 'PagesController@privacyPolicy']);

	Route::get('about-aham', ['as' => 'about-aham', 'uses' => 'PagesController@aboutAham']);

	Route::get('terms', ['as' => 'terms', 'uses' => 'PagesController@terms']);

	Route::get('pricing', ['as' => 'pricing', 'uses' => 'PagesController@pricing']);

	Route::get('tutor/{slug}', ['as' => 'tutor.public', 'uses' => 'TutorsController@show']);

});