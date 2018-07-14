<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Page;
use App\MobileOtp;
use App\Sms;
use Venturecraft\Revisionable\Revision;

use App\Http\Controllers\Backend\BaseController;

use Input;
use Validator;

class SMSController extends BaseController
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
        $tableRoute = route('admin::content::sms.table');

        return view('backend.content.sms.index',compact('tableRoute'));
    }

    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $smsModel = Sms::where('number', 'LIKE', '%'.$q.'%');

        $iTotalRecords = $smsModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));

        $sms = $smsModel
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($sms as $msg)
        {
            // dd($revision);

            $row = [];

            $row['sms']['id'] = $msg->id;
            $row['sms']['time'] = $msg->created_at->format('jS M Y h:i a');
            $row['sms']['number'] = $msg->number;
            $row['sms']['message'] = $msg->message;
            $row['sms']['result'] = $msg->result;


            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }
}
