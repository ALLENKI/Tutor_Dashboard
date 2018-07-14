<?php

namespace Aham\Http\Middleware;

use Closure;
use Sentinel;

class TeacherDashboardCheck
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
      if (Sentinel::getUser()->teacher) {
        
        return $next($request);
        
      }

      abort(404);
  }
}
