<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->post('ala/authenticate',['uses' => 'Aham\Http\Controllers\API\AuthController@authForAla']);

    $api->get('ala/account',['uses' => 'Aham\Http\Controllers\API\AuthController@alaAccount']);


});

$api->version('v1', ['middleware' => ['api.auth']], function ($api) {

    $api->group(['prefix' => 'ala'], function ($api) {

        $api->get('get-schedule-data',['uses' => 'Aham\Http\Controllers\API\Ala\AvailabilityController@get']);

        $api->get('get-location-availability',['uses' => 'Aham\Http\Controllers\API\Ala\AvailabilityController@getLocationAvailability']);

        $api->get('get-class-timings/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\AvailabilityController@getClassTimings']);

        $api->get('get-locations',['uses' => 'Aham\Http\Controllers\API\Ala\LocationsController@get']);

        $api->post('location/add-note/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@addNoteToLocation']);

        $api->get('location/get-notes/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@getNotesForLocation']);

        $api->get('get-location-detail/{slug}',['uses' => 'Aham\Http\Controllers\API\Ala\LocationsController@getDetail']);

        $api->get('get-analytics/{slug}',['uses' => 'Aham\Http\Controllers\API\Ala\LocationHomeController@getAnalytics']);

        $api->get('get-class-in-timings/{slug}',['uses' => 'Aham\Http\Controllers\API\Ala\LocationHomeController@getClassInTimings']);

        $api->get('location/{slug}/classes_cal',['uses' => 'Aham\Http\Controllers\API\Ala\LocationsController@classesForCalendar']);

        $api->get('get-classrooms/{slug}',['uses' => 'Aham\Http\Controllers\API\Ala\LocationsController@getClassrooms']);

        $api->get('get-topics',['uses' => 'Aham\Http\Controllers\API\Ala\TopicsController@get']);

        $api->get('get-teacher',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@get']);

        $api->get('get-topic-detail/{slug}',['uses' => 'Aham\Http\Controllers\API\Ala\TopicsController@getDetail']);

        /** Teachers **/

        $api->get('teachers',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@index']);
        $api->get('teachers-by-date',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@teachersByDate']);
        $api->get('teachers/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@show']);
        $api->get('teachers/{id}/analytics',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@getAnalytics']);
        $api->get('teachers/{id}/classes',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@classes']);
        $api->get('teachers/{id}/classes_cal',['uses' => 'Aham\Http\Controllers\API\Ala\TeachersController@classesForCalendar']);

       $api->post('teacher/add-note/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@addNoteToTeacher']);

        $api->get('teacher/get-notes/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@getNotesForTeacher']);


        /** Students **/
        
        $api->get('students',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@index']);
        $api->get('students/get-student-timedate',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@getStudentTimeDate']);
        $api->get('students/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@show']);
        $api->get('students-by-date',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@studentsByDate']);
        $api->get('students/{id}/classes',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@classes']);
        $api->get('students/{id}/classes_cal',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@classesForCalendar']);


        $api->post('student/post-interest/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\StudentsController@postInterest']);

        $api->post('student/add-note/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@addNoteToStudent']);

        $api->get('student/get-notes/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@getNotesForStudent']);


        /** Classrooms **/
        
        $api->get('classrooms',['uses' => 'Aham\Http\Controllers\API\Ala\ClassroomsController@index']);
        $api->get('classrooms/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassroomsController@show']);
        $api->get('classrooms/{id}/classes',['uses' => 'Aham\Http\Controllers\API\Ala\ClassroomsController@classes']);
        $api->get('classrooms/{id}/classes_cal',['uses' => 'Aham\Http\Controllers\API\Ala\ClassroomsController@classesForCalendar']);

        /** Invitations **/

        $api->get('class/{id}/invitations',['uses' => 'Aham\Http\Controllers\API\Ala\InvitationsController@getInvitations']);

        $api->get('unit/{id}/eligible',['uses' => 'Aham\Http\Controllers\API\Ala\InvitationsController@getEligiblePerUnit']);

        $api->post('class/{id}/invitations',['uses' => 'Aham\Http\Controllers\API\Ala\InvitationsController@postInvitations']);

        $api->post('class/{invite}/award-invitation',['uses' => 'Aham\Http\Controllers\API\Ala\InvitationsController@awardInvitation']);


        /** Student Invitations **/

        $api->get('class/{id}/student-invitations',['uses' => 'Aham\Http\Controllers\API\Ala\StudentInvitationsController@getInvitations']);

        $api->post('class/{id}/student-invitations',['uses' => 'Aham\Http\Controllers\API\Ala\StudentInvitationsController@postInvitations']);


        /** Enrollments **/

        $api->get('class/{id}/enrollments',['uses' => 'Aham\Http\Controllers\API\Ala\EnrollmentsController@getEnrollments']);

        $api->get('class/{id}/enrollment-eligibility/{stu}',['uses' => 'Aham\Http\Controllers\API\Ala\EnrollmentsController@checkEligibility']);

        $api->post('class/{id}/enroll/{stu}',['uses' => 'Aham\Http\Controllers\API\Ala\EnrollmentsController@enroll']);

        $api->post('class/{id}/cancel-enroll/{stu}',['uses' => 'Aham\Http\Controllers\API\Ala\EnrollmentsController@cancelEnrollment']);


        /** Classes **/

        $api->get('classes',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@index']);

        $api->post('classes',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@store']);

        $api->post('schedule-class/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@scheduleClass']);

        $api->get('classes/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@show']);

        $api->put('classes/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@update']);

        $api->post('class/mark-as-done/{timing}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@markAsDone']);

        $api->post('class/change-teacher/{timing}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@changeTeacher']);

        $api->post('class/cancel-class/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@cancelClass']);

        $api->post('class/complete-class/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@completeClass']);

        $api->post('class/enable-chat/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\ClassesController@enableChat']);

        $api->post('class/add-note/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@AddNoteToClass']);

        $api->get('class/get-notes/{id}',['uses' => 'Aham\Http\Controllers\API\Ala\NotesController@getNotesForClass']);

        /** Middle States **/

        $api->get('middlestates',['uses' => 'Aham\Http\Controllers\API\Ala\MiddleStatesController@index']);


    });

});

