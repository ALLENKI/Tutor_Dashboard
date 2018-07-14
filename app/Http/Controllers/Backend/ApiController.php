<?php

namespace Aham\Http\Controllers\Backend;

use Aham\Http\Controllers\Controller;

use Input;
use Carbon;

use Aham\Interactions\ClassSchedule;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Slot;

class ApiController extends Controller {

    public function getAvailableSlots()
    {
        $availableSlots = ClassSchedule::prepareAndGetAvailableSlots(Input::get('class'),Input::get('unit'),Input::get('date'));

        return $availableSlots;
    }

    public function getAvailableSlotsForEpisodes()
    {
        $availableSlots = ClassSchedule::getAvailableSlotsForDate(Input::get('date'), Input::get('episode'));

        return $availableSlots;
    }

    public function getTopicRules($id)
    {
    	//fetch number of units in a topic
    	$topic = Topic::find($id);

    	$units = $topic->units;

    	$number_of_units = $units->count();

    	$topicRules = SchedulingRule::where('no_of_units', $number_of_units)->pluck('description','id');

    	return $topicRules;
    }

    public function getTopicDetails($id)
    {
        $topic = Topic::find($id);

        return $topic;
    }

}