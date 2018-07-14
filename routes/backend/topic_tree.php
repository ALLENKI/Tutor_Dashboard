<?php

Route::group(array('prefix' => 'topic_tree', 'namespace' => 'TopicTree', 'as' => 'topic_tree::'), function () {

        Route::group(['prefix' => 'topics'], function()
        {
            Route::get('table',['as' => 'topics.table', 'uses' => 'TopicsController@table']);
            Route::get('table_tree',['as' => 'topics.table_tree', 'uses' => 'TopicsController@tableTree']);
            Route::get('table_d3',['as' => 'topics.table_d3', 'uses' => 'TopicsController@tabled3']);
            Route::get('topic_tree',['as' => 'topics.topic_tree', 'uses' => 'TopicsController@topicTree']);
            Route::get('index_d3',['as' => 'topics.index_d3', 'uses' => 'TopicsController@indexd3']);

            Route::get('/',['as' => 'topics.index', 'uses' => 'TopicsController@index']);
            Route::get('create',['as' => 'topics.create', 'uses' => 'TopicsController@create']);
            Route::post('/',['as' => 'topics.store', 'uses' => 'TopicsController@store']);
            Route::get('show/{id}',['as' => 'topics.show', 'uses' => 'TopicsController@show']);
            Route::get('edit/{id}',['as' => 'topics.edit', 'uses' => 'TopicsController@edit']);
            Route::post('update/{id}',['as' => 'topics.update', 'uses' => 'TopicsController@update']);
            Route::post('update_positions',['as' => 'topics.update_positions', 'uses' => 'TopicsController@updatePositions']);
            Route::delete('delete/{id}',['as' => 'topics.delete', 'uses' => 'TopicsController@destroy']);

            Route::post('certify/{id}',['as' => 'topics.certify', 'uses' => 'TopicsController@certifyTeacher']);

            Route::post('add_prerequisite/{id}',['as' => 'topics.add_prerequisite', 'uses' => 'TopicsController@addPrequisite']);
            Route::post('remove_prerequisite/{requirer}/{topic}',['as' => 'topics.remove_prerequisite', 'uses' => 'TopicsController@removePrequisite']);

            Route::post('upload_dump',['as' => 'topics.upload_dump', 'uses' => 'TopicsController@uploadDump']);

            Route::post('upload_picture/{id}',['as' => 'topics.upload_picture', 'uses' => 'TopicPictureController@uploadPicture']);

            Route::post('upload_cover_picture/{id}',['as' => 'topics.upload_cover_picture', 'uses' => 'TopicPictureController@uploadCoverPicture']);

            Route::post('goals',['as' => 'topics.goals', 'uses' => 'TopicsController@goals']);
        });

        Route::group(['prefix' => 'units'], function()
        {
            Route::post('/',['as' => 'units.store', 'uses' => 'UnitsController@store']);
            Route::get('edit/{id}',['as' => 'units.edit', 'uses' => 'UnitsController@edit']);
            Route::post('update/{id}',['as' => 'units.update', 'uses' => 'UnitsController@update']);
            Route::post('update_positions',['as' => 'units.update_positions', 'uses' => 'UnitsController@updatePositions']);
            Route::delete('delete/{id}',['as' => 'units.delete', 'uses' => 'UnitsController@destroy']);
        });

        Route::group(['prefix' => 'scheduling_rules'], function()
        {
            Route::get('/',['as' => 'scheduling_rules.index', 'uses' => 'SchedulingRulesController@index']);

            Route::get('edit/{id}',['as' => 'scheduling_rules.edit', 'uses' => 'SchedulingRulesController@edit']);

            Route::post('update/{id}',['as' => 'scheduling_rules.update', 'uses' => 'SchedulingRulesController@update']);
        });

        Route::group(['prefix' => 'goals'], function()
        {
            Route::get('table',['as' => 'goals.table', 'uses' => 'GoalsController@table']);
            Route::get('/',['as' => 'goals.index', 'uses' => 'GoalsController@index']);
            Route::post('/',['as' => 'goals.store', 'uses' => 'GoalsController@store']);
            Route::get('show/{id}',['as' => 'goals.show', 'uses' => 'GoalsController@show']);
            Route::get('edit/{id}',['as' => 'goals.edit', 'uses' => 'GoalsController@edit']);
            Route::post('update/{id}',['as' => 'goals.update', 'uses' => 'GoalsController@update']);
            Route::delete('delete/{id}',['as' => 'goals.delete', 'uses' => 'GoalsController@destroy']);
        });
});
