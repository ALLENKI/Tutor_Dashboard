<?php

namespace Aham\Http\Middleware;

use Closure;
use Sentinel;

class StudentDashboardCheck
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
      if (Sentinel::getUser()->student) {
          return $next($request);
      }

      abort(404);
  }
}
