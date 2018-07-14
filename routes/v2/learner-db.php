<?php

Route::group(
    ['namespace' => 'LearnerDB',
                'middleware' => ['sentinel'],
                'as' => 'learner-db::',
                'prefix' => 'learner-db'],
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
    'namespace' => 'Aham\Http\Controllers\V2\LearnerDB',
    // 'prefix' => 'hub'
], function ($api) {
    $api->group(['prefix' => 'learner'], function ($api) {
        $api->get('enrolled-units', 'CalendarController@enrolledUnits');
    });
});
