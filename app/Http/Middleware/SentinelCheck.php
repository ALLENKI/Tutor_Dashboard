<?php

namespace Aham\Http\Middleware;

use Closure;
use Sentinel;

class SentinelCheck
{
    /**
   * Sentinel - Check login status.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   *
   * @return mixed
   */
    public function handle($request, Closure $next)
    {
        if (Sentinel::check()) {
            return $next($request);
        }

        return redirect()->guest('auth/login');
    }
}
