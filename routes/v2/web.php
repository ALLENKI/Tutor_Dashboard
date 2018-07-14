<?php


Route::group(
    ['namespace' => 'V2'],
function () {
    include 'admin-db.php';
    include 'hub-db.php';
    include 'learner-db.php';
    include 'tutor-db.php';
    include 'common-api.php';
}
);

$api = app('Dingo\Api\Routing\Router');

$api->version('v2', [], function ($api) {
    $api->get('testing-v2-hello', function () {
        return 'testing-v2-hello';
    });
});

$api->version('v2', ['middleware' => ['api.auth']], function ($api) {
    $api->get('account', ['uses' => 'Aham\Http\Controllers\V2\AccountController@account']);
});
