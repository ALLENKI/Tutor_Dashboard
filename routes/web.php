<?php

Route::get('/', ['as' => 'home', 'uses' => 'Frontend\HomeController@home']);
Route::post('notify-chat', ['uses' => 'Frontend\HomeController@notifyChat']);


Route::get('/new-slider', ['as' => 'new-slider', 'uses' => 'Frontend\HomeController@newSlider']);
Route::get('/summer-camp', ['as' => 'new-slider', 'uses' => 'Frontend\HomeController@summerCamp']);
Route::get('/bet', ['as' => 'bet', 'uses' => 'Frontend\HomeController@bet']);
Route::post('/register-for-bet', ['as' => 'register-for-bet', 'uses' => 'Frontend\RegisterForBetController@post']);

Route::get('phantom', ['as' => 'phantom', 'uses' => 'Frontend\HomeController@phantom']);

Route::get('mimic-email', 'Frontend\HomeController@mimicEmail');
Route::get('mimicpush', 'Frontend\HomeController@mimicPush');
Route::get('testcrap', 'Frontend\HomeController@testCrap');

Route::post('get_otp', ['as' => 'get_otp', 'uses' => 'Frontend\OTPController@get']);

Route::get('/profile', ['as' => 'profile', 'uses' => 'Frontend\HomeController@profile']);

Route::get('/api/suggest', ['as' => 'suggest', 'uses' => 'Frontend\HomeController@suggest']);
Route::get('/api/tags', ['as' => 'tags', 'uses' => 'Frontend\HomeController@tags']);

Route::get('/course/{slug}', ['as' => 'course', 'uses' => 'Frontend\HomeController@course']);

Route::get('/categories', ['as' => 'categories', 'uses' => 'Frontend\HomeController@categories']);

Route::get('/class', ['as' => 'class', 'uses' => 'Frontend\HomeController@period']);

Route::get('/tutor_profile', ['as' => 'tutor_profile', 'uses' => 'Frontend\HomeController@tutor_profile']);

/**
 * This file contains routes for user registration etc.,.
 */
include 'frontend.php';
include 'backend.php';
include 'api.php';
include 'mobile_api.php';
include 'ala.php';
include 'v2/web.php';

Route::get('/{name?}', ['as' => 'classes', 'uses' => 'Frontend\Search\SearchController@index'])
->where('name', 'classes[0-9A-Za-z\/\-]*');

