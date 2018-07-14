<?php

Route::group(array('prefix' => 'locations_mgmt', 'namespace' => 'LocationsMgmt', 'as' => 'locations_mgmt::'), function () {

    Route::group(['prefix' => 'cities'], function()
    {
        Route::get('/',['as' => 'cities.index', 'uses' => 'CitiesController@index']);
        Route::post('/',['as' => 'cities.store', 'uses' => 'CitiesController@store']);
        Route::get('show/{id}',['as' => 'cities.show', 'uses' => 'CitiesController@show']);
        Route::get('edit/{id}',['as' => 'cities.edit', 'uses' => 'CitiesController@edit']);
        Route::post('update/{id}',['as' => 'cities.update', 'uses' => 'CitiesController@update']);
        Route::delete('delete/{id}',['as' => 'cities.delete', 'uses' => 'CitiesController@destroy']);

        
    });

    Route::group(['prefix' => 'localities'], function()
    {
        Route::get('/',['as' => 'localities.index', 'uses' => 'LocalitiesController@index']);
        Route::post('/',['as' => 'localities.store', 'uses' => 'LocalitiesController@store']);
        Route::get('show/{id}',['as' => 'localities.show', 'uses' => 'LocalitiesController@show']);
        Route::get('edit/{id}',['as' => 'localities.edit', 'uses' => 'LocalitiesController@edit']);
        Route::post('update/{id}',['as' => 'localities.update', 'uses' => 'LocalitiesController@update']);
        Route::delete('delete/{id}',['as' => 'localities.delete', 'uses' => 'LocalitiesController@destroy']);
        
    });

    Route::group(['prefix' => 'classrooms'], function()
    {
        Route::get('create/{id}',['as' => 'classrooms.create', 'uses' => 'ClassroomsController@create']);
        Route::post('/',['as' => 'classrooms.store', 'uses' => 'ClassroomsController@store']);
        Route::get('show/{id}',['as' => 'classrooms.show', 'uses' => 'ClassroomsController@show']);
        Route::get('edit/{id}',['as' => 'classrooms.edit', 'uses' => 'ClassroomsController@edit']);

        Route::post('update/{id}',['as' => 'classrooms.update', 'uses' => 'ClassroomsController@update']);

        Route::delete('delete/{id}',['as' => 'classrooms.delete', 'uses' => 'ClassroomsController@destroy']);

        Route::post('add_slot/{id}',['as' => 'classrooms.add_slot', 'uses' => 'ClassroomsController@addSlot']);

        Route::delete('delete_slot/{id}',['as' => 'classrooms.remove_slot', 'uses' => 'ClassroomsController@removeSlot']);

    });

    Route::group(['prefix' => 'day_types'], function()
    {
        Route::get('/',['as' => 'day_types.index', 'uses' => 'DayTypesController@index']);
        Route::post('/',['as' => 'day_types.store', 'uses' => 'DayTypesController@store']);
        Route::get('show/{id}',['as' => 'day_types.show', 'uses' => 'DayTypesController@show']);
        Route::get('edit/{id}',['as' => 'day_types.edit', 'uses' => 'DayTypesController@edit']);
        Route::post('update/{id}',['as' => 'day_types.update', 'uses' => 'DayTypesController@update']);
        Route::delete('delete/{id}',['as' => 'day_types.delete', 'uses' => 'DayTypesController@destroy']);

        Route::post('add_slot',['as' => 'day_types.add_slot', 'uses' => 'DayTypesController@addSlot']);
        Route::delete('delete_slot/{id}',['as' => 'day_types.delete_slot', 'uses' => 'DayTypesController@deleteSlot']);
    });

    Route::group(['prefix' => 'locations'], function()
    {
        Route::get('table',['as' => 'locations.table', 'uses' => 'LocationsController@table']);
        Route::get('/',['as' => 'locations.index', 'uses' => 'LocationsController@index']);
        Route::get('create',['as' => 'locations.create', 'uses' => 'LocationsController@create']);
        Route::post('/',['as' => 'locations.store', 'uses' => 'LocationsController@store']);
        Route::get('show/{id}',['as' => 'locations.show', 'uses' => 'LocationsController@show']);
        Route::get('edit/{id}',['as' => 'locations.edit', 'uses' => 'LocationsController@edit']);
        Route::post('update/{id}',['as' => 'locations.update', 'uses' => 'LocationsController@update']);
        Route::delete('delete/{id}',['as' => 'locations.delete', 'uses' => 'LocationsController@destroy']);

        Route::post('add_calendar',['as' => 'locations.add_calendar', 'uses' => 'LocationsController@addCalendar']);

        Route::delete('delete_calendar/{id}',['as' => 'locations.delete_calendar', 'uses' => 'LocationsController@deleteCalendar']);

    });
});
