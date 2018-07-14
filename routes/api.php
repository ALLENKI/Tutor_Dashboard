<?php

Route::group(['namespace' => 'Backend',
				'as' => 'api::',
				'prefix' => 'api'], 
function()
{

	Route::get('get_available_slots',[
			'as' => 'get_available_slots',
			'uses' => "ApiController@getAvailableSlots"
		]);


	Route::get('get_available_slots_for_episodes',[
			'as' => 'get_available_slots_for_episodes',
			'uses' => "ApiController@getAvailableSlotsForEpisodes"
		]);

	Route::get('get_topic_rules/{id}',[
			'as' => 'get_topic_rules',
			'uses' => "ApiController@getTopicRules"
		]);

	Route::get('get_topic_details/{id}',[
			'as' => 'get_topic_details',
			'uses' => "ApiController@getTopicDetails"
		]);

});