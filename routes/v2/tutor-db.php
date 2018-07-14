<?php

Route::group(
    ['namespace' => 'TutorDB',
                'middleware' => ['sentinel'],
                'as' => 'tutor-db::',
                'prefix' => 'tutor-db'],
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
    'namespace' => 'Aham\Http\Controllers\V2\TutorDB',
    // 'prefix' => 'tutor'
], function ($api) {

		$api->group(['prefix' => 'tutor/singleclass'], function ($api) {

             $api->get('/{id}/enrollments', ['uses' => 'ClassController@enrollments']);
            $api->post('/{id}/enroll/{learner}', ['uses' => 'ClassController@enroll']);
            $api->post('/{id}/cancel-class-enrollment/{learner}', ['uses' => 'ClassController@cancelClassEnrollment']);
            $api->post('/{unit}/cancel-unit-enrollment/{learner}', ['uses' => 'ClassController@cancelUnitEnrollment']);
	    });
	});