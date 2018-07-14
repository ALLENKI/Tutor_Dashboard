<?php

namespace Aham\Helpers;

use Carbon;
use DB;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassTiming;

class ClassStatusHelper {

    public static function status($ahamClass)
    {
        if($ahamClass->status == 'cancelled')
        {
            return true;
        }

        // if(($ahamClass->timings->count() != $ahamClass->topic->units->count()))
        // {
        //     $ahamClass->status = 'initiated';
        //     $ahamClass->save();
        // }

        // dd($ahamClass->timings->count());

        if(($ahamClass->timings->count() == $ahamClass->topic->units->count()) && $ahamClass->status == 'initiated')
        {
            $ahamClass->status = 'created';
            $ahamClass->save();
        }

        if($ahamClass->invitations->count() && $ahamClass->status == 'created')
        {
            $ahamClass->status = 'invited';
            $ahamClass->save();
        }

        if($ahamClass->invitations()->where('status','accepted')->count()  && $ahamClass->status == 'invited')
        {
            $ahamClass->status = 'accepted';
            $ahamClass->save();
        }

        if($ahamClass->teacher  && $ahamClass->status == 'accepted')
        {
            $ahamClass->status = 'ready_for_enrollment';
            $ahamClass->save();
        }


        return true;
    }

}
