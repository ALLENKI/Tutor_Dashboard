<?php

Route::group(array('prefix' => 'classes_mgmt', 'namespace' => 'ClassesMgmt', 'as' => 'classes_mgmt::'), function () {

    Route::group(['prefix' => 'classes'], function()
    {
        Route::get('table',['as' => 'classes.table', 'uses' => 'ClassesController@table']);
        Route::get('/',['as' => 'classes.index', 'uses' => 'ClassesController@index']);
        Route::get('create',['as' => 'classes.create', 'uses' => 'ClassesController@create']);
        Route::post('/',['as' => 'classes.store', 'uses' => 'ClassesController@store']);
        Route::get('show/{id}',['as' => 'classes.show', 'uses' => 'ClassesController@show']);
        Route::get('edit/{id}',['as' => 'classes.edit', 'uses' => 'ClassesController@edit']);
        Route::post('update/{id}',['as' => 'classes.update', 'uses' => 'ClassesController@update']);
        Route::delete('delete/{id}',['as' => 'classes.delete', 'uses' => 'ClassesController@destroy']);

        Route::get('create-repeat/{id}',['as' => 'classes.create-repeat', 'uses' => 'ClassesController@createRepeat']);
        Route::post('store-repeat/{id}',['as' => 'classes.store-repeat', 'uses' => 'ClassesController@storeRepeat']);
        

        Route::get('schedule_modal/{class}/{unit}',['as' => 'classes.schedule_modal', 'uses' => 'ClassScheduleController@scheduleModal']);
        Route::post('schedule_modal/{class}/{unit}',['as' => 'classes.schedule_modal', 'uses' => 'ClassScheduleController@scheduleModalPost']);

        Route::get('enrollment_cutoff_modal/{class}',['as' => 'classes.enrollment_cutoff_modal', 'uses' => 'ClassScheduleController@enrollmentCutoffModal']);
        Route::post('enrollment_cutoff_modal/{class}',['as' => 'classes.enrollment_cutoff_modal', 'uses' => 'ClassScheduleController@enrollmentCutoffModalPost']);

        Route::get('schedule_cutoff_modal/{class}',['as' => 'classes.schedule_cutoff_modal', 'uses' => 'ClassScheduleController@scheduleCutoffModal']);
        Route::post('schedule_cutoff_modal/{class}',['as' => 'classes.schedule_cutoff_modal', 'uses' => 'ClassScheduleController@scheduleCutoffModalPost']);

        Route::get('unitdone_modal/{class}/{unit}',['as' => 'classes.unitdone_modal', 'uses' => 'ClassStatusController@unitDoneModal']);
        Route::post('unitdone_modal/{class}/{unit}',['as' => 'classes.unitdone_modal', 'uses' => 'ClassStatusController@unitDoneModalPost']);

        Route::get('assign_classroom/{class}/{unit}',['as' => 'classes.assign_classroom', 'uses' => 'ClassScheduleController@assignClassroomModal']);
        Route::post('assign_classroom/{class}/{unit}',['as' => 'classes.assign_classroom', 'uses' => 'ClassScheduleController@assignClassroom']);

        Route::post('open_for_enrollment/{class}',['as' => 'classes.open_for_enrollment', 'uses' => 'ClassScheduleController@openForEnrollment']);

        Route::post('schedule/{class}',['as' => 'classes.schedule', 'uses' => 'ClassScheduleController@schedule']);


        Route::post('complete/{class}',['as' => 'classes.complete', 'uses' => 'ClassScheduleController@complete']);

        Route::post('session/{class}',['as' => 'classes.session', 'uses' => 'ClassScheduleController@session']);

        Route::post('reinitiate/{class}',['as' => 'classes.reinitiate', 'uses' => 'ClassScheduleController@reInitiate']);

        Route::get('cancel_modal/{class}',['as' => 'classes.cancel_modal', 'uses' => 'ClassesController@cancelClassModal']);

        Route::post('cancel/{class}',['as' => 'classes.cancel', 'uses' => 'ClassesController@cancelClass']);

        Route::post('invite_teacher/{class}',['as' => 'classes.invite_teacher', 'uses' => 'ClassInvitationController@inviteTeacher']);

        Route::post('invite_award_teacher/{class}',['as' => 'classes.invite_award_teacher', 'uses' => 'ClassInvitationController@inviteAwardTeacher']);

        Route::post('invite_all_teachers/{class}',['as' => 'classes.invite_all_teachers', 'uses' => 'ClassInvitationController@inviteAllTeachers']);

        Route::get('award_to_teacher/{invite}',['as' => 'classes.award_to_teacher', 'uses' => 'ClassInvitationController@award']);

        Route::delete('delete_teacher_invitation/{id}',['as' => 'classes.delete_teacher_invitation', 'uses' => 'ClassInvitationController@deleteInvite']);

        Route::get('enroll_student/{class}/{student}',['as' => 'classes.enroll_student', 'uses' => 'EnrollmentController@enrollStudent']);

        Route::post('/enrollment/{class}/{student}',['as' => 'enrollment.direct_enroll', 'uses' => 'EnrollmentController@directEnroll']);
        Route::post('/enrollment/{class}/{student}/ghost',['as' => 'enrollment.enroll_as_ghost', 'uses' => 'EnrollmentController@enrollAsGhost']);
        Route::post('/enrollment/{class}/{student}/free',['as' => 'enrollment.free_enroll', 'uses' => 'EnrollmentController@freeEnroll']);

        Route::get('cancel_student/{class}/{student}',['as' => 'enrollment.cancel', 'uses' => 'EnrollmentController@cancelModal']);
        Route::post('cancel_student/{class}/{student}',['as' => 'enrollment.cancel', 'uses' => 'EnrollmentController@cancel']);
    });


    Route::group(['prefix' => 'group_classes'], function()
    {
        Route::get('table',['as' => 'group_classes.table', 'uses' => 'GroupClassesController@table']);
        Route::get('/',['as' => 'group_classes.index', 'uses' => 'GroupClassesController@index']);

        Route::get('create',['as' => 'group_classes.create', 'uses' => 'GroupClassesController@create']);
        Route::post('/',['as' => 'group_classes.store', 'uses' => 'GroupClassesController@store']);

        Route::get('show/{id}',['as' => 'group_classes.show', 'uses' => 'GroupClassesController@show']);

    });


    Route::group(['prefix' => 'guest_series'], function()
    {
        Route::get('table',['as' => 'guest_series.table', 'uses' => 'GuestSeriesController@table']);
        Route::get('/',['as' => 'guest_series.index', 'uses' => 'GuestSeriesController@index']);
        Route::get('create',['as' => 'guest_series.create', 'uses' => 'GuestSeriesController@create']);
        Route::post('/',['as' => 'guest_series.store', 'uses' => 'GuestSeriesController@store']);
        Route::get('show/{id}',['as' => 'guest_series.show', 'uses' => 'GuestSeriesController@show']);
        Route::get('edit/{id}',['as' => 'guest_series.edit', 'uses' => 'GuestSeriesController@edit']);
        Route::post('update/{id}',['as' => 'guest_series.update', 'uses' => 'GuestSeriesController@update']);
        Route::delete('delete/{id}',['as' => 'guest_series.delete', 'uses' => 'GuestSeriesController@destroy']);

        Route::get('cancel_modal/{series}',['as' => 'guest_series.cancel_modal', 'uses' => 'GuestSeriesController@cancelClassModal']);

        Route::post('cancel/{series}',['as' => 'guest_series.cancel', 'uses' => 'GuestSeriesController@cancelClass']);


        Route::post('upload_picture/{id}',['as' => 'guest_series.upload_picture', 'uses' => 'GuestSeriesPictureController@uploadPicture']);
        Route::get('send_emails/{id}',['as' => 'guest_series.send_emails', 'uses' => 'GuestSeriesController@sendEnrollmentEmails']);


    });


    Route::group(['prefix' => 'guest_series_levels'], function()
    {
        Route::get('create_modal/{series}',['as' => 'guest_series_levels.create_modal', 'uses' => 'GuestSeriesLevelController@createModal']);
        Route::post('create_modal/{series}',['as' => 'guest_series_levels.create_modal', 'uses' => 'GuestSeriesLevelController@storeModal']);

        Route::get('edit_modal/{level}',['as' => 'guest_series_levels.edit_modal', 'uses' => 'GuestSeriesLevelController@editModal']);
        Route::post('edit_modal/{level}',['as' => 'guest_series_levels.edit_modal', 'uses' => 'GuestSeriesLevelController@updateModal']);

        Route::delete('delete/{level}',['as' => 'guest_series_levels.delete', 'uses' => 'GuestSeriesLevelController@deleteLevel']);

    });




    Route::group(['prefix' => 'guest_series_episodes'], function()
    {

        Route::get('enrolled/{episode}/',['as' => 'guest_series_episodes.enrolled', 'uses' => 'GuestSeriesEpisodesController@enrolled']);

        Route::get('create_modal/{level}/',['as' => 'guest_series_episodes.create_modal', 'uses' => 'GuestSeriesEpisodesController@createModal']);
        Route::post('create_modal/{level}',['as' => 'guest_series_episodes.create_modal', 'uses' => 'GuestSeriesEpisodesController@storeModal']);

        Route::get('rerun_modal/{level}/',['as' => 'guest_series_episodes.rerun_modal', 'uses' => 'GuestSeriesEpisodesController@createRerunModal']);
        Route::post('rerun_modal/{level}',['as' => 'guest_series_episodes.rerun_modal', 'uses' => 'GuestSeriesEpisodesController@storeRerunModal']);

        Route::get('schedule_modal/{episode}',['as' => 'guest_series_episodes.schedule_modal', 'uses' => 'EpisodeScheduleController@scheduleModal']);
        Route::post('schedule_modal/{episode}',['as' => 'guest_series_episodes.schedule_modal', 'uses' => 'EpisodeScheduleController@scheduleModalPost']);

        Route::get('edit_modal/{episode}',['as' => 'guest_series_episodes.edit_modal', 'uses' => 'GuestSeriesEpisodesController@editModal']);
        Route::post('edit_modal/{episode}',['as' => 'guest_series_episodes.edit_modal', 'uses' => 'GuestSeriesEpisodesController@updateModal']);

        Route::get('cancel_modal/{episode}',['as' => 'guest_series_episodes.cancel_modal', 'uses' => 'GuestSeriesEpisodesController@cancelEpisodeModal']);

        Route::post('cancel/{episode}',['as' => 'guest_series_episodes.cancel', 'uses' => 'GuestSeriesEpisodesController@cancelEpisode']);

    });

});
