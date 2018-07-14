<?php

namespace Aham\Http\Controllers\Backend\LocationsMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\Locality;
use Aham\Models\SQL\City;
use Aham\Models\SQL\State;
use Aham\Models\SQL\Country;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\LocationCalendar;
use Aham\Models\SQL\DayType;
use Aham\Models\SQL\ClassroomSlot;
use Aham\Models\SQL\ClassTiming;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;

class LocationsController extends BaseController
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
        $tableRoute = route('admin::locations_mgmt::locations.table');

        return view('backend.locations_mgmt.locations.index', compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::pluck('name', 'id');
        $localities = Locality::pluck('name', 'id');

        return view('backend.locations_mgmt.locations.create', compact('cities', 'localities'));
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
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'locality_id' => 'required|exists:localities,id',
            'pincode' => 'required|numeric',
            'landmark' => 'required',
            'street_address' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        Location::create(Input::only('name', 'city_id', 'pincode', 'landmark', 'street_address', 'locality_id', 'currency_type', 'credits_type'));

        return redirect()->route('admin::locations_mgmt::locations.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Location::with('locationCalendars.dayType')->find($id);

        // $date = Carbon::createFromTimestamp(strtotime('02-07-2016'));

        // $c = LocationCalendar::with('dayType')
        //                     ->where('location_id',$location->id)
        //                     ->where('from_date','<=',$date)
        //                     ->where('to_date','>=',$date)
        //                     ->first();

        // $classrooms = $location->classrooms->pluck('id')->toArray();

        // $slots = ClassroomSlot::where('day_type_id',$c->dayType->id)
        //                         ->whereIn('classroom_id',$classrooms)
        //                         ->pluck('slot_id')
        //                         ->toArray();

        // $booked_slots = ClassTiming::where('date',$date)
        //                            ->where('location_id',$location->id)
        //                            ->pluck('slot_id')
        //                            ->toArray();

        // dd($slots);

        $day_types = DayType::notWeekend()->pluck('name', 'id');

        return view('backend.locations_mgmt.locations.show', compact('location', 'day_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Assets::add('js/plugins/pickers/location/location.js');

        $location = Location::find($id);

        $cities = City::pluck('name', 'id');
        $localities = Locality::pluck('name', 'id');

        return view('backend.locations_mgmt.locations.edit', compact('location', 'cities', 'localities'));
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
        // dd(Input::all());

        $rules = [
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'locality_id' => 'required|exists:localities,id',
            'pincode' => 'required|numeric',
            'landmark' => 'required',
            'street_address' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $location = Location::find($id);

        $location->fill(Input::only('name', 'city_id', 'pincode', 'landmark', 'street_address', 'latitude', 'longitude', 'locality_id', 'currency_type', 'credits_type'));

        if (Input::has('active')) {
            $location->active = true;
        } else {
            $location->active = false;
        }

        if (Input::has('repeat_class')) {
            $location->repeat_class = true;
        } else {
            $location->repeat_class = false;
        }

        $location->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Location::destroy($id);

        return redirect()->route('admin::locations_mgmt::locations.index');
    }


    public function table()
    {
        $output_mode = Input::get('o', 'json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('locations.manage');

        $locationsModel = Location::with('city')
                                ->where(function ($query) use ($q) {
                                    $query->orwhere('name', 'LIKE', '%'.$q.'%')
                                    ->orWhereHas('city', function ($query) use ($q) {
                                        $query
                                        ->where('name', 'LIKE', '%'.$q.'%');
                                    });
                                })
                                ->whereIn('id', $accessibleLocations);

        $iTotalRecords = $locationsModel->count();
        $iDisplayLength = intval(Input::get('length', 10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval(Input::get('start', 0));
        $sEcho = intval(Input::get('draw', 1));


        $locations = $locationsModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column, $sort)
                        ->get();

        $records = array();
        $records["data"] = array();

        foreach ($locations as $location) {
            $view_link = route('admin::locations_mgmt::locations.show', $location->id);
            $edit_link = route('admin::locations_mgmt::locations.edit', $location->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$view_link'><i class='icon-eye'></i> View</a></li>
                                    <li><a href='$edit_link'><i class='icon-pencil'></i> Edit</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['locations']['name'] = "<a href='$view_link'>".$location->name.'</a>';
            $row['locations']['code'] = $location->code;
            $row['locations']['credits_type'] = $location->credits_type;
            $row['locations']['currency_type'] = $location->currency_type;
            $row['locations']['city_id'] = $location->city->name;
            $row['locations']['locality_id'] = $location->locality->name;
            $row['locations']['id'] = $location->id;
            $row['locations']['created_at'] = $location->created_at->format('jS M Y');
            $row['locations']['actions'] = $actions;

            $records["data"][] = $row;
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    /**********************/

    public function addCalendar()
    {
        $rules = [
            'location_id' => 'required|exists:locations,id',
            'day_type_id' => 'required|exists:day_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $data = Input::only('location_id', 'day_type_id', 'from_date', 'to_date');

        $from_date = Carbon::createFromTimestamp(strtotime($data['from_date']));
        $to_date = Carbon::createFromTimestamp(strtotime($data['to_date']));

        $exists = LocationCalendar::where('location_id', $data['location_id'])
                                ->where(function ($query) use ($from_date,$to_date) {
                                    $query->whereBetween('from_date', [$from_date,$to_date])
                                          ->orWhereBetween('to_date', [$from_date,$to_date]);
                                })
                                ->first();

        if ($exists) {
            flash()->error('An entry for this date range already exists!');
            return redirect()->back();
        } else {
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;

            LocationCalendar::create($data);

            flash()->success('Successfully added');
        }

        return redirect()->back();
    }


    public function deleteCalendar($id)
    {
        LocationCalendar::destroy($id);

        flash()->success('Successfully deleted');

        return redirect()->back();
    }
}
