<?php

namespace Aham\Http\Controllers\Backend\LocationsMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\City;
use Aham\Models\SQL\State;
use Aham\Models\SQL\Country;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Classroom;
use Aham\Models\SQL\ClassroomSlot;
use Aham\Models\SQL\DayType;
use Aham\Models\SQL\Slot;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Carbon;

class ClassroomsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $location = Location::find($id);

        return view('backend.locations_mgmt.classrooms.create',compact('location'));
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
            'size' => 'required|numeric',
            'location_id' => 'required|exists:locations,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        Classroom::create(Input::only('name','location_id','size'));

        return redirect()->route('admin::locations_mgmt::locations.show',Input::get('location_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classroom = Classroom::with('location','classroomSlots')->find($id);

        $location = $classroom->location;

        $day_types = DayType::all();

        $day_type_slots = [];

        foreach($day_types as $day_type){

            $day_type_slots[$day_type->id] = $day_type
                                                ->slots()
                                                ->select('id as id',\DB::raw("CONCAT(start_time,' - ', end_time) as text"))
                                                ->get()
                                                ->toArray();

        }

        $day_types = DayType::select('id as id','name as text')
                                                    ->get()
                                                    ->toArray();

        $day_type_slots = json_encode($day_type_slots);

        $day_types = DayType::pluck('name','id');

        return view('backend.locations_mgmt.classrooms.edit',compact('classroom','location','day_types','day_type_slots'));
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
            'name' => 'required',
            'size' => 'required|numeric'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $classroom = Classroom::find($id);

        $classroom->fill(Input::only('name','size'));

        if(Input::has('active'))
        {
            $classroom->active = true;
        }
        else
        {
            $classroom->active = false;
        }

        $classroom->save();

        flash()->success('Updated Successfully');

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
        City::destroy($id);

        return redirect()->route('admin::locations_mgmt::cities.index');

    }

    public function addSlot($id)
    {
        $rules = [
            'day_type_id' => 'required|exists:day_types,id',
            'slot_id' => 'required|exists:slots,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $classroom = Classroom::find($id);

        $slot = Slot::find(Input::get('slot_id'));

        $data = Input::only('day_type_id','slot_id');

        $start_time = Carbon::createFromTimestamp(strtotime($slot->start_time));
        $end_time = Carbon::createFromTimestamp(strtotime($slot->end_time));


        $exists = ClassroomSlot::where('day_type_id',$data['day_type_id'])
                                ->where('classroom_id',$classroom->id)
                                ->where(function ($query) use($start_time,$end_time) {
                                    $query->whereBetween('start_time', [$start_time,$end_time])
                                          ->orWhereBetween('end_time', [$start_time,$end_time]);
                                })
                                ->first();

        if($exists)
        {
            flash()->error('Classroom is already occupied for this slot on this day type');
            return redirect()->back();
        }
        else
        {

            $data['start_time'] = $start_time;
            $data['end_time'] = $end_time;

            $classroom_slot = new ClassroomSlot($data);

            $classroom->classroomSlots()->save($classroom_slot);
        }

        flash()->success('Classroom Slot Successfully added');

        return redirect()->back();
    }

    public function removeSlot($id)
    {
        ClassroomSlot::destroy($id);

        flash()->success('Classroom Slot Successfully deleted');

        return redirect()->back();

    }


}
