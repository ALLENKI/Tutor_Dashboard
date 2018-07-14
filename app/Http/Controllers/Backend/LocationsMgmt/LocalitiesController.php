<?php

namespace Aham\Http\Controllers\Backend\LocationsMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\Locality;
use Aham\Models\SQL\City;
use Aham\Models\SQL\State;
use Aham\Models\SQL\Country;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;

class LocalitiesController extends BaseController
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
        $localities = Locality::with('city.state.country')->get();

        $cities = City::with('state.country')->pluck('name','id');

        $india = Country::where('slug','india')->first();

        $states = $india->states()->pluck('name','id');

        return view('backend.locations_mgmt.localities.index',compact('states','cities','localities'));
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
            'city_id' => 'required|exists:cities,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        Locality::create(Input::only('name','city_id'));

        return redirect()->route('admin::locations_mgmt::localities.index');
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
        $locality = Locality::with('city.state.country')->find($id);

        $cities = City::with('state.country')->pluck('name','id');

        return view('backend.locations_mgmt.localities.edit',compact('cities','locality'));
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
            'city_id' => 'required|exists:cities,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $locality = Locality::find($id);

        $locality->fill(Input::only('city_id','name'));

        $locality->save();

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
        Locality::destroy($id);

        return redirect()->route('admin::locations_mgmt::localities.index');

    }
}
