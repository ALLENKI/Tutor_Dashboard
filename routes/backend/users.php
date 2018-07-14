<?php

Route::group(array('prefix' => 'users', 'namespace' => 'Users', 'as' => 'users::'), function () {


        Route::group(['prefix' => 'students'], function()
        {
	        Route::get('table',['as' => 'students.table', 'uses' => 'StudentsController@table']);
	        Route::get('/',['as' => 'students.index', 'uses' => 'StudentsController@index']);

	        Route::get('create',['as' => 'students.create', 'uses' => 'StudentsController@create']);
	        Route::post('/',['as' => 'students.store', 'uses' => 'StudentsController@store']);
	        Route::get('show/{id}',['as' => 'students.show', 'uses' => 'StudentsController@show']);
	        Route::get('edit/{id}',['as' => 'students.edit', 'uses' => 'StudentsController@edit']);
	        Route::post('update/{id}',['as' => 'students.update', 'uses' => 'StudentsController@update']);
	        Route::get('delete',['as' => 'students.delete', 'uses' => 'StudentsController@destroy']);

	        Route::get('credits/{id}',['as' => 'students.credits', 'uses' => 'StudentCreditsController@getCredits']);

	        Route::post('credits/{id}',['as' => 'students.credits', 'uses' => 'StudentCreditsController@postCredits']);

	        Route::get('show_assessment/{id}',['as' => 'students.show_assessment', 'uses' => 'StudentsController@showAssessment']);

	        Route::post('add_assessment/{id}',['as' => 'students.add_assessment', 'uses' => 'StudentsController@addAssessment']);

	        Route::delete('remove_assessment/{id}',['as' => 'students.remove_assessment', 'uses' => 'StudentsController@removeAssessment']);

	        Route::get('graph/{id}',['as' => 'students.graph', 'uses' => 'StudentsController@graph']);

	        Route::post('status/{id}',['as' => 'students.status', 'uses' => 'StudentsController@controlStatus']);

	        Route::get('goals/{id}',['as' => 'students.goals.show', 'uses' => 'StudentGoalsController@getGoals']);

	        Route::post('goals/{id}',['as' => 'students.goals', 'uses' => 'StudentGoalsController@postGoal']);

	        Route::delete('goals/{id}/{goal}',['as' => 'students.goals.delete', 'uses' => 'StudentGoalsController@remove']);

        });

        Route::group(['prefix' => 'teachers'], function()
        {
	        Route::get('table',['as' => 'teachers.table', 'uses' => 'TeachersController@table']);
	        Route::get('/',['as' => 'teachers.index', 'uses' => 'TeachersController@index']);
	        Route::get('create',['as' => 'teachers.create', 'uses' => 'TeachersController@create']);
	        Route::post('/',['as' => 'teachers.store', 'uses' => 'TeachersController@store']);
	        Route::get('show/{id}',['as' => 'teachers.show', 'uses' => 'TeachersController@show']);
	        Route::get('edit/{id}',['as' => 'teachers.edit', 'uses' => 'TeachersController@edit']);
	        Route::post('update/{id}',['as' => 'teachers.update', 'uses' => 'TeachersController@update']);
	        Route::get('delete',['as' => 'teachers.delete', 'uses' => 'TeachersController@destroy']);

	        Route::get('show_certification/{id}',['as' => 'teachers.show_certification', 'uses' => 'TeachersController@showCertification']);

	        Route::post('add_certification/{id}',['as' => 'teachers.add_certification', 'uses' => 'TeachersController@addCertification']);

	        Route::delete('remove_certification/{id}',['as' => 'teachers.remove_certification', 'uses' => 'TeachersController@removeCertification']);

        	Route::get('graph/{id}',['as' => 'teachers.graph', 'uses' => 'TeachersController@graph']);

	        Route::post('status/{id}',['as' => 'teachers.status', 'uses' => 'TeachersController@controlStatus']);

	        Route::get('earnings/{id}',['as' => 'teachers.show_earnings', 'uses' => 'TeacherEarningsController@index']);

	        Route::post('add_payout/{id}',['as' => 'teachers.add_payout', 'uses' => 'TeacherEarningsController@addPayout']);

	        Route::get('public_profile/{id}',['as' => 'teachers.public_profile', 'uses' => 'TeachersController@getPublicProfile']);

	        Route::post('public_profile/{id}',['as' => 'teachers.public_profile', 'uses' => 'TeachersController@updatePublicProfile']);

	        Route::get('manage_commission/{id}',['as' => 'teachers.manage_commission', 'uses' => 'TeachersController@getCommission']);

	        Route::post('manage_commission/{id}',['as' => 'teachers.manage_commission', 'uses' => 'TeachersController@postCommission']);

        });

        Route::group(['prefix' => 'users'], function()
        {

	        Route::get('table',['as' => 'users.table', 'uses' => 'UsersController@table']);
	        Route::get('/',['as' => 'users.index', 'uses' => 'UsersController@index']);
	        Route::get('create',['as' => 'users.create', 'uses' => 'UsersController@create']);
	        Route::post('/',['as' => 'users.store', 'uses' => 'UsersController@store']);
	        Route::get('show/{id}',['as' => 'users.show', 'uses' => 'UsersController@show']);
	        Route::get('edit/{id}',['as' => 'users.edit', 'uses' => 'UsersController@edit']);
	        Route::post('update/{id}',['as' => 'users.update', 'uses' => 'UsersController@update']);
	        Route::get('delete',['as' => 'users.delete', 'uses' => 'UsersController@destroy']);

	        Route::get('toggle_admin/{id}',['as' => 'users.toggle_admin', 'uses' => 'UsersController@toggleAdmin']);

			Route::get('activate/{id}',['as' => 'users.activate', 'uses' => 'UsersController@activate']);

			Route::get('activate_and_impersonate/{id}',['as' => 'users.activate_and_impersonate', 'uses' => 'UsersController@activateAndImpersonate']);

	        Route::get('users/impersonate/{id}',['as' => 'users.impersonate', 'uses' => 'ImpersonationController@impersonate']);

	        Route::get('stop_impersonation',['as' => 'users.stop_impersonation', 'uses' => 'ImpersonationController@stopImpersonating']);

	        Route::get('permissions',['as' => 'permissions.index', 'uses' => 'PermissionsController@index']);

	        Route::get('permissions/manage/{id}',['as' => 'permissions.manage', 'uses' => 'PermissionsController@manage']);
	        Route::post('permissions/manage/{id}',['as' => 'permissions.manage', 'uses' => 'PermissionsController@updatePermissions']);

	        Route::post('upload_avatar/{id}',['as' => 'users.upload_avatar', 'uses' => 'UsersController@uploadAvatar']);

	        Route::get('export',['as' => 'users.export', 'uses' => 'UsersController@export']);

	        Route::get('export-non-enrolled-series/{series}',['as' => 'users.export-non-enrolled-series', 'uses' => 'UsersController@exportNonEnrolledSeries']);

	        Route::get('export-enrolled-series/{series}',['as' => 'users.export-enrolled-series', 'uses' => 'UsersController@exportEnrolledSeries']);
	    });

	    Route::group(['prefix' => 'coupons'], function()
	    {
	    	Route::get('table',['as' => 'coupons.table', 'uses' => 'CouponsController@table']);

	        Route::get('/',['as' => 'coupons.index', 'uses' => 'CouponsController@index']);

			Route::get('create',['as' => 'coupons.create', 'uses' => 'CouponsController@create']);

	        Route::post('/',['as' => 'coupons.store', 'uses' => 'CouponsController@store']);

	        Route::get('show/{id}',['as' => 'coupons.show', 'uses' => 'CouponsController@show']);

	        Route::get('edit/{id}',['as' => 'coupons.edit', 'uses' => 'CouponsController@edit']);

	        Route::post('update/{id}',['as' => 'coupons.update', 'uses' => 'CouponsController@update']);

	        Route::delete('delete/{id}',['as' => 'coupons.delete', 'uses' => 'CouponsController@destroy']);
	    });


	    Route::group(['prefix' => 'coupon_templates'], function()
	    {
	    	Route::get('table',['as' => 'coupon_templates.table', 'uses' => 'CouponTemplatesController@table']);

	        Route::get('/',['as' => 'coupon_templates.index', 'uses' => 'CouponTemplatesController@index']);

			Route::get('create',['as' => 'coupon_templates.create', 'uses' => 'CouponTemplatesController@create']);

	        Route::post('/',['as' => 'coupon_templates.store', 'uses' => 'CouponTemplatesController@store']);

	        Route::get('show/{id}',['as' => 'coupon_templates.show', 'uses' => 'CouponTemplatesController@show']);

	        Route::get('generate/{id}',['as' => 'coupon_templates.generate', 'uses' => 'CouponTemplatesController@generate']);

	        Route::get('edit/{id}',['as' => 'coupon_templates.edit', 'uses' => 'CouponTemplatesController@edit']);

	        Route::post('update/{id}',['as' => 'coupon_templates.update', 'uses' => 'CouponTemplatesController@update']);

	        Route::delete('delete/{id}',['as' => 'coupon_templates.delete', 'uses' => 'CouponTemplatesController@destroy']);

	        Route::post('status/{id}',['as' => 'coupon_templates.status', 'uses' => 'CouponTemplatesController@controlStatus']);

	        Route::post('add_user/{id}',['as' => 'coupon_templates.add_user', 'uses' => 'CouponTemplatesController@addUser']);
	        
	        Route::post('remove_user/{template}/{user}',['as' => 'coupon_templates.remove_user', 'uses' => 'CouponTemplatesController@removeUser']);

	    });

	    Route::group(['prefix' => 'payments'], function()
        {

	    	Route::get('table',['as' => 'payments.table', 'uses' => 'PaymentsController@table']);

            Route::get('/{id}/edit',['as' => 'payments.edit', 'uses' => 'PaymentsController@edit']);
            Route::put('/{id}',['as' => 'payments.update', 'uses' => 'PaymentsController@update']);
            Route::delete('/{id}',['as' => 'payments.destroy','uses' => 'PaymentsController@destroy']);

	    	Route::get('/',['as' => 'payments.index', 'uses' => 'PaymentsController@index']);
            Route::get('export',['as' => 'payments.export', 'uses' => 'PaymentsController@export']);
	    });

	    Route::group(['prefix' => 'bet_applicants'], function()
        {
        	Route::get('table',['as' => 'bet_applicants.table', 'uses' => 'BetApplicantsController@table']);
	        Route::get('/',['as' => 'bet_applicants.index', 'uses' => 'BetApplicantsController@index']);
	        Route::get('export',['as' => 'bet_applicants.export', 'uses' => 'BetApplicantsController@export']);
    	});
});
