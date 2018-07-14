<?php

Route::group(
    [
        'namespace' => 'HubDB',
        'middleware' => ['sentinel'],
        'as' => 'hub-db::',
        'prefix' => 'hub-db'
    ],
function () {
    Route::get('/', [
        'as' => 'home',
        'uses' => 'HomeController@home'
    ]);
}
);

$api = app('Dingo\Api\Routing\Router');

$api->version('v2', [
    'middleware' => ['api.auth', 'apiAccess'],
    'namespace' => 'Aham\Http\Controllers\V2\HubDB',
    // 'prefix' => 'hub'
], function ($api) {


    $api->group(['prefix' => 'hub/{slug}'], function ($api) {
        $api->get('/course-catalog/browse-topics', ['uses' => 'CourseCatalogController@browseTopics']);
        $api->get('/course-catalog/browse-courses', ['uses' => 'CourseCatalogController@browseCourses']);
        $api->get('/earnings', ['uses' => 'EarningsController@index']);
    });

    $api->get('hub/available-locations', 'HomeController@locations');
    $api->get('hub/location/{slug}', 'HomeController@getLocationDetail');
    $api->get('hub/topics/{slug}', 'HomeController@getHubTopics');

    $api->get('hub/get-classes-in-timings/{slug}','HomeController@getClassInTimings');
    $api->get('hub/get-analytics/{slug}','HomeController@getAnalytics');

    $api->get('hub/learner-by-date','LearnerController@learnerByDate');
    $api->get('hub/tutor-by-date','TutorController@tutorByDate');
    $api->get('hub/learner/{id}/classes','LearnerController@Classes');
    $api->get('hub/tutor/{id}/classes','TutorController@Classes');
    $api->get('hub/learner/{id}/classes-calendar','LearnerController@ClassesForCalendar');
    $api->get('hub/tutor/{id}/classes-calendar','TutorController@ClassesForCalendar');
    $api->get('hub/colors/', 'HomeController@HubColors');


    $api->get('hub/all-learners','LearnerController@AllLearners');
    $api->get('hub/all-tutors','TutorController@AllTutors');

    $api->get('hub/tutor-profile/{id}','TutorController@tutorProfile');
    $api->get('hub/learner-profile/{id}','LearnerController@learnerProfile');
    $api->get('hub/user-profile/{id}','LearnerController@userProfile');
    $api->post('hub/learner-profile/{id}','LearnerController@saveLearnerProfile');

    $api->get('hub/learner-profile/{id}/get-feedback','LearnerController@getFeedback');

    $api->get('hub/classes', 'ClassesController@index');
    $api->post('hub/classes', 'ClassesController@store');
    $api->put('hub/classes/{id}', 'ClassesController@update');
    $api->get('hub/classes/{id}', 'ClassesController@show');
    $api->get('hub/calendar/{id}', 'ClassesController@calendar');
    $api->post('hub/classes/schedule', 'ClassesController@schedule');
    $api->get('hub/classes/calendar/{slug}', 'ClassesController@classesForCalendar');

    $api->get('hub/classes/{id}/repeat-class-details', 'ClassesController@repeatClassDetails');
    $api->get('hub/classes/{id}/repeat-classes', 'ClassesController@repeatClasses');
    $api->get('hub/classes/course/{id}/classes', 'ClassesController@course');
    $api->get('hub/classes/course/{id}/class-course', 'ClassesController@classeCourseDetail');

    $api->group(['prefix' => 'hub/single-class'], function ($api) {
        $api->get('/{id}/enrollments', ['uses' => 'ClassController@enrollments']);
        $api->post('/{id}/enroll/{learner}', ['uses' => 'ClassController@enroll']);

        $api->post('/{id}/repeat-class', ['uses' => 'ClassController@repeatClass']);

        $api->post('/{classId}/cancel-class', ['uses' => 'ClassController@cancelClass']);

        $api->post('/{classId}/complete-class', ['uses' => 'ClassController@completeClass']);
        $api->post('/{classId}/finalize-payments', ['uses' => 'ClassController@finalizePayments']);
        $api->post('/{classId}/calculate-payments', ['uses' => 'ClassController@calculatePayments']);
        
        $api->post('/{id}/cancel-class-enrollment/{learner}', ['uses' => 'ClassController@cancelClassEnrollment']);
        $api->post('/{unit}/cancel-unit-enrollment/{learner}', ['uses' => 'ClassController@cancelUnitEnrollment']);

        $api->post('/{classId}/mark-as-done/{unitId}', ['uses' => 'ClassController@markAsDone']);
        $api->post('/{classId}/change-topic/{topicId}', ['uses' => 'ClassController@changeTopic']);

        $api->get('/{classId}/tutors', ['uses' => 'ClassController@tutors']);
        $api->get('/{classId}/tutors/{unitId}', ['uses' => 'ClassController@tutorsForUnit']);
        $api->post('/{id}/assign-tutor/{tutor}', ['uses' => 'ClassController@assignTutor']);
        $api->post('/{id}/assign-tutor/{tutor}/{unitId}', ['uses' => 'ClassController@changeTutor']);


        $api->post('/{classId}/tutor/{unitId}/addPayment', ['uses' => 'ClassController@addPayments']);


        $api->get('/{id}/check-enrollment-eligibility/{learner}', ['uses' => 'ClassController@checkEligibility']);

        $api->get('/{id}/notes', ['uses' => 'ClassController@getNotesForClass']);
        $api->post('/{id}/notes', ['uses' => 'ClassController@addNotesToClass']);
        
        $api->get('/{id}/student-invitation',['uses' => 'ClassController@getStudentInvite']);
        $api->post('/{id}/student-invitation',['uses' => 'ClassController@studentInvite']);
    
        $api->get('/{id}', ['uses' => 'ClassController@show']);
    });

    $api->group(['prefix' => 'hub/group-class'], function($api){

        //   $api->get('',['uses' => '']);

    });

});
