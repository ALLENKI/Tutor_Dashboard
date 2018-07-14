<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Slot;

use Aham\Interactions\ClassSchedule;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use DB;

class ClassStatusController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function unitDoneModal($class, $unit)
    {
        $ahamClass = AhamClass::find($class);

        return view('backend.classes_mgmt.classes._unitdone_modal',compact('ahamClass','unit'));
    }

    public function unitDoneModalPost($class, $unit)
    {
        $ahamClass = AhamClass::find($class);

        $timing = ClassTiming::where('class_id',$class)
               ->where('unit_id',$unit)
               ->first();

        \DB::beginTransaction();

        $timing->status = 'done';
        $timing->remarks = Input::get('remarks','');
        $timing->save();

        if($ahamClass->topic->units->count() == $ahamClass->timings()->where('status','done')->count())
        {
            $ahamClass->status = 'get_feedback';
            $ahamClass->save();
        }

        \DB::commit();

        // $ahamClass = $ahamClass->refresh();

        if($ahamClass->status == 'get_feedback')
        {
            event(new \Aham\Events\Teacher\GetFeedback($ahamClass));
        }

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully scheduled']]

        ), 200);
    }
}
