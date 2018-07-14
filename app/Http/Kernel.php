<?php

namespace Aham\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Aham\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Aham\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Aham\Http\Middleware\TrustProxies::class,
        \Illuminate\Session\Middleware\StartSession::class,
        // \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Barryvdh\Cors\HandleCors::class,
        \Barryvdh\Cors\HandlePreflight::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Aham\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Aham\Http\Middleware\RedirectIfAuthenticated::class,

        'sentinel' => \Aham\Http\Middleware\SentinelCheck::class,  // add this
        'hasAccess' => \Aham\Http\Middleware\SentinelHasAccess::class, // add this,
        'apiAccess' => \Aham\Http\Middleware\SentinelApiAccess::class, // add this,
        'teacher' => \Aham\Http\Middleware\TeacherDashboardCheck::class,
        'student' => \Aham\Http\Middleware\StudentDashboardCheck::class,
        'api.auth' => \Dingo\Api\Http\Middleware\Auth::class, // add this,
    ];
}
