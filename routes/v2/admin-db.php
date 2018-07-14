<?php

Route::group(
                ['namespace' => 'AdminDB',
                'middleware' => ['sentinel', 'hasAccess'],
                'hasAccess' => 'admin',
                'as' => 'admin-db::',
                'prefix' => 'admin-db'],
function () {
    Route::get('/', [
        'uses' => 'HomeController@home'
    ]);
}
);

$api = app('Dingo\Api\Routing\Router');

$api->version('v2', [
    'middleware' => ['api.auth', 'apiAccess'],
    'hasAccess' => 'admin',
    'namespace' => 'Aham\Http\Controllers\V2\AdminDB'
], function ($api) {

    $api->group(['prefix' => 'hubs'], function ($api) {
        $api->get('/get-topics/{hub}/{filter}', ['uses' => 'HubController@getTopics']);
        $api->post('/assign-topics/{hub}', ['uses' => 'HubController@assignTopics']);
        $api->post('/remove-topics/{hub}', ['uses' => 'HubController@removeHubTopic']);
        $api->get('/hub-selected-topics/{hub}', ['uses' => 'HubController@getHubSelectedTopics']);
    });

    $api->group(['prefix' => 'credits'], function ($api) {
        $api->get('/', ['uses' => 'CreditsController@index']);
        $api->post('/', ['uses' => 'CreditsController@store']);
        $api->post('/buy', ['uses' => 'CreditsController@buy']);
        $api->put('/{id}', ['uses' => 'CreditsController@update']);
        $api->delete('/{id}', ['uses' => 'CreditsController@destroy']);
    });

    $api->group(['prefix' => 'admin/learners'], function ($api) {
        $api->put('{id}', ['uses' => 'LearnersController@update']);
    });

    $api->group(['prefix' => 'learners'], function ($api) {
        $api->get('/', ['uses' => 'LearnersController@index']);
        $api->get('{id}/credits', ['uses' => 'LearnerCreditsController@credits']);
        $api->get('{id}/get-credits', ['uses' => 'LearnerCreditsController@getCredits']);
        $api->get('{id}/sync-credits', ['uses' => 'LearnerCreditsController@syncCredits']);
        $api->get('{id}/reset-buckets', ['uses' => 'LearnerCreditsController@resetBuckets']);
        $api->post('{id}/add-purchased-credits', ['uses' => 'LearnerCreditsController@addPurchasedCredits']);
        $api->post('{id}/add-promotional-credits', ['uses' => 'LearnerCreditsController@addPromotionalCredits']);
        $api->put('{id}', ['uses' => 'LearnerCreditsController@update']);
    });

    $api->group(['prefix' => 'course_catalog'], function ($api) {
        $api->get('browse', ['uses' => 'CourseCatalogController@browse']);
        
        $api->get('browse-topics', ['uses' => 'CourseCatalogController@browseTopics']);
        $api->get('browse-courses', ['uses' => 'CourseCatalogController@browseCourses']);

        $api->group(['prefix' => 'categories'], function ($api) {
            $api->get('/', ['uses' => 'CategoriesController@index']);
            
            $api->get('/', ['uses' => 'CategoriesController@index']);
            $api->get('/{id}', ['uses' => 'CategoriesController@show']);
            $api->post('/', ['uses' => 'CategoriesController@store']);
            $api->put('/{id}', ['uses' => 'CategoriesController@update']);
            $api->delete('/{id}', ['uses' => 'CategoriesController@destroy']);
        });

        $api->group(['prefix' => 'subjects'], function ($api) {
            $api->get('/', ['uses' => 'SubjectsController@index']);
            $api->get('/', ['uses' => 'SubjectsController@index']);
            $api->get('/{id}', ['uses' => 'SubjectsController@show']);

            $api->post('/', ['uses' => 'SubjectsController@store']);
            $api->put('/{id}', ['uses' => 'SubjectsController@update']);
            $api->delete('/{id}', ['uses' => 'SubjectsController@destroy']);
        });

        $api->group(['prefix' => 'sub-categories'], function ($api) {
            $api->get('/', ['uses' => 'SubCategoriesController@index']);
            $api->get('/{id}', ['uses' => 'SubCategoriesController@show']);
            $api->post('/', ['uses' => 'SubCategoriesController@store']);
            $api->put('/{id}', ['uses' => 'SubCategoriesController@update']);
            $api->delete('/{id}', ['uses' => 'SubCategoriesController@destroy']);
        });

        $api->group(['prefix' => 'topics'], function ($api) {
            $api->get('/', ['uses' => 'TopicController@index']);
            $api->get('/{id}', ['uses' => 'TopicController@show']);
            $api->post('/', ['uses' => 'TopicController@store']);
            $api->put('/{id}', ['uses' => 'TopicController@update']);
            $api->delete('/{id}', ['uses' => 'TopicController@destroy']);

            $api->get('/{id}/get-active-topics', ['uses' => 'TopicController@getAllActiveTopics']);
            $api->get('/{id}/get-prerequisite', ['uses' => 'TopicController@getPrerequisite']);
            $api->post('/{id}/add-prerequisite', ['uses' => 'TopicController@addPrerequisite']);
            $api->post('/{id}/remove-prerequisite', ['uses' => 'TopicController@removePrerequisite']);

            $api->post('/{id}/upload-doc', ['uses' => 'TopicController@uploadDoc']);
            $api->get('/{id}/get-doc', ['uses' => 'TopicController@getDocs']);
            $api->post('/{id}/remove-doc', ['uses' => 'TopicController@removeDocs']);
            $api->post('/{id}/add-to-Course', ['uses' => 'TopicController@addToCourse']);
        });

        $api->group(['prefix' => 'courses'], function ($api) {
            $api->get('/', ['uses' => 'CoursesController@index']);
            $api->get('/{id}/show', ['uses' => 'CoursesController@show']);
            
            $api->get('/{id}/tree', ['uses' => 'CoursesController@courseTree']);

            $api->post('/', ['uses' => 'CoursesController@store']);
            $api->put('/{id}', ['uses' => 'CoursesController@update']);
            $api->delete('/{id}', ['uses' => 'CoursesController@destroy']);

            $api->post('/{id}/delete-topic', ['uses' => 'CoursesController@deleteTopic']);
            $api->get('/get-topics', ['uses' => 'CoursesController@getTopics']);
            $api->get('/get-courses', ['uses' => 'CoursesController@getCourses']);
        });

        $api->get('/{id}/get-a-catalog-path',['uses' => 'CourseCatalogController@getACatalogPath']);
    });

    $api->group(['prefix' => 'users_payments'],function ($api) {
	    	$api->get('table',['uses' => 'PaymentsController@table']);
	    	$api->get('table1',['uses' => 'PaymentsController@table1']);

            $api->get('/{id}/edit/{type}',[ 'uses' => 'PaymentsController@edit']);
            $api->put('/{id}/{type}',[ 'uses' => 'PaymentsController@update']);
            $api->get('/{id}/invoice/{type}',[ 'uses' => 'PaymentsController@getInvoice']);
            $api->get('/export', ['uses' => 'PaymentsController@export']);
            $api->post('/{id}/invoice/{type}',[ 'uses' => 'PaymentsController@invoice']);
            $api->delete('/{id}/{type}',[ 'uses' => 'PaymentsController@destroy']);
    });

    $api->group(['prefix' => 'user'],function ($api) {
        $api->post('{id}/manage/permission',['uses' => 'UserController@updatePermissions']);
        $api->get('table',['uses' => 'PaymentsController@table']);
        $api->get('table1',['uses' => 'PaymentsController@table1']);

        $api->get('/{id}/edit/{type}',[ 'uses' => 'PaymentsController@edit']);
        $api->put('/{id}/{type}',[ 'uses' => 'PaymentsController@update']);
        $api->get('/{id}/invoice/{type}',[ 'uses' => 'PaymentsController@getInvoice']);
        $api->get('/export', ['uses' => 'PaymentsController@export']);
        $api->post('/{id}/invoice/{type}',[ 'uses' => 'PaymentsController@invoice']);
        $api->delete('/{id}/{type}',[ 'uses' => 'PaymentsController@destroy']);
    });

    $api->group(['prefix' => 'tutor_payments'],function ($api) {
        
        $api->get('{tutorId}/hub/{hubId}/show',['uses' => 'TutorPaymentsController@index']);
        $api->post('createOrUpdate/payment',['uses' => 'TutorPaymentsController@createUpdate']);
        $api->delete('tutor/{tutorId}/delete/{hubId}/day/{day}/timings/{timingsId}',['uses' => 'TutorPaymentsController@destroy']);
        
    });


});
