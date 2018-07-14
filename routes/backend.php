<?php


Route::group(
    ['namespace' => 'Backend',
                'middleware' => ['sentinel', 'hasAccess'],
                'hasAccess' => 'admin',
                'as' => 'admin::',
                'prefix' => 'admin'],
function () {
    Route::get('/', [
            'as' => 'admin',
            'uses' => 'AdminController@home'
        ]);

    Route::get('test-queues', [
            'as' => 'test-queues',
            'uses' => 'AdminController@testQueues'
        ]);

    Route::get('recon', [
            'as' => 'recon',
            'uses' => 'AdminController@recon'
        ]);

    include 'backend/topic_tree.php';
    include 'backend/users.php';
    include 'backend/locations.php';
    include 'backend/classes.php';
    include 'backend/content.php';
}
);
