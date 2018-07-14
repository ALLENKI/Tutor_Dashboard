<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\Permission;
use Aham\Models\SQL\UserPermission;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\User;

use Aham\Helpers\PermissionsHelper;

use Input;
use Sentinel;
use Activation;
use Validator;

class PermissionsController extends BaseController 
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Input::has('load'))
        {
            PermissionsHelper::load();
        }

        $permissions = Permission::all();

        return view('backend.permissions.index',compact('permissions'));
    }

    public function manage($id)
    {
        $user = User::find($id);
        $permissions = Permission::get()->groupBy('type')->toArray();
        $locations = Location::all();
        // $permissions = Permission::all();

        return view('backend.permissions.manage',compact('permissions','user','locations'));
    }

    public function updatePermissions($id)
    {
        // dd(Input::all());

        $user = User::find($id);

        $loggedInUser = Sentinel::getUser();

        if($user->id == $loggedInUser->id)
        {
            dd('Sorry, you cannot manage your own permissions');
        }

        $user->permissions()->delete();

        /** Global Permissions **/

        if(!Input::has('superuser'))
        {

            if(Input::has('global'))
            {
                foreach(Input::get('global') as $key => $permission)
                {
                    // dd(['permission'=>$key]);

                    $up = new UserPermission(['permission'=>$key]);
                    
                    $user->permissions()->save($up);
                }

            }

            /** Location specific **/

            if(Input::has('location'))
            {
                foreach(Input::get('location') as $pu_id => $permissions)
                {
                    foreach($permissions as $key => $permission)
                    {
                        // dd($key);

                        $up = new UserPermission([
                            'permission'=>$key,
                            'of_id' => $pu_id,
                            'of_type' => 'Aham\Models\SQL\Location',
                            ]);

                        $user->permissions()->save($up);
                    }
                }

            }

        }

        $superuserRole = Sentinel::findRoleBySlug('superuser');

        /** Super User **/

        // 1. Already a superuser and superuser is selected

        if($user->isSuperUser() && Input::has('superuser'))
        {
            $user->permissions()->delete();
        }

        // 2. Not a superuser and superuser is selected

        if(!$user->isSuperUser() && Input::has('superuser'))
        {
            $user->permissions()->delete();
            $superuserRole->users()->attach($user);
        }

        // 3. already a superuser and not superuser is not selected

        if($user->isSuperUser() && !Input::has('superuser'))
        {
            $user->permissions()->delete();
            $superuserRole->users()->detach($user);
        }

        $user->save();

        flash()->success('Updated permissions successfully');

        return redirect()->back();

    }

}
