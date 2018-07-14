<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Page;
use App\MobileOtp;
use Venturecraft\Revisionable\Revision;

use App\Http\Controllers\Backend\BaseController;

use Input;
use Validator;

class RevisionsController extends BaseController
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
        $tableRoute = route('admin::content::revisions.table');

        return view('backend.content.revisions.index',compact('tableRoute'));
    }

    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $revisionsModel = new Revision();

        $iTotalRecords = $revisionsModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));

        $revisions = $revisionsModel
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($revisions as $revision)
        {
            // dd($revision);

            $row = [];

            $row['revisions']['id'] = $revision->id;
            $row['revisions']['time'] = $revision->created_at->format('jS M Y h:i a');

            if($revision->key == 'created_at')
            {
                $row['revisions']['action'] = 'created';
                $row['revisions']['user'] = $revision->user_id;
                $row['revisions']['fieldname'] = 'NA';
                $row['revisions']['oldvalue'] = 'NA';
                $row['revisions']['newvalue'] = $revision->new_value;
                $row['revisions']['model_id'] = $revision->revisionable_id;
                $row['revisions']['model_type'] = $revision->revisionable_type;
            }
            else
            {
                $row['revisions']['action'] = 'updated';
                $row['revisions']['user'] = $revision->user_id;
                $row['revisions']['fieldname'] = $revision->key;
                $row['revisions']['oldvalue'] = $revision->old_value;
                $row['revisions']['newvalue'] = $revision->new_value;
                $row['revisions']['model_id'] = $revision->revisionable_id;
                $row['revisions']['model_type'] = $revision->revisionable_type;
            }

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }
}
