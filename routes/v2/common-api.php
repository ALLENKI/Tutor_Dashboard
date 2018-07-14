<?php

use function Clue\StreamFilter\fun;

$api = app('Dingo\Api\Routing\Router');

$api->version('v2', [
    'namespace' => 'Aham\Http\Controllers\V2\Common'
], function ($api) {
    $api->group(['prefix' => 'common'], function ($api) {

        $api->group(['prefix' => 'learners'], function($api) {
            $api->get('/', ['uses' => 'LearnersController@index']);
            $api->get('/{id}',['uses' => 'LearnersController@show']);
        });

        $api->group(['prefix' => 'tutors'], function($api) {
             $api->get('/', ['uses' => 'TutorController@index']);
             $api->get('/dataTable', ['uses' => 'TutorController@dataTable']);
             $api->get('/{id}', ['uses' => 'TutorController@getTutor']);
        });

        $api->group([], function ($api) {
            $api->get('data/tutors', ['uses' => 'DataController@tutors']);
            $api->get('hubs', ['uses' => 'DataController@hubs']);
            $api->get('category-tree', ['uses' => 'CategoriesController@categoryTree']);
            $api->get('subject-tree', ['uses' => 'CategoriesController@subjectTree']);
            $api->get('sub-category-tree', ['uses' => 'CategoriesController@subCategoryTree']);
            $api->get('topic-tree', ['uses' => 'CategoriesController@topicTree']);
            $api->get('course-tree', ['uses' => 'CategoriesController@courseTree']);
            $api->get('topic-tree-neo', ['uses' => 'CategoriesController@neoTopicTree']);

            $api->get('permissionData',['uses' => 'DataController@getPermissionData']);
            $api->get('locationData/{id}',['uses' => 'DataController@getHubPermission']);
            $api->get('getUserPermission/{id}',['uses' => 'DataController@getUserPermission']);
        });

        $api->group(['prefix' => 'hub-db'], function ($api) {
            $api->get('{hub}/subject-tree', ['uses' => 'CatalogueController@subject']);
            $api->get('{hub}/category-tree', ['uses' => 'CatalogueController@categoryTree']);
            $api->get('{hub}/course-tree', ['uses' => 'CatalogueController@courseTree']);
            $api->get('{hub}/hub-topics', ['uses' => 'CatalogueController@hubTopics']);
            $api->get('{hub}/sub-category-tree', ['uses' => 'CatalogueController@subCategoryTree']);
            $api->get('{hub}/course-topic-tree', ['uses' => 'CatalogueController@courseTopicTree']);
            $api->get('{hub}/topic-tree', ['uses' => 'CatalogueController@topicTree']);
        });

        $api->group(['prefix' => 'wishlist'], function ($api) {

            $api->get('{learnerId}/view-topics',['uses' => 'WishListController@viewTopicsForLeanrer']);

            $api->post('admin-db/{learnerId}/add', ['uses' => 'WishListController@store']);
            $api->delete('admin-db/{learnerId}/destroy/{wishlistId}', ['uses' => 'WishListController@destroy']);

            $api->post('hub-db/{learnerId}/{hub}/add', ['uses' => 'WishListController@hubStoreWishList']);
            $api->delete('hub-db/{learnerId}/{hub}/destroy/{wishlistid}', ['uses' => 'WishListController@hubDestroy']);
            
            $api->get('admin-db/{learnerId}/admin-wishlist-topics', ['uses' => 'WishListController@adminWishlistTopics']);
            
            $api->get('hub-db/{learnerId}/{hub}/hub-wishlist-topics', ['uses' => 'WishListController@hubWishlistTopics']);

        });

    });
});
