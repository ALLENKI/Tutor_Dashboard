<?php

namespace Aham\Http\Controllers\API\Ala;

use Input;
use Aham\Models\SQL\Topic;

class TopicsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {

        if (Input::has('type')) {
            $topics = Topic::where('type', Input::get('type'))
                            ->orderBy('name', 'asc')
                            ->select(['topics.id', 'topics.slug', 'topics.name', 'topics.minimum_enrollment', 'topics.maximum_enrollment', 'topics.type'])

                            ->get();
                         
                           return ([' topic' => $topics->toJson() ]);


        }

        $topics = Topic::has('units')
                        ->with('units')
                        ->topic()
                        ->active()
                        ->orderBy('name', 'asc')

                        ->select(['topics.id', 'topics.slug', 'topics.name', 'topics.minimum_enrollment', 'topics.maximum_enrollment', 'topics.type'])

                        ->get();
        return ([' topic' => $topics->toJson() ])

    }

    public function getDetail($slug)
    {
        //dd('hey');
        $topics = Topic::has('units')
                        ->topic()
                        ->active()
                        ->orderBy('name', 'asc')
                        ->where('slug', $slug)
                        ->select(['id', 'slug', 'name', 'minimum_enrollment', 'maximum_enrollment'])
                        ->first();
 return ($topics->toJson() );
                         
                    //    return ($topics);

        //return utf8_encode($topics);
    }
}
