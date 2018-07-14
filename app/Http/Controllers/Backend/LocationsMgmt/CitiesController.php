<?php

namespace Aham\Http\Controllers\Backend\LocationsMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\City;
use Aham\Models\SQL\State;
use Aham\Models\SQL\Country;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;

class CitiesController extends BaseController
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
        $cities = City::with('state.country')->get();

        $india = Country::where('slug','india')->first();

        $states = $india->states()->pluck('name','id');

        return view('backend.locations_mgmt.cities.index',compact('states','cities'));
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
            'state_id' => 'required|exists:states,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        City::create(Input::only('name','state_id'));

        return redirect()->route('admin::locations_mgmt::cities.index');
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
        $city = City::with('state.country')->find($id);

        $india = Country::where('slug','india')->first();

        $states = $india->states()->pluck('name','id');

        return view('backend.locations_mgmt.cities.edit',compact('states','city'));
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
            'state_id' => 'required|exists:states,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $city = City::find($id);

        $city->fill(Input::only('state_id','name'));

        $city->save();

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
}
