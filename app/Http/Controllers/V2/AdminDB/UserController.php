<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\UserPermission;

use Illuminate\Http\Request;
use Input;
use Sentinel;

class UserController extends Controller 
{
    public function __construct()
    {

    }

    public function updatePermissions(Request $request,$id)
    {
        $tutor = Teacher::find($id);

        $user = $tutor->user;

        // dd($user->permissions);

        $loggedInUser = Sentinel::getUser();

        if($user->id == $loggedInUser->id)
        {
            dd('Sorry, you cannot manage your own permissions');
        }

        $user->permissions()->delete();

        /** Global Permissions **/
        // dd(Input::has('isSuperAdmin'), Input::has('globalPermission'));
        
        if(!Input::has('isSuperAdmin'))
        {

            if(Input::has('globalPermission'))
            {
                foreach(Input::get('globalPermission') as $key => $permission)
                {
                    // dd();

                    // dd(['permission' => $permission['label']]);

                    UserPermission::create(
                                            ['user_id' => $user->id,
                                            'permission' => $permission['label']]
                                          );

                    // $up = new UserPermission(['permission' => $permission['label']]);
                    
                    // $user->permissions()->save($up);
                    // dd($user->permissions,['permission' => $permission['label']]);
                }

            }

            // dd($user->permissions);

            // dd('test');
            
            // dd('done');

            /** Location specific **/

            if(Input::has('hubs'))
            {
                foreach(Input::get('hubs') as $pu_id => $permissions)
                {
                    // dd($pu_id);

                    foreach($permissions as $key => $permission)
                    {   
                        if (is_array($permission)){

                            foreach($permission as $permission) {
                                // dd($permission['label']);

                                $up = new UserPermission([
                                            'permission'=> $permission['label'],
                                            'of_id' => $location->id,
                                            'of_type' => 'Aham\Models\SQL\Location',
                                        ]);

                                $user->permissions()->save($up);
                            }
                            // dd($permission,'before',$location->id);

                            
                        }  else {
                            $location =  Location::where('name',$permission)->first();
                            // dd($location);
                        }     
                        
                        // var_dump('after',$location->id);

                    }
                    // dd();
                }

            }

        }

        // dd($user->permissions);

        $superuserRole = Sentinel::findRoleBySlug('superuser');
        // dd($superuserRole);

        /** Super User **/

        // 1. Already a superuser and superuser is selected

        if($user->isSuperUser() && Input::has('isSuperAdmin'))
        {   
            // dd($user);

            $user->permissions()->delete();
        }

        // 2. Not a superuser and superuser is selected

        if(!$user->isSuperUser() && Input::has('isSuperAdmin'))
        {
            $user->permissions()->delete();
            $superuserRole->users()->attach($user);
        }

        // 3. already a superuser and not superuser is not selected

        if($user->isSuperUser() && !Input::has('isSuperAdmin'))
        {
            $user->permissions()->delete();
            $superuserRole->users()->detach($user);
        }

        $user->save();

        return response()->json([
                    'success' => true
               ],200);

        // flash()->success('Updated permissions successfully');

        // return redirect()->back();

    }

}