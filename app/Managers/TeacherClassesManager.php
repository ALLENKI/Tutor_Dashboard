<?php

namespace Aham\Managers;
use Aham\Models\SQL\AhamClass;
use Carbon\Carbon;
class TeacherClassesManager
{
    public $teacher;

    public function __construct($teacher)
    {
        $this->teacher = $teacher;
    }
    // invitations
    public function getPendingInvitations()
    {
        return $this->teacher->invitations()
                    ->whereHas('ahamClass', function ($query) {
                        return $query->where('start_date', '>=', Carbon::now());
                    })
                    ->whereIn('status', ['pending'])
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10);
    }
    public function getAcceptedInvitations()
    {
        return $this->teacher->invitations()
                            ->accepted()
                            ->whereHas('ahamClass', function ($query) {
                                return $query->where('start_date', '>', Carbon::now());
                            })
                            ->paginate(10);
    }
    public function getAwardedInvitations()
    {
        return $this->teacher->invitations()
                            ->awarded()
                            ->whereHas('ahamClass', function ($query) {
                                return $query->where('start_date', '>', Carbon::now());
                            })
                            ->paginate(10);
    }
    public function getAllInvitations()
    {
        return $this->teacher->invitations()->paginate(10);
    }



    public function getUpcomingClasses()
    {
        $classes =  array_unique($this->teacher->classesTimings->pluck('class_id')->toArray());

        return $this->teacher->classes()
                            ->whereIn('id', $classes)
                            ->whereIn('status', ['scheduled'])
                            ->get();
    }

    
    public function getScheduledClasses()
    {
        $classes =  array_unique($this->teacher->classesTimings->pluck('class_id')->toArray());

        return $this->teacher->classes()
                            ->whereIn('id', $classes)
                            ->whereIn('status', ['open_for_enrollment'])
                            ->get();
    }
    /* TODO:
     * fixing bug,
     * bug:: admin need to manually change the status of classes to completed which effect things
     * fixing:: storing a session var
     */
    public function getInSessionClasses()
    {
        $classes =  array_unique($this->teacher->classesTimings->pluck('class_id')->toArray());


        return $this->teacher->classes()
                            ->whereIn('id', $classes)
                            ->where('status', ['in_session'])
                            ->get();
    }


    public function getOnGoingClassesTimings()
    {
        return $this->teacher
                    ->classesTimings()
                    ->whereNotIn('status', ['cancelled'])
                    ->where('date', '=', Carbon::now()->format('Y-m-d'))
                    ->whereRaw("TIMESTAMP(date,end_time) >= '" . Carbon::now() . "'")
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc')

                    ->paginate(12);
    }
    public function getUpComingClassesTimings()
    {
        return $this->teacher
                    ->classesTimings()
                    ->whereNotIn('status', ['cancelled'])
                    ->where('date', '>', Carbon::now()->format('Y-m-d'))
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->paginate(12);
    }
    public function getCompletedClassesTimings()
    {
        return $this->teacher
                    ->classesTimings()
                    ->whereRaw("TIMESTAMP(date,end_time) <= '" . Carbon::now() . "'")
                    ->whereHas('ahamClass', function ($q) {
                        $q->whereNotIn('status', ['cancelled']);
                    })
                    ->orderBy('date', 'desc')
                    ->orderBy('start_time', 'desc')
                    ->paginate(12);
    }
    public function getFeedbackClasses()
    {
        $classes =  array_unique($this->teacher->classesTimings->pluck('class_id')->toArray());

        return $this->teacher->classes()
                            ->whereIn('id',$classes)
                            ->whereIn('status', ['get_feedback'])
                            ->get();
    }
    public function getCompletedClasses()
    {
        return $this->teacher->classes()->whereIn('status', ['completed', 'got_feedback'])->get();
    }
    public function getProjectedAmount()
    {
        $suchClasses = $this->teacher->classes()->whereIn('status', ['scheduled', 'in_session', 'get_feedback', 'got_feedback'])->where('no_tutor_comp', false)->get();

        // $suchClasses = $this->teacher->classes()->get();
        // dd($suchClasses);
        $amount = 0;

        foreach ($suchClasses as $ahamClass) {
            $enrollmentCount = $ahamClass->enrollments->count();
            $enrollmentCount = $enrollmentCount > 4 ? $enrollmentCount : 4;
            if ($ahamClass->free) {
                $enrollmentCount = 4;
            }
            $totalWorth = $enrollmentCount * $ahamClass->classUnits->count() * 1000;
            $classAmount = ($ahamClass->commission / 100) * $totalWorth;
            $amount += $classAmount;
        }
        return $amount;
    }
}