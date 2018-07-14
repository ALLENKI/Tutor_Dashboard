<?php


Route::get('/', ['as' => 'home', 'uses' => 'Frontend\HomeController@home']);
Route::get('/contact-us', ['as' => 'contact-us', 'uses' => 'Frontend\HomeController@contact']);
Route::get('/aham-gachibowli', ['as' => 'aham-gachibowli', 'uses' => 'Frontend\HomeController@ahamLocation']);
Route::get('/aham-banjara-hills', ['as' => 'aham-banjara-hills', 'uses' => 'Frontend\HomeController@ahamBanjara']);

Route::get('/invoice/{invoice}/',['as' => 'invoice', 'uses' => 'Frontend\InvoiceController@show']);

/** Contact us **/
Route::get('/contact-us/request/',['as' => 'contact.request', 'uses' => 'Frontend\ContactRequestController@create']);
Route::post('/contact-us/request/',['as' => 'contact.request', 'uses' => 'Frontend\ContactRequestController@post']);



include 'frontend/pages.php';

include 'frontend/onboarding.php';
include 'frontend/settings.php';

include 'frontend/dashboards.php';
include 'frontend/class.php';



