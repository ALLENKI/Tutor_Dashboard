<?php

Route::group(array(
    'prefix' => 'tutor', 
    'namespace' => 'Frontend\TeacherDashboard', 
    'as' => 'tutor::'), function () {


    Route::get('{slug}', ['as' => 'profile', 'uses' => 'ProfileController@profile']);

    Route::get('{slug}/certification', ['as' => 'certification', 'uses' => 'ProfileController@certification']);
    Route::get('{slug}/graph', ['as' => 'graph', 'uses' => 'ProfileController@graph']);

    Route::group(array(
        'prefix' => '{slug}', 
        'middleware' => ['sentinel','teacher']
    ), function () {

        // Check invitations
        Route::get('invitations', ['as' => 'invitations.index', 'uses' => 'InvitationsController@index']);

        Route::get('invitations/{id}', ['as' => 'invitations.show', 'uses' => 'InvitationsController@show']);

        // Accept, Reject, Postpone Invitation

        Route::get('invitations/{id}/accept', ['as' => 'invitations.accept', 'uses' => 'InvitationsController@accept']);

        Route::get('invitations/{id}/reject', ['as' => 'invitations.reject', 'uses' => 'InvitationsController@reject']);

        Route::post('invitations/{id}/propose', ['as' => 'invitations.propose', 'uses' => 'InvitationsController@propose']);


    });

});
