<?php


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	$api->get('hello',function(){
		return 'hello';
	});

    $api->post('tutor/authenticate',['uses' => 'Aham\Http\Controllers\API\AuthController@tutorAuthenticate']);

    $api->post('learner/authenticate',['uses' => 'Aham\Http\Controllers\API\AuthController@learnerAuthenticate']);

    $api->get('topic_details/{id}',['uses' => 'Aham\Http\Controllers\API\TopicsController@show']);

    $api->get('test_invite_push/{id}',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@testInvitePush']);

    $api->get('test_class_push/{id}',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@testClassPush']);

});

$api->version('v1', ['middleware' => ['api.auth']], function ($api) {
    // Routes within this version group will require authentication.

    $api->get('tutor/account',['uses' => 'Aham\Http\Controllers\API\AuthController@tutorAccount']);

    $api->post('tutor/account/upload_avatar',['uses' => 'Aham\Http\Controllers\API\AuthController@uploadAvatar']);

    $api->post('tutor/register_for_push',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@register']);

    $api->post('tutor/delete_push_token',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@delete']);

    $api->post('register_for_push',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@register']);

    $api->post('delete_push_token',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@delete']);

    $api->get('tutor/dashboard',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@index']);


    
   

    $api->get('tutor/profile-settings',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@getProfile']);

    $api->post('tutor/profile-settings',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@UpdateProfile']);

    
   

    $api->post('tutor/change_password',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@updatePassword']);

    $api->get('tutor/update_mobile',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@getMobile']);

    $api->post('tutor/update_mobile',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@updateMobile']);

    
    $api->post('tutor/upload-avatar',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@uploadAvatar']);

    $api->post('tutor/upload-resume',['uses' => 'Aham\Http\Controllers\API\Tutor\DashboardController@uploadResume']);
    


    // Invitations Api

    $api->group(['prefix' => 'tutor/invitations'], function ($api) {


        $api->get('/',['uses' => 'Aham\Http\Controllers\API\Tutor\InvitationsController@index']);

        $api->get('/{id}',['uses' => 'Aham\Http\Controllers\API\Tutor\InvitationsController@show']);

        $api->post('/{id}/accept',['uses' => 'Aham\Http\Controllers\API\Tutor\InvitationsController@accept']);

        $api->post('/{id}/reject',['uses' => 'Aham\Http\Controllers\API\Tutor\InvitationsController@reject']);
        

    });

    // Classes Api

    $api->group(['prefix' => 'tutor/classes'], function ($api) {

        $api->get('/',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@index']);

        $api->get('/{id}',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@show']);
        
        $api->get('chat-details/{class}',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@chatRequired']);

        $api->post('/{code}/leave_feedback',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@tutorFeedback']);

        $api->get('{id}/notes',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@getNotesForClass']);
 
        $api->post('/{id}/notes',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@addNotesToClass']);
       
        $api->post('upload-file/{id}',['uses' => 'Aham\Http\Controllers\API\Tutor\ClassesController@uploadDoc']);
        
    });


    $api->get('tutor/availabilities',['uses' => 'Aham\Http\Controllers\API\Tutor\AvailabilityController@index']);

    $api->post('tutor/delete_availability/{id}',['uses' => 'Aham\Http\Controllers\API\Tutor\AvailabilityController@delete']);

    $api->post('tutor/availabilities',['uses' => 'Aham\Http\Controllers\API\Tutor\AvailabilityController@store']);

    $api->post('tutor/availabilities',['uses' => 'Aham\Http\Controllers\API\Tutor\AvailabilityController@store']);

    $api->post('tutor/update_ignore_calendar',['uses' => 'Aham\Http\Controllers\API\Tutor\AvailabilityController@updateIgnoreCalendar']);

});

$api->version('v1', ['middleware' => ['api.auth']], function ($api) {
    // Routes within this version group will require authentication.

    $api->get('learner/account',['uses' => 'Aham\Http\Controllers\API\AuthController@learnerAccount']);

    $api->post('learner/account/upload_avatar',['uses' => 'Aham\Http\Controllers\API\AuthController@uploadAvatar']);

    $api->post('learner/register_for_push',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@register']);

    $api->post('learner/delete_push_token',['uses' => 'Aham\Http\Controllers\API\RegisterForPushController@delete']);


     // Learner Classes Api

    $api->group(['prefix' => 'learner/classes'], function ($api) {

        $api->get('browse',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@browse']);

        $api->get('recommended',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@recommended']);

        $api->post('not_interested/{id}',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@markAsNotInterested']);

        $api->post('get-enroll/{id}',['uses' => 'Aham\Http\Controllers\API\Learner\EnrollmentsController@getEnroll']);

        $api->post('post-enroll/{id}',['uses' => 'Aham\Http\Controllers\API\Learner\EnrollmentsController@postEnroll']);

        $api->post('cancel-unit-enroll/{id}',['uses' => 'Aham\Http\Controllers\API\Learner\EnrollmentsController@cancelUnitEnrollment']);


        $api->get('/',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@index']);

        $api->get('/{id}',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@show']);

        $api->get('chat-details/{class}',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@chatRequired']);


        $api->post('/{code}/leave_feedback',['uses' => 'Aham\Http\Controllers\API\Learner\ClassesController@studentFeedback']);
        

    });

     $api->get('learner/credit-history',['uses' => 'Aham\Http\Controllers\API\Learner\CreditsHistoryController@index2']);
    
     $api->get('learner/dashboard',['uses' => 'Aham\Http\Controllers\API\Learner\DashboardController@index']);
        
});

$api->version('v1', [
    'middleware' => ['api.auth', 'apiAccess'],
    'namespace' => 'Aham\Http\Controllers\V2\TutorDB',
    // 'prefix' => 'tutor'
], function ($api) {

        $api->group(['prefix' => 'tutor/singleclass'], function ($api) {

             $api->get('/{id}/enrollments', ['uses' => 'ClassController@enrollments']);
            $api->post('/{id}/enroll/{learner}', ['uses' => 'ClassController@enroll']);
            $api->post('/{id}/cancel-class-enrollment/{learner}', ['uses' => 'ClassController@cancelClassEnrollment']);
            $api->post('/{unit}/cancel-unit-enrollment/{learner}', ['uses' => 'ClassController@cancelUnitEnrollment']);
            $api->get('/{id}/check-enrollment-eligibility/{learner}', ['uses' => 'ClassController@checkEligibility']);
             $api->post('/{unit}/attended/{learner}', ['uses' => 'ClassController@attended']);
            $api->post('/{unit}/not-attended/{learner}', ['uses' => 'ClassController@notattended']);
        });
    });
