<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\City;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\GuestSeriesLevel;
use Aham\Models\SQL\UserEnrollment;

use Aham\Interactions\ClassSchedule;
use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

use Aham\Managers\ClassStatusManager;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use Artisan;

class GuestSeriesController extends BaseController
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
        $tableRoute = route('admin::classes_mgmt::guest_series.table');

        return view('backend.classes_mgmt.guest_series.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = Topic::has('units')
                        ->topic()
                        ->active()
                        ->orderBy('name','asc')
                        ->pluck('name','id');

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $locations = Location::whereIn('id',$accessibleLocations)->pluck('name','id');

        return view('backend.classes_mgmt.guest_series.create',compact('topics','locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = [
            'location_id' => 'required|exists:locations,id',
            'name' => 'required',
            'level_1' => 'required',
            'enrollment_restriction' => 'required',
            'enrollment_user' => 'required',
            'enrollment_type' => 'required',
            'cost_per_episode' => 'required|numeric',
        ];

        // dd(Input::all());

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $series = GuestSeries::create(Input::only('location_id','name','enrollment_restriction','enrollment_type','enrollment_user','cost_per_episode'));

        $series->creator_id = \Sentinel::getUser()->id;

        $series->status = 'initiated';

        if(Input::has('free'))
        {
            $series->free = true;
        }

        $series->save();

        $level = new GuestSeriesLevel(['name' => Input::get('level_1')]);

        $series->levels()->save($level);

        return redirect()->route('admin::classes_mgmt::guest_series.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $guestSeries = GuestSeries::with('levels.episodes.enrollments')->find($id);

        // dd($guestSeries->levels->first()->episodes);

        return view('backend.classes_mgmt.guest_series.show',compact('guestSeries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $guestSeries = GuestSeries::find($id);

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $locations = Location::whereIn('id',$accessibleLocations)->pluck('name','id');

        return view('backend.classes_mgmt.guest_series.edit',compact('guestSeries','locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $rules = [
            'location_id' => 'required|exists:locations,id',
            'name' => 'required',
            'enrollment_restriction' => 'required',
            'enrollment_user' => 'required',
            'enrollment_type' => 'required',
            'cost_per_episode' => 'required|numeric',
        ];

        // dd(Input::all());

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            // dd($v->errors());
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $series = GuestSeries::find($id);

        $series->fill(Input::only('location_id','name','enrollment_restriction','enrollment_type','enrollment_user','cost_per_episode','requirement','optional','description'));

        $series->save();


        return redirect()->route('admin::classes_mgmt::guest_series.show',$series->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AhamClass::destroy($id);

        return redirect()->back();

    }


    public function table()
    {

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $seriesModel = GuestSeries::with('location')
                                ->where(function ($query) use ($q) {
                                    
                                    $query
                                    ->whereHas('location', function($query) use ($q)
                                    {
                                        $query
                                        ->where('name', 'LIKE', '%'.$q.'%');
                                    });

                                })
                                ->whereIn('location_id',$accessibleLocations);


        if(count(Input::get('class_status')))
        {
            $seriesModel = $seriesModel->whereIn('status',Input::get('class_status'));
        }

        $iTotalRecords = $seriesModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $serieses = $seriesModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($serieses as $series)
        {
            $view_link = route('admin::classes_mgmt::guest_series.show',$series->id);
            $edit_link = route('admin::classes_mgmt::guest_series.edit',$series->id);
            $delete_link = route('admin::classes_mgmt::guest_series.delete',$series->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$view_link'><i class='icon-eye'></i> View</a></li>
                                    <li><a href='$edit_link'><i class='icon-pencil'></i> Edit</a></li>
                                    <li><a href='$delete_link' class='rest' data-method='DELETE'><i class='icon-cross'></i> Delete</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];


            $row['guest_series']['code'] = "<a href='$view_link'>".$series->code.'</a>';
            $row['guest_series']['name'] = $series->name;
            $row['guest_series']['enrollment_restriction'] = $series->enrollment_restriction;
            $row['guest_series']['enrollment_user'] = $series->enrollment_user;
            $row['guest_series']['enrollment_type'] = $series->enrollment_type;
            $row['guest_series']['levels'] = $series->levels->count();
            $row['guest_series']['episodes'] = $series->episodes->count();
            
            $row['guest_series']['location_id'] = $series->location->name;
            $row['guest_series']['creator_id'] = $series->creator->email;
            $row['guest_series']['created_at'] = $series->created_at->format('jS M Y');

            $row['guest_series']['actions'] = $actions;

            $row['guest_series']['status'] = '<span class="label label-primary">'.ucfirst($series->status).'</span>';

            if($series->free)
            {
                $row['guest_series']['free'] = '<span class="label label-success">Yes</span>';
            }
            else
            {
                $row['guest_series']['free'] = '<span class="label label-danger">No</span>';
            }


            $records["data"][] = $row;
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function cancelClassModal($id)
    {
        $guestSeries = GuestSeries::find($id);
        
        return view('backend.classes_mgmt.guest_series._cancel_modal',compact('guestSeries'));
    }

    public function cancelClass($id)
    {
        $guestSeries = GuestSeries::with('levels.episodes.timings')->find($id);

        $rules = [
            'confirm' => 'required|in:DELETE',
            'remarks' => 'required',
        ];

        $messages = [
            'confirm.in' => 'Please type DELETE to confirm'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        // dd($guestSeries);

        $guestSeries->status = 'cancelled';
        $guestSeries->cancelled_at = Carbon::now();
        $guestSeries->cancellation_reason = Input::get('remarks');
        $guestSeries->save();

        foreach($guestSeries->levels as $level)
        {
            foreach($level->episodes as $episode)
            {
                foreach($episode->timings as $timing)
                {
                    $timing->status = 'cancelled';
                    $timing->save();
                }
            }
        }

        // ClassStatusManager::giveBackCredits($ahamClass);

        // event(new \Aham\Events\AdminCancelledClass($ahamClass));

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully cancelled']]
        ), 200);

    }

    public function sendEnrollmentEmails($id)
    {
        $guestSeries = GuestSeries::with('levels.episodes.timings')->find($id);

        if($guestSeries->enrollment_restriction == 'restrict_by_level')
        {
            $enrollments = UserEnrollment::whereIn('episode_id',$guestSeries->levels->pluck('id')->toArray())->where('type','level')->get();

            foreach($enrollments as $enrollment)
            {
                $this->dispatch(new \Aham\Jobs\SendLevelWorkshopConfirmMail($enrollment));
            }

        }

        return redirect()->back();
        
    }


}
