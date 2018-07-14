<?php

return [

    [
        'group' => 'admin',
        'permission' => 'admin',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Open Admin Dashboard - Without this permission, no user can access admin dashboard, other permissions won\'t be useful',
        'comments' => 'implemented'
    ],

    [
        'group' => 'Permissions',
        'permission' => 'permissions',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Permissions',
        'comments' => 'done'
    ],

    [
        'group' => 'Topic Tree',
        'permission' => 'topic_tree.create',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Create Topics'
    ],

    [
        'group' => 'Topic Tree',
        'permission' => 'topic_tree.manage',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Edit Topics'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.view',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to View Users',
        'comments' => 'Implemented'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.edit',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Edit Users',
        'comments' => 'done'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.impersonate',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Impersonate a User'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.student_profile',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Create a Student Profile for Users and Activate it',
        'comments' => 'done'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.student_credits',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Student Credits',
        'comments' => 'done'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.student_goals',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Student Goals',
        'comments' => 'done'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.student_assessment',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Student Assessment - Adding, Removing Assessment',
        'comments' => 'done'
    ],


    [
        'group' => 'Users',
        'permission' => 'users.teacher_profile',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Create a Teacher Profile for Users and Activate it',
        'comments' => 'done'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.teacher_earnings',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Teacher Earnings',
        'comments' => 'done'
    ],

    [
        'group' => 'Users',
        'permission' => 'users.teacher_certification',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Teacher Certification',
        'comments' => 'done'
    ],

    [
        'group' => 'Coupons',
        'permission' => 'coupons',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Coupon Templates and Coupons',
        'comments' => 'done'
    ],

    [
        'group' => 'Goals',
        'permission' => 'goals',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Goals',
        'comments'=> 'done'
    ],

    [
        'group' => 'Scheduling Rules',
        'permission' => 'scheduling_rules',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Scheduling Rules',
        'comments' => 'done'
    ],

    [
        'group' => 'Content Pages',
        'permission' => 'content_pages',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Content Pages',
        'comments' => 'done'
    ],

    [
        'group' => 'Cities',
        'permission' => 'cities',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Cities'
    ],

    [
        'group' => 'Localities',
        'permission' => 'localities',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Localities'
    ],

    [
        'group' => 'Day Types',
        'permission' => 'day_types',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to Manage Day Types'
    ],

    [
        'group' => 'Locations',
        'permission' => 'locations.create',
        'level' => 'manage',
        'type' => 'global',
        'description' => 'Access to create a Location'
    ],

    [
        'group' => 'Locations',
        'permission' => 'locations.manage',
        'level' => 'manage',
        'type' => 'location',
        'description' => 'Access to manage a location including editing it, managing calendar, classrooms etc.,'
    ],

    [
        'group' => 'Classes',
        'permission' => 'classes.manage',
        'level' => 'manage',
        'type' => 'location',
        'description' => 'Access to create, edit, manage a class',
        'comments' => 'done'
    ],

];
