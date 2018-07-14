<?php

Route::group(array('prefix' => 'auth', 'namespace' => 'Frontend', 'as' => 'auth::'), function () {

    Route::get('login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@postLogin']);
    Route::get('google', ['as' => 'google', 'uses' => 'AuthController@google']);

    Route::get('register', ['as' => 'register', 'uses' => 'AuthController@getRegister']);
    Route::post('register', ['as' => 'register', 'uses' => 'AuthController@postRegister']);

    # Teacher Registration
    Route::get('register-as-a-teacher', ['as' => 'register-as-a-teacher', 'uses' => 'RegisterAsTeacherController@get']);

    Route::post('register-as-a-teacher', ['as' => 'register-as-a-teacher', 'uses' => 'RegisterAsTeacherController@post']);

    # Student Registration
    Route::get('register-as-a-student', ['as' => 'register-as-a-student', 'uses' => 'RegisterAsStudentController@get']);
    
    Route::post('register-as-a-student', ['as' => 'register-as-a-student', 'uses' => 'RegisterAsStudentController@post']);

    Route::get('activate/{user}/{code}', ['as' => 'activate', 'uses' => 'AuthController@activate']);

    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

    # Forgot Password
    Route::get('forgot-password', array('as' => 'forgot-password', 'uses' => 'AuthController@getForgotPassword'));
    Route::post('forgot-password', 'AuthController@postForgotPassword');

    # Forgot Password Confirmation
    Route::get('reset-password/{user_id}/{passwordResetCode}', array('as' => 'reset-password', 'uses' => 'AuthController@getResetPassword'));
    Route::post('reset-password/{user_id}/{passwordResetCode}', 'AuthController@postResetPassword');
});
