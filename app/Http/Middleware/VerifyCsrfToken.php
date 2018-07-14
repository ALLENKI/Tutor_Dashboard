<?php

namespace Aham\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    	'notify-chat',
        'dashboard/student/credits/payment_success',
        'ahamapi/*',
        'series/*'
    ];
}
