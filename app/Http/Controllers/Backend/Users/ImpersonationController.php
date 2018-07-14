<?php namespace Aham\Http\Controllers\Backend\Users;

use View;
use Assets;
use Sentinel;
use Aham\Models\SQL\User;
use Illuminate\Http\Request;

use Aham\Http\Controllers\Backend\BaseController;



class ImpersonationController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function impersonate(Request $request, $userId)
    {
        $request->session()->flush();

        // We will store the original user's ID in the session so we can remember who we
        // actually are when we need to stop impersonating the other user, which will
        // allow us to pull the original user back out of the database when needed.
        $request->session()->put(
            'aham:impersonator', $request->user()->id
        );

        Sentinel::login(User::findOrFail($userId));

        return redirect()->to('/');
    }

        /**
     * Stop impersonating and switch back to primary account.
     *
     * @param  Request  $request
     * @return Response
     */
    public function stopImpersonating(Request $request)
    {
        $currentId = Sentinel::getUser()->id;

        // We will make sure we have an impersonator's user ID in the session and if the
        // value doesn't exist in the session we will log this user out of the system
        // since they aren't really impersonating anyone and manually hit this URL.
        if (! $request->session()->has('aham:impersonator')) {
            Sentinel::logout();

            return redirect('/');
        }

        $userId = $request->session()->pull(
            'aham:impersonator'
        );

        // After removing the impersonator user's ID from the session so we can retrieve
        // the original user. Then, we will flush the entire session to clear out any
        // stale data from while we were doing the impersonation of the other user.
        $request->session()->flush();

        Sentinel::login(User::findOrFail($userId));

        return redirect()->route('admin::users::users.show',$currentId);
    }


}
