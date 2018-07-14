<?php

namespace Aham\Http\Controllers\V2\Common;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\CategoryRepository;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Permission;
use Aham\Models\SQL\User;

class DataController extends Controller
{
    public function __construct()
    {

    }

    public function tutors(Request $request)
    {
        $tutors = Teacher::with('user')->get();

        $tutorTree = [];

        foreach ($tutors as $tutor) {
            $tutorBranch = [];
            $tutorBranch['value'] = $tutor->id;
            $tutorBranch['label'] = $tutor->user->name;
            $tutorTree[] = $tutorBranch;
        }

        return $tutorTree;
    }

    public function getPermissionData()
    {
        return  Permission::select('description','permission','group')->get();
    }

    public function getUserPermission($id)
    {
       $user =  Teacher::find($id)->user;

    //    dd($user->permissions);

       $permissionArray =  Permission::select('permission','description')->get()->toArray();
       $data = [];

       foreach ($permissionArray as $permission) {
              // dd();
              
            $set[] =  [
                        'value' =>  $user->first()->can($permission['permission']),
                        'label' =>  $permission['permission'],
                        'description' => $permission['description'],
                      ];

            $data = $set;
       }

       return $data;
    }

    public function getHubPermission($id)
    {
       $locations =  Location::all();
       $user = Teacher::find($id)->user;

       $data = [];

    // dd($user);
        foreach ($locations as $location) {

            $set['hubLabel'] =  $location->name;

            $set['data'] =  [
                                [
                                'value' =>  $user->can('locations.manage',$location->id),
                                'label' =>  'locations.manage',
                                'description' => 'Access to manage a location including editing it, managing calendar, classrooms etc., (Locations - locations.manage)',
                                ],

                                [
                                'value' =>  $user->can('classes.manage',$location->id),
                                'label' =>  'classes.manage',
                                'description' => 'Access to create, edit, manage a class (Classes - classes.manage)',
                                ],

                            ];


            $data[] = $set;
        }

        // dd($data);

       return $data;
    }

    public function hubs()
    {
        $locations =  Location::all();

        $hubs = [];

        foreach ($locations as $location) {
            $hub = [];
            $hub['value'] = $location->id;
            $hub['label'] = $location->name;
            $hubs[] = $hub;
        }

        return $hubs;
    }

}
