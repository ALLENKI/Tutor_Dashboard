<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\CouponTemplate;
use Aham\Models\SQL\User;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Carbon;

class CouponTemplatesController extends BaseController
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
        $tableRoute = route('admin::users::coupon_templates.table');

        return view('backend.users.coupon_templates.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name','id')->toArray();

        return view('backend.users.coupon_templates.create',compact('users'));
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
            'issuance_limit' => 'required|min:1',
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

            $coupon = CouponTemplate::create($data);
        }

        if(Input::get('type') == 'multiple')
        {
            $data = Input::except('_token');
            $data['valid_from'] = Carbon::createFromTimestamp(strtotime($data['valid_from']));
            $data['valid_till'] = Carbon::createFromTimestamp(strtotime($data['valid_till']));
            $data['active'] = true;

            // dd($data);

            $coupon = CouponTemplate::create($data);
        }

        if(Input::get('type') == 'lifetime')
        {
            $data = Input::except('_token');
            $data['valid_from'] = Carbon::createFromTimestamp(strtotime($data['valid_from']));
            $data['valid_till'] = null;
            $data['active'] = true;

            // dd($data);

            $coupon = CouponTemplate::create($data);
        }

        return redirect()->route('admin::users::coupon_templates.show',$coupon->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = CouponTemplate::find($id);

        // \Aham\Helpers\GenerateCouponsHelper::generate($id);

        // dd($template->users->pluck('id')->toArray());

        $users = User::has('student')
                        ->whereNotIn('id',$template->users->pluck('id')->toArray())
                        ->pluck('name','id')
                        ->toArray();

        return view('backend.users.coupon_templates.show',compact('template','users'));
    }

    public function generate($id)
    {
        \Aham\Helpers\GenerateCouponsHelper::generate($id);

        flash()->success('Generated Coupons for the users');

        return redirect()->back();
    }

    public function addUser($id)
    {
        $template = CouponTemplate::find($id);

        $template->users()->sync(array_merge(Input::get('user_id'), $template->users->pluck('id')->toArray()));

        return redirect()->back();

    }

    public function removeUser($template, $user)
    {
        $template = CouponTemplate::find($template);

        $template->users()->detach($user);

        return redirect()->back();
    }

    public function controlStatus($id)
    {
        $template =  CouponTemplate::find($id);

        if(Input::has('active'))
        {
            $template->active = true;
        }

        if(Input::has('inactive'))
        {
            $template->active = false;
        }

        $template->save();

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
        $coupon = CouponTemplate::find($id);

        if($coupon->usage->count())
        {
            flash()->error('Cannot delete. Already used.');

            return redirect()->route('admin::users::coupon_templates.index');
        }

        $coupon->delete();

        return redirect()->route('admin::users::coupon_templates.index');

    }


    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $couponsModel = CouponTemplate::where('coupon', 'LIKE', '%'.$q.'%');

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
            $view_link = route('admin::users::coupon_templates.show',$coupon->id);
            $edit_link = route('admin::users::coupon_templates.edit',$coupon->id);
            $delete_link = route('admin::users::coupon_templates.delete',$coupon->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$view_link'><i class='icon-eye'></i> View</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['coupons']['coupon'] = $coupon->coupon;

            $row['coupons']['additional_type'] = $coupon->additional_type;
            $row['coupons']['additional_value'] = $coupon->additional_value;
            $row['coupons']['max_usage_limit_per_user'] = $coupon->max_usage_limit_per_user;
            $row['coupons']['max_users_limit'] = $coupon->max_users_limit;
            $row['coupons']['issuance_limit'] = $coupon->issuance_limit;
            $row['coupons']['remaining_coupons'] = $coupon->issuance_limit - $coupon->users->count();
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
