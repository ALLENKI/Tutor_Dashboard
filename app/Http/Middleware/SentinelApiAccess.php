<?php

namespace Aham\Http\Middleware;

use Closure;
use Sentinel;
use Dingo\Api\Routing\Helpers;

class SentinelApiAccess
{
    use Helpers;

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
                $user = $this->auth->user();

                if (!$user->hasAccess($permission)) {
                    abort(403, 'Unauthorized action.');
                }
            } catch (\Cartalyst\Sentinel\Users\UserNotFoundException $e) {
                return redirect()->route('login')->with('merror', trans('acl.user_not_found'));
            }
        }

        return $next($request);
    }
}
