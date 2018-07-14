<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\User;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Carbon;

class CouponsController extends BaseController
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
        $tableRoute = route('admin::users::coupons.table');

        return view('backend.users.coupons.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = [0 => 'All'] + User::pluck('name','id')->toArray();

        return view('backend.users.coupons.create',compact('users'));
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
            'coupon' => 'required|unique:coupons,coupon',
            'valid_from' => 'required|date',
            'valid_till' => 'date|required_unless:type,lifetime',
            'type' => 'required|in:one-time,multiple,lifetime',
            'additional_type' => 'required|in:additional_units,additional_percent',
            'additional_value' => 'required|min:1',
            'max_usage_limit_per_user' => 'required|numeric',
            'max_users_limit' => 'required|numeric',
            'min_units_to_purchase' => 'required|numeric',
            'description' => 'required'
        ];

        $messages = [
            'valid_till.required_unless' => 'This field is required'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        if(Input::get('type') == 'one-time')
        {
            $data = Input::except('_token');
            $data['valid_from'] = Carbon::createFromTimestamp(strtotime($data['valid_from']));
            $data['valid_till'] = Carbon::createFromTimestamp(strtotime($data['valid_till']));
            $data['active'] = true;

            $coupon = Coupon::create($data);
        }

        if(Input::get('type') == 'multiple')
        {
            $data = Input::except('_token');
            $data['valid_from'] = Carbon::createFromTimestamp(strtotime($data['valid_from']));
            $data['valid_till'] = Carbon::createFromTimestamp(strtotime($data['valid_till']));
            $data['active'] = true;

            // dd($data);

            $coupon = Coupon::create($data);
        }

        if(Input::get('type') == 'lifetime')
        {
            $data = Input::except('_token');
            $data['valid_from'] = Carbon::createFromTimestamp(strtotime($data['valid_from']));
            $data['valid_till'] = null;
            $data['active'] = true;

            // dd($data);

            $coupon = Coupon::create($data);
        }

        return redirect()->route('admin::users::coupons.index');
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
        $coupon = Coupon::find($id);

        if($coupon->usage->count())
        {
            flash()->error('Cannot delete. Already used.');

            return redirect()->route('admin::users::coupons.index');
        }

        $coupon->delete();

        return redirect()->route('admin::users::coupons.index');

    }


    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $couponsModel = Coupon::with('user')
                                ->where(function($query) use($q){
                                    $query->where('coupon', 'LIKE', '%'.$q.'%')
                                           ->orWhereHas('user', function($query) use($q){
                                                $query
                                                    ->where('name','LIKE','%'.$q.'%');
                                            });
                                });

        $iTotalRecords = $couponsModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $coupons = $couponsModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($coupons as $coupon)
        {
            $view_link = route('admin::users::coupons.show',$coupon->id);
            $edit_link = route('admin::users::coupons.edit',$coupon->id);
            $delete_link = route('admin::users::coupons.delete',$coupon->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$delete_link' data-method='DELETE' class='rest'><i class='icon-eye'></i> Delete</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['coupons']['coupon'] = $coupon->coupon;

            $row['coupons']['additional_type'] = $coupon->additional_type;
            $row['coupons']['additional_value'] = $coupon->additional_value;
            $row['coupons']['max_usage_limit_per_user'] = $coupon->max_usage_limit_per_user;
            $row['coupons']['max_users_limit'] = $coupon->max_users_limit;
            $row['coupons']['min_units_to_purchase'] = $coupon->min_units_to_purchase;
            $row['coupons']['valid_from'] = $coupon->valid_from->format('jS M Y');

            if($coupon->type != 'lifetime')
            {
                $row['coupons']['valid_till'] = $coupon->valid_till->format('jS M Y');
            }
            else
            {
                $row['coupons']['valid_till'] = 'NA';
            }
            
            $row['coupons']['type'] = $coupon->type;

            if($coupon->user_id == 0)
            {
                $row['coupons']['user_id'] = 'All';
            }
            else
            {
                $row['coupons']['user_id'] = $coupon->user->name;
            }

            if($coupon->active)
            {
                $row['coupons']['active'] = 'Yes';
            }
            else
            {
                $row['coupons']['active'] = 'No';
            }

            $row['coupons']['actions'] = $actions;

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }
}
