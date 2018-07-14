<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;
use Aham\TDGateways\UserGatewayInterface;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Locality;
use Aham\Models\SQL\City;
use Aham\Models\SQL\CloudinaryImage;
use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\UserEnrollment;

use Input;
use Sentinel;
use Activation;
use Validator;

class UsersController extends BaseController 
{

    public function __construct(UserGatewayInterface $userGateway)
    {
        parent::__construct();

        $this->userGateway = $userGateway;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tableRoute = route('admin::users::users.table');

        return view('backend.users.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        $active = Activation::completed($user);

        return view('backend.users.show',compact('user','active'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $cities = City::pluck('name','id');
        $localities = Locality::pluck('name','id');

        return view('backend.users.edit',compact('user','cities','localities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'commission' => 'numeric',
            'interested_in' => 'required|in:user,student,teacher,student_teacher'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = User::find($id);

        $user->fill(Input::only('name','interested_in', 'locality_id', 'city_id'));

        $user->save();

        if($student = $user->student)
        {
            $student->fill(Input::only('grade','curriculum'));
            $student->save();
        }

        if($teacher = $user->teacher)
        {
            $teacher->fill(Input::only('commission'));

            if(Input::has('ignore_calendar'))
            {
                $teacher->ignore_calendar = true;
            }
            else
            {
                $teacher->ignore_calendar = false;
            }

            $teacher->show_on_homepage = false;

            if(Input::has('show_on_homepage'))
            {
                $teacher->show_on_homepage = true;
            }
        
            $teacher->save();
        }

        flash()->success('Updated Successfully!');

        return redirect()->route('admin::users::users.show',$user->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function toggleAdmin($id)
    {
        $user = User::find($id);

        if($user->hasAccess('admin'))
        {
            $user->removePermission('admin');
        }
        else
        {
            $user->addPermission('admin');
        }

        $user->save();

        return redirect()->back();
    }

    public function activate($id)
    {
        $user = User::find($id);

        $activation = Activation::exists($user);

        if(!$activation)
        {
            $activation = Activation::create($user);
        }

        Activation::complete($user, $activation->code);
        
        flash()->success('User successfully activated');

        return redirect()->back(); 
    }

    public function activateAndImpersonate(Request $request,$id)
    {
        $user = User::find($id);

        $activation = Activation::exists($user);

        if(!$activation)
        {
            $activation = Activation::create($user);
        }

        Activation::complete($user, $activation->code);

        $request->session()->flush();
            $request->session()->put(
            'aham:impersonator', $request->user()->id
        );

        Sentinel::login(User::findOrFail($id));

        return redirect()->route('settings::profile');
    }

    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $usersModel = User::where('email', 'LIKE', '%'.$q.'%')
                                ->orWhere('name', 'LIKE', '%'.$q.'%')
                                ->orWhere('username', 'LIKE', '%'.$q.'%');

        $iTotalRecords = $usersModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $users = $usersModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        $currentUser = Sentinel::getUser();

        foreach($users as $user)
        {
            $view_link = route('admin::users::users.show',$user->id);
            $edit_link = route('admin::users::users.edit',$user->id);
            $manage_permissions = route('admin::users::permissions.manage',$user->id);
            $toggle_admin_link = route('admin::users::users.toggle_admin',$user->id);

            $links = "<li><a href='$view_link'><i class='icon-eye'></i> View</a></li>";

            if($currentUser->can('permissions'))
            {
                $links .= "<li><a href='$manage_permissions'><i class='icon-pencil'></i> Manage Permission</a></li>";
            }

            if($currentUser->can('users.edit'))
            {
                $links .= "<li><a href='$edit_link'><i class='icon-eye'></i> Edit</a></li>
                                    ";
            }
            
            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>
                                <ul class='dropdown-menu dropdown-menu-right'>
                                    $links
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['users']['name'] = $user->name;

            if($user->isSuperUser())
            {
                $row['users']['name'] .= ' <span class="label label-primary">Super User</span>';
            }

            if($user->hasAccess('admin'))
            {
                $row['users']['name'] .= ' <span class="label label-primary">Admin</span>';
            }

            $row['users']['email'] = "<a href='$view_link'>".$user->email."</a>";
            $row['users']['username'] = $user->username;
            $row['users']['created_at'] = $user->created_at->format('jS M Y');
            if($user->last_login)
            {
               $row['users']['last_login'] = $user->last_login->format('jS M Y'); 
            }
            else
            {
                $row['users']['last_login'] = 'None';
            }
            $row['users']['interested_in'] = ucwords($user->interested_in);
            $row['users']['who_are_you'] = $user->who_are_you;
            $row['users']['actions'] = $actions;

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function uploadAvatar($id)
    {
        $user = User::find($id);

        $filename = time().'.jpg';

        $imagePath = storage_path('uploads/'.$filename);

        $parts = explode(',', Input::get('image-data'));

        $image = base64_decode($parts[1]);

        \File::put($imagePath,$image);

        $result = \Cloudinary\Uploader::upload($imagePath);

        \File::delete($imagePath);

        if($picture = $user->picture)
        {
            $api = new \Cloudinary\Api();

            $api->delete_resources(array($picture->public_id));

            $picture->fill(array_only($result,['public_id','format']));

            $picture->save();
        }
        else
        {
            $picture = new CloudinaryImage(array_only($result,['public_id','format']));

            $picture->type = 'picture';

            $user->picture()->save($picture);
        }

        flash()->overlay('Successfully changed','Success');

        return redirect()->back();
    }

    public function export()
    {
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=Users_'.\Carbon::now()->format('d-m-Y_h:i').'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $rows = \DB::table('users')
            
            ->select(\DB::raw("users.email as Email"), \DB::raw("users.name as Name"), \DB::raw("users.mobile as Mobile") )
            ->get();


       array_unshift($rows, array_keys((array) $rows[0]));

       $callback = function() use ($rows) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($rows as $row) {
                $row = (array) $row;

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Response::stream($callback, 200, $headers);
    }

    public function exportNonEnrolledSeries($id)
    {
        $series = GuestSeries::find($id);

        if($series->enrollment_restriction == 'restrict_by_episode')
        {

            $enrolled = UserEnrollment::whereIn('episode_id',$series->episodes->pluck('id')->toArray())
                        ->pluck('user_id')
                        ->toArray();
        }


        if($series->enrollment_restriction == 'restrict_by_level')
        {

            $enrolled = UserEnrollment::whereIn('episode_id',$series->levels->pluck('id')->toArray())
                        ->pluck('user_id')
                        ->toArray();
        }

        // dd($enrolled);

        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=Users_'.\Carbon::now()->format('d-m-Y_h:i').'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $rows = \DB::table('users')
            ->whereNotIn('id',$enrolled)
            ->select(\DB::raw("users.email as Email"), \DB::raw("users.name as Name") )
            ->get();


       array_unshift($rows, array_keys((array) $rows[0]));

       $callback = function() use ($rows) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($rows as $row) {
                $row = (array) $row;

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Response::stream($callback, 200, $headers);
    }

    public function exportEnrolledSeries($id)
    {
        $series = GuestSeries::find($id);

        $episodes = $series->episodes->pluck('id')->toArray();

        // dd($episodes);

        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=Users_'.\Carbon::now()->format('d-m-Y_h:i').'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];


        if($series->enrollment_restriction == 'restrict_by_episode')
        {

            $rows = \DB::table('users')
                ->join('user_enrollments', 'users.id', '=', 'user_enrollments.user_id')
                ->join('guest_series_episodes', 'user_enrollments.episode_id', '=', 'guest_series_episodes.id')
                ->whereIn('user_enrollments.episode_id',$episodes)
                ->orderBy('guest_series_episodes.enrollment_cutoff','asc')
                ->select(\DB::raw("users.email as Email"), 
                         \DB::raw("users.name as Name"),
                         \DB::raw("guest_series_episodes.date_summary as Date") )
                ->get();

        }


        if($series->enrollment_restriction == 'restrict_by_level')
        {
            // dd('gfgfd');

            $rows = \DB::table('users')
                ->join('user_enrollments', 'users.id', '=', 'user_enrollments.user_id')
                ->join('guest_series_levels', 'user_enrollments.episode_id', '=', 'guest_series_levels.id')
                ->whereIn('user_enrollments.episode_id',$series->levels->pluck('id')->toArray())
                ->orderBy('guest_series_levels.enrollment_cutoff','asc')
                ->where('user_enrollments.type','level')
                ->select(\DB::raw("users.email as Email"), 
                         \DB::raw("users.name as Name"),
                         \DB::raw("guest_series_levels.name as Level_Name") )
                ->get();

            // dd($rows);

        }

        if(count($rows))
        {

            array_unshift($rows, array_keys((array) $rows[0]));
        }
        else
        {
            flash()->overlay('There are no users enrolled','Try again later!');

            return redirect()->back();
        }


       $callback = function() use ($rows) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($rows as $row) {
                $row = (array) $row;

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Response::stream($callback, 200, $headers);
    }
}
