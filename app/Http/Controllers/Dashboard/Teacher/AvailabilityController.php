<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;

use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\TeacherAvailability;

use Aham\Helpers\TeacherHelper;

use Validator;
use Input;
use Assets;
use Carbon;

class AvailabilityController extends TeacherDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->daysOfTheWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        $this->sessions = [
            'early_morning' => 'Early Morning (5:30 - 8:00)',
            'morning' => 'Morning (8:00 - 12:00)',
            'afternoon' => 'Afternoon (12:00 - 16:00)',
            'evening' => 'Evening (16:00 - 18:00)',
            'late_evening' => 'Late Evening (18:00 - 21:00)',
        ];
    }

    public function index()
    {   
        Assets::add('js/plugins/pickers/anytime.min.js');
        Assets::add('js/plugins/pickers/anytime.min.css');

        $availabilities = TeacherAvailability::where('teacher_id',$this->teacher->id)->get();

        $currentMonth = Carbon::now()->format('F Y');
        $daysInMonth = Carbon::now()->daysInMonth;

        $firstDay = date("w", mktime(0,0,0,Carbon::now()->month,1,Carbon::now()->year));

        $tempDays = $firstDay + Carbon::now()->daysInMonth;

        $weeksInMonth = ceil($tempDays/7);

        // dd($weeksInMonth);

        $tempArray = [];

        $k = 1;

        for ($i=1; $i <= $weeksInMonth; $i++) { 
            
            for ($j=1; $j <= 7; $j++) { 
                $tempArray[$i][] = $k-$firstDay;
                $k++;
            }

        }


        $availabilityData = TeacherAvailability::where('teacher_id',$this->teacher->id)
                                ->where(function($query){
                                    $query->whereBetween('from_date',[Carbon::now()->firstOfMonth(),Carbon::now()->lastOfMonth()])
                                    ->orWhereBetween('to_date',[Carbon::now()->firstOfMonth(),Carbon::now()->lastOfMonth()]);
                                })
                                ->get();

        if(!$availabilityData->count())
        {

            $availabilityData = TeacherAvailability::where('teacher_id',$this->teacher->id)
                                ->where(function($query){
                                    $query->where('from_date','<=',Carbon::now()->firstOfMonth())
                                    ->where('to_date','>=',Carbon::now()->lastOfMonth());
                                })
                                ->get();
        }

        $availabilityForMonth = [];

        foreach($availabilityData as $dataRow)
        {
            if($dataRow->from_date->month < Carbon::now()->month)
            {
                $startDate = Carbon::now()->firstOfMonth()->format('d-m-Y');
            }
            elseif($dataRow->from_date->year < Carbon::now()->year)
            {
                $startDate = Carbon::now()->firstOfMonth()->format('d-m-Y');
            }
            else
            {
                $startDate = $dataRow->from_date->format('d-m-Y');
            }

            if($dataRow->to_date->month > Carbon::now()->month)
            {
                $endDate = Carbon::now()->lastOfMonth()->format('d-m-Y');
            }
            elseif($dataRow->to_date->year > Carbon::now()->year)
            {
                $endDate = Carbon::now()->lastOfMonth()->format('d-m-Y');
            }
            else
            {
                $endDate = $dataRow->to_date->format('d-m-Y');
            }

            // dd($endDate);
            
            $filter = $dataRow->day_of_the_week;

            // var_dump($dataRow->id.'-'.$filter.'-'.$startDate.'-'.$endDate);

            $endDate = strtotime($endDate);

            for($i = strtotime($filter, strtotime($startDate)); 
                $i <= $endDate; 
                $i = strtotime('+1 week', $i))
            {
                $availabilityForMonth[date('j', $i)] = $dataRow;
            }

        }

        // dd($availabilityForMonth);

        $daysOfTheWeek = $this->daysOfTheWeek;
        $sessions = $this->sessions;

    	return view('dashboard.teacher.availability.index',compact('daysOfTheWeek','sessions','availabilities','tempArray','weeksInMonth','currentMonth','daysInMonth','availabilityForMonth'));
    }


    public function store()
    {


        $daysOfTheWeek = Input::get('day_of_week');
        $sessions = Input::get('session');

        $session_array = [];

        foreach($sessions as $session)
        {
            $session_array[$session] = true;
        }


        foreach($daysOfTheWeek as $dayofTheWeek)
        {
            $data = [];

            $data['teacher_id'] = $this->teacher->id;
            $data['day_of_the_week'] = $dayofTheWeek;
            $data['from_date'] = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
            $data['to_date'] = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

            $data = array_merge($data, $session_array);

            $there = TeacherAvailability::where('teacher_id',$data['teacher_id'])
                      ->where('day_of_the_week',$data['day_of_the_week'])
                      ->where(function($query) use ($data){
                            $query->whereBetween('from_date', [$data['from_date'],$data['to_date']])
                                  ->orWhereBetween('to_date', [$data['from_date'],$data['to_date']]);
                        })
                      ->first();

            
            if(!is_null($there))
            {
                flash()->error('Availability is already marked for these dates');
                return redirect()->back();
            }

        }

        // dd($session_array);

        foreach($daysOfTheWeek as $dayofTheWeek)
        {
            $data = [];

            $data['teacher_id'] = $this->teacher->id;
            $data['day_of_the_week'] = $dayofTheWeek;
            $data['from_date'] = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
            $data['to_date'] = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

            $data = array_merge($data, $session_array);

            TeacherAvailability::create($data);
        }

        return redirect()->back();

    }

    public function destroy($id)
    {
        TeacherAvailability::destroy($id);

        flash()->success('Delete sucessful');
        return redirect()->back();
    }

    public function updateIgnoreCalendar()
    {
        $teacher = $this->teacher;

        if(Input::get('value') == 'yes')
        {
            $teacher->ignore_calendar = true;
            flash()->success('The calendar below will be ignored. All invitations for classes that you are certified for will be sent.');
        }

        if(Input::get('value') == 'no')
        {
            $teacher->ignore_calendar = false;
            flash()->success('You will be sent invitations based on your availability mentioned below.');

        }

        $teacher->save();

        
        return redirect()->back();

    }

}