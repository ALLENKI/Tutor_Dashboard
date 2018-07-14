<?php

namespace Aham\Http\Middleware;

use Closure;
use Sentinel;

class SentinelHasAccess
{
    /**
   * Sentinel - Check role permission.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   *
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
      $actions = $request->route()->getAction();

      if (array_key_exists('hasAccess', $actions)) {
          $permission = $actions['hasAccess'];

          try {
              $user = Sentinel::getUser();

              if (!$user->hasAccess($permission)) {
                  flash()->overlay("Sorry! You don't have access!");

                  return redirect()->route('home');
              }
          } catch (\Cartalyst\Sentinel\Users\UserNotFoundException $e) {
              return redirect()->route('login')->with('merror', trans('acl.user_not_found'));
          }
      }

      return $next($request);
  }
}
