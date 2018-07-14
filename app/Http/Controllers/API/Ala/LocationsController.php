<?php

namespace Aham\Http\Controllers\API\Ala;

use Input;
use Carbon;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;

class LocationsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $user = $this->auth->user();

        $accessibleLocations = $user->accessibleLocations('classes.manage');

        $locations = Location::with(
            [
                'city' => function ($query) {
                    return $query->addSelect(['name', 'id']);
                }
            ]
        )
        ->whereIn('id', $accessibleLocations)
        ->select(['id', 'name', 'slug', 'street_address', 'landmark', 'city_id'])
        ->get();

        return ['locations' => $locations];
    }

    public function getDetail($slug)
    {
        $user = $this->auth->user();

        $accessibleLocations = $user->accessibleLocations('classes.manage');

        $locations = Location::with(
            [
                'city' => function ($query) {
                    return $query->addSelect(['name', 'id']);
                }
            ]
        )
        ->where('slug', $slug)
        ->whereIn('id', $accessibleLocations)
        ->select(['id', 'name', 'slug', 'street_address', 'landmark', 'city_id'])
        ->first();

        return ['location' => $locations];
    }

    public function getClassrooms($slug)
    {
        $location = Location::where('slug', $slug)->first();

        return $location->classrooms;
    }

    public function classesForCalendar($slug)
    {
        $location = Location::where('slug', $slug)->first();

        $classes = AhamClass::whereNotIn('status', ['cancelled'])
                                    ->where('location_id', $location->id)
                                    ->orderBy('start_date', 'asc')
                                    ->pluck('id')
                                    ->toArray();

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        $timings = ClassTiming::with('ahamClass', 'classUnit', 'classroom')
                    ->whereIn('class_id', $classes)
                    ->whereBetween('date', [$from_date, $to_date])
                    ->whereNotIn('status', ['cancelled'])
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();

        $events = [];

        foreach ($timings as $timing) {
            $event = [];

            $event['title'] = $timing->classUnit->name . ' ' . $timing->ahamClass->topic_name;
            $event['class_id'] = $timing->ahamClass->id;

            $dt = Carbon::parse($timing->date->format('Y-m-d') . ' ' . $timing->start_time);

            $event['start'] = $dt->toIso8601String();

            $dt = Carbon::parse($timing->date->format('Y-m-d') . ' ' . $timing->end_time);

            $event['end'] = $dt->toIso8601String();

            if ($timing->status == 'done') {
                $event['color'] = '#39c529';
            }

            $events[] = $event;
        }

        return $events;
    }
}
