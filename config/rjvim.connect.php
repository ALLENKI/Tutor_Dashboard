<?php

return array(

    'route' => '/',

    'ajax' => false,

    'force' => false,

    'offline' => false,

    'google' => array(

        'clients' => array(

            'default' => array(
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_SECRET'),
                'redirect_uri' => env('GOOGLE_REDIRECT'),
            ),

        ),

        'scopes' => array(

            'default' => array(
                "https://www.googleapis.com/auth/userinfo.email",
                "https://www.googleapis.com/auth/userinfo.profile"
            ),

        ),

    ),


    'github' => array(

        'clients' => array(

            'default' => array(
                'client_id' => 'XXXXXXXXXXXXXX',
                'client_secret' => 'XXXXXXXXXXXXXXXXXXXX',
                'redirect_uri' => 'XXXXXXXXXXXXXXXXXXX',
            ),
        ),

        'scopes' => array(

            'default' => array(
                "user:email"
            ),

        ),

    ),

    'facebook' => array(

        'clients' => array(

            'default' => array(
                'client_id' => 'XXXXXXXXXXXXXX',
                'client_secret' => 'XXXXXXXXXXXXXXXXXXXX',
                'redirect_uri' => 'XXXXXXXXXXXXXXXXXXX',
            ),
        ),

        'scopes' => array(

            'default' => array(
                'email',
                'public_profile',
                'user_friends'
            ),

        ),

    ),

);