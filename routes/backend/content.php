<?php

Route::group(array('prefix' => 'content', 'as' => 'content::','namespace' => 'Content'), function () {

    Route::group(['prefix' => 'pages'], function()
    {
        Route::get('/',['as' => 'pages.index', 'uses' => 'PagesController@index']);
        Route::post('/',['as' => 'pages.store', 'uses' => 'PagesController@store']);
        Route::get('show/{id}',['as' => 'pages.show', 'uses' => 'PagesController@show']);
        Route::get('edit/{id}',['as' => 'pages.edit', 'uses' => 'PagesController@edit']);
        Route::post('update/{id}',['as' => 'pages.update', 'uses' => 'PagesController@update']);
        Route::delete('delete/{id}',['as' => 'pages.delete', 'uses' => 'PagesController@destroy']);   
    });



    Route::group(['prefix' => 'seo'], function()
    {
        Route::get('/',['as' => 'seo.index', 'uses' => 'SEOController@index']);
        Route::post('/',['as' => 'seo.store', 'uses' => 'SEOController@store']);
        Route::get('show/{id}',['as' => 'seo.show', 'uses' => 'SEOController@show']);
        Route::get('edit/{id}',['as' => 'seo.edit', 'uses' => 'SEOController@edit']);
        Route::post('update/{id}',['as' => 'seo.update', 'uses' => 'SEOController@update']);
        Route::delete('delete/{id}',['as' => 'seo.delete', 'uses' => 'SEOController@destroy']);   
    });

    Route::get('otps',['as' => 'otps.index', 'uses' => 'OTPsController@index']);

    Route::get('revisions/table',['as' => 'revisions.table', 'uses' => 'RevisionsController@table']);
    Route::get('revisions',['as' => 'revisions.index', 'uses' => 'RevisionsController@index']);

    Route::get('sms/table',['as' => 'sms.table', 'uses' => 'SMSController@table']);
    Route::get('sms',['as' => 'sms.index', 'uses' => 'SMSController@index']);


    Route::group(['prefix' => 'settings'], function()
    {
        Route::get('/',['as' => 'settings.index', 'uses' => 'SettingsController@index']);
        Route::post('/',['as' => 'settings.store', 'uses' => 'SettingsController@store']);
        Route::get('show/{id}',['as' => 'settings.show', 'uses' => 'SettingsController@show']);
        Route::get('edit/{id}',['as' => 'settings.edit', 'uses' => 'SettingsController@edit']);
        Route::post('update/{id}',['as' => 'settings.update', 'uses' => 'SettingsController@update']);
        Route::delete('delete/{id}',['as' => 'settings.delete', 'uses' => 'SettingsController@destroy']);

        
    });

    
});