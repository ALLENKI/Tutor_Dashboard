<?php


Route::group(array(
    'prefix' => 'dashboard/student',
    'namespace' => 'Dashboard\Student',
    'middleware' => ['sentinel','student'],
    'as' => 'student::'), function () {


    Route::get('/',['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/calendar',['as' => 'ahamCalendar', 'uses' => 'HomeController@classesForCalendar']);
	Route::get('/demo-video',['as' => 'demo-video', 'uses' => 'HomeController@demo']);
    Route::get('/snippet',['as' => 'snippet', 'uses' => 'HomeController@snippet']);

    Route::get('/assessment',['as' => 'assessment', 'uses' => 'HomeController@assessment']);
	Route::get('/graph',['as' => 'graph', 'uses' => 'HomeController@graph']);

    /** Credits Related **/

    Route::get('/credits',['as' => 'credits.index', 'uses' => 'CreditsController@index']);
    Route::get('/credits/statement',['as' => 'credits.statement', 'uses' => 'CreditsController@statement']);
    Route::get('/credits/add',['as' => 'credits.add', 'uses' => 'CreditsController@add']);
    Route::post('/credits/pay',['as' => 'credits.pay', 'uses' => 'CreditsController@pay']);
    Route::post('/credits/payment_success',['as' => 'credits.payment_success', 'uses' => 'CreditsController@paymentSuccess']);

    /** Class Page **/

    Route::get('/class/{code}/details',['as' => 'class.show', 'uses' => 'ClassController@show']);
    Route::get('/class/{code}/course-details',['as' => 'class.course-details', 'uses' => 'ClassController@courseDetails']);
    Route::get('/class/{code}/goals',['as' => 'class.goals', 'uses' => 'ClassController@goals']);
    Route::get('/class/{code}/prerequisites',['as' => 'class.prerequisites', 'uses' => 'ClassController@prerequisites']);

    Route::post('/class/{code}/attachments',['as' => 'class.attachments', 'uses' => 'ClassController@attachments']);

    Route::post('/class/{code}/feedback',['as' => 'class.feedback', 'uses' => 'ClassController@feedback']);

    /** Upcoming Classes **/

    Route::get('/classes/enrolled',['as' => 'classes.enrolled', 'uses' => 'ClassesController@enrolled']);

    Route::get('/classes/upcoming',['as' => 'classes.upcoming', 'uses' => 'ClassesController@upcoming']);

    Route::get('/classes/on-going',['as' => 'classes.on-going', 'uses' => 'ClassesController@onGoing']);

    Route::get('/classes/completed',['as' => 'classes.completed', 'uses' => 'ClassesController@completed']);

    Route::get('/classes/browse',['as' => 'classes.browse', 'uses' => 'ClassesController@browse']);

    Route::get('/classes/catalog',['as' => 'courses.catalog', 'uses' => 'CoursesController@catalog']);

    /** Recommended **/

    Route::get('/classes/recommended',['as' => 'classes.recommended', 'uses' => 'ClassesController@recommended']);

    Route::get('/classes/{id}/not_interested',['as' => 'classes.not_interested', 'uses' => 'ClassesController@markAsNotInterested']);

    /** Course Page **/

    Route::get('/courses/{slug}',['as' => 'courses.show', 'uses' => 'CoursesController@show']);
    Route::get('/courses/{slug}/goals',['as' => 'courses.goals', 'uses' => 'CoursesController@goals']);
    Route::get('/courses/{slug}/prerequisites',['as' => 'courses.prerequisites', 'uses' => 'CoursesController@prerequisites']);

    Route::post('/courses/for_goal_page/{id}',['as' => 'courses.for_goal_page', 'uses' => 'CoursesController@forGoalPage']);



     /** Request for class **/
    Route::get('/courses/request/{id}',['as' => 'courses.request', 'uses' => 'RequestController@create']);
    Route::post('/courses/request/{id}',['as' => 'courses.request', 'uses' => 'RequestController@post']);

    /** Settings **/

    Route::get('settings/profile',['as' => 'settings.profile', 'uses' => 'SettingsController@getProfile']);
    Route::post('settings/profile',['as' => 'settings.profile', 'uses' => 'SettingsController@updateProfile']);

    Route::get('settings/password',['as' => 'settings.password', 'uses' => 'SettingsController@getPassword']);
    Route::post('settings/password',['as' => 'settings.password', 'uses' => 'SettingsController@updatePassword']);

    Route::get('settings/manage',['as' => 'settings.manage', 'uses' => 'SettingsController@getManage']);
    Route::post('settings/manage',['as' => 'settings.manage', 'uses' => 'SettingsController@updateManage']);


    /**** GOALS *****/

    Route::get('/goals',['as' => 'goals.index', 'uses' => 'GoalsController@index']);
    Route::get('/goals/{slug}',['as' => 'goals.show', 'uses' => 'GoalsController@show']);
    Route::post('/goals/add/{slug}',['as' => 'goals.add', 'uses' => 'GoalsController@add']);
    Route::post('/goals/remove/{slug}',['as' => 'goals.remove', 'uses' => 'GoalsController@remove']);


    /** Enrollment **/
    Route::get('/enrollment/{id}',['as' => 'enrollment.show', 'uses' => 'EnrollmentsController@create']);
    Route::post('/enrollment/{id}',['as' => 'enrollment.store', 'uses' => 'EnrollmentsController@store']);
    Route::post('/enrollment/{id}/ghost',['as' => 'enrollment.enroll_as_ghost', 'uses' => 'EnrollmentsController@enrollAsGhost']);
    Route::post('/enrollment/{id}/free',['as' => 'enrollment.free_enroll', 'uses' => 'EnrollmentsController@freeEnroll']);
    Route::get('/cancel_enrollment_modal/{id}',['as' => 'enrollment.cancel_modal', 'uses' => 'EnrollmentsController@cancelModal']);
    Route::post('/cancel_enrollment/{id}',['as' => 'enrollment.cancel', 'uses' => 'EnrollmentsController@cancel']);
});


Route::group(array(
    'prefix' => 'dashboard/teacher',
    'namespace' => 'Dashboard\Teacher',
    'middleware' => ['sentinel','teacher'],
    'as' => 'teacher::'), function () {


    Route::get('/',['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/calendar',['as' => 'ahamCalendar', 'uses' => 'HomeController@classesForCalendar']);

    /** Class Page **/

    Route::get('/class/{code}/details',['as' => 'class.show', 'uses' => 'ClassController@show']);
    Route::get('/class/{code}/learners',['as' => 'class.learners', 'uses' => 'ClassController@learners']);
    Route::get('/class/{code}/course-details',['as' => 'class.course-details', 'uses' => 'ClassController@courseDetails']);
    Route::get('/class/{code}/goals',['as' => 'class.goals', 'uses' => 'ClassController@goals']);
    Route::get('/class/{code}/prerequisites',['as' => 'class.prerequisites', 'uses' => 'ClassController@prerequisites']);

    Route::post('/class/{code}/attachments',['as' => 'class.attachments', 'uses' => 'ClassController@attachments']);

    Route::post('/class/{code}/feedback',['as' => 'class.feedback', 'uses' => 'ClassController@feedback']);

    Route::get('/classes/upcoming',['as' => 'classes.upcoming', 'uses' => 'ClassesController@upcoming']);

    Route::get('/classes/scheduled',['as' => 'classes.scheduled', 'uses' => 'ClassesController@scheduled']);

    Route::get('/classes/on-going',['as' => 'classes.on-going', 'uses' => 'ClassesController@onGoing']);

    Route::get('/classes/completed',['as' => 'classes.completed', 'uses' => 'ClassesController@completed']);

    Route::get('/classes/catalog',['as' => 'courses.catalog', 'uses' => 'CoursesController@catalog']);
    /** Request for class **/
    Route::get('/courses/interested/{id}',['as' => 'courses.interested', 'uses' => 'InterestedController@create']);
    Route::post('/courses/interested/{id}',['as' => 'courses.interested', 'uses' => 'InterestedController@post']);

    Route::get('/courses/{slug}',['as' => 'courses.show', 'uses' => 'CoursesController@show']);
    Route::get('/courses/{slug}/goals',['as' => 'courses.goals', 'uses' => 'CoursesController@goals']);


    Route::get('settings/profile',['as' => 'settings.profile', 'uses' => 'SettingsController@getProfile']);
    Route::post('settings/profile',['as' => 'settings.profile', 'uses' => 'SettingsController@updateProfile']);

    Route::get('settings/public_profile',['as' => 'settings.public_profile', 'uses' => 'SettingsController@getPublicProfile']);
    Route::post('settings/public_profile',['as' => 'settings.public_profile', 'uses' => 'SettingsController@updatePublicProfile']);

    Route::get('settings/password',['as' => 'settings.password', 'uses' => 'SettingsController@getPassword']);
    Route::post('settings/password',['as' => 'settings.password', 'uses' => 'SettingsController@updatePassword']);

   Route::get('settings/mobile',['as' => 'settings.mobile', 'uses' => 'SettingsController@getMobile']);
    Route::post('settings/mobile',['as' => 'settings.mobile', 'uses' => 'SettingsController@updateMobile']);

    Route::get('/certification',['as' => 'certification', 'uses' => 'HomeController@certification']);
    Route::get('/graph',['as' => 'graph', 'uses' => 'HomeController@graph']);

    Route::get('/courses/{slug}',['as' => 'courses.show', 'uses' => 'CoursesController@show']);

    Route::get('/payments',['as' => 'payments', 'uses' => 'PaymentsController@index']);

    Route::get('/availability',['as' => 'availability', 'uses' => 'AvailabilityController@index']);
    Route::post('/availability',['as' => 'availability', 'uses' => 'AvailabilityController@store']);
    Route::post('/availability/ignore_calendar',['as' => 'availability.ignore_calendar', 'uses' => 'AvailabilityController@updateIgnoreCalendar']);
    Route::delete('/availability/{id}',['as' => 'availability.delete', 'uses' => 'AvailabilityController@destroy']);

    Route::group(array('prefix' => 'invitations'), function () {

        // Show all invitations
        Route::get('/all',['as' => 'invitations.all', 'uses' => 'InvitationsController@all']);

        // Show new invitations - pending state
        Route::get('/new',['as' => 'invitations.new', 'uses' => 'InvitationsController@newInvitations']);

        // Show pending invitations - acccepted state
        Route::get('/pending',['as' => 'invitations.pending', 'uses' => 'InvitationsController@pending']);

        // Show awarded invitations - awarded state
        Route::get('/awarded',['as' => 'invitations.awarded', 'uses' => 'InvitationsController@awarded']);

        Route::get('{id}/accept-modal',['as' => 'invitations.accept_modal', 'uses' => 'InvitationsController@acceptModal']);

        Route::get('{id}/decline-modal',['as' => 'invitations.decline_modal', 'uses' => 'InvitationsController@declineModal']);

        Route::post('{id}/accept',['as' => 'invitations.accept', 'uses' => 'InvitationsController@accept']);

        Route::post('{id}/decline',['as' => 'invitations.decline', 'uses' => 'InvitationsController@decline']);

    });


});


Route::group(array(
    'prefix' => 'dashboard/comments/{class}',
    'namespace' => 'Dashboard',
    'middleware' => ['sentinel'],
    'as' => 'user::'), function () {

    Route::get('/',['as' => 'comments.index', 'uses' => 'CommentsController@index']);
    Route::post('/',['as' => 'comments.store', 'uses' => 'CommentsController@store']);
    Route::get('show/{comment}',['as' => 'comments.show', 'uses' => 'CommentsController@show']);
    Route::get('edit/{comment}',['as' => 'comments.edit', 'uses' => 'CommentsController@edit']);
    Route::post('update/{comment}',['as' => 'comments.update', 'uses' => 'CommentsController@update']);
    Route::delete('delete/{comment}',['as' => 'comments.delete', 'uses' => 'CommentsController@destroy']);

});
