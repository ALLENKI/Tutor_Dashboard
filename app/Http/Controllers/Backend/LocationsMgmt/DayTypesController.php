<?php

namespace Aham\Http\Controllers\Backend\LocationsMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\DayType;
use Aham\Models\SQL\Slot;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Carbon;

class DayTypesController extends BaseController
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
        $day_types = DayType::all();

        return view('backend.locations_mgmt.day_types.index',compact('day_types'));
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
    public function store()
    {
        $rules = [
            'name' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        DayType::create(Input::only('name'));

        return redirect()->route('admin::locations_mgmt::day_types.index');
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
        $day_type = DayType::find($id);

        return view('backend.locations_mgmt.day_types.edit',compact('day_type'));
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
            'slug' => 'required|unique:day_types,slug,'.$id
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $day_type = DayType::find($id);

        $day_type->fill(Input::only('name','slug'));

        $day_type->save();

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
        DayType::destroy($id);

        return redirect()->route('admin::locations_mgmt::day_types.index');

    }

    /**
     * 
     */
    public function addSlot()
    {
        $rules = [
            'start_time' => 'required|date_format:H:i|unique:slots,start_time,NULL,id,day_type_id,'.Input::get('day_type_id').',deleted_at,NULL',
            'end_time' => 'required|date_format:H:i',
            'day_type_id' => 'required|exists:day_types,id',
        ];

        // dd($rules);

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $data = Input::only('start_time','end_time','day_type_id');

        $data['start_time'] = Carbon::createFromTimestamp(strtotime($data['start_time']));
        $data['end_time'] = Carbon::createFromTimestamp(strtotime($data['end_time']));

        Slot::create($data);

        return redirect()->back();

    }

    public function deleteSlot($id)
    {
        Slot::destroy($id);

        return redirect()->back();
    }
}
