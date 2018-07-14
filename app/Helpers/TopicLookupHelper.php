<?php

namespace Aham\Helpers;

use Aham\Models\SQL\TopicsLookup;

class TopicLookupHelper {


    public static function createLookup($topic)
    {
        $topic_lookup = [];

        $topic_lookup['topic_id'] = $topic->id;
        $topic_lookup['status'] = $topic->status;
        $topic_lookup['sub_category_id'] = $topic->parent->id;
        $topic_lookup['subject_id'] = $topic->parent->parent->id;
        $topic_lookup['category_id'] = $topic->parent->parent->parent->id;

        $lookup = TopicsLookup::firstOrCreate(['topic_id' => $topic->id]);
                                
        $lookup->fill($topic_lookup);
        $lookup->save();

        return true;
    }

    public static function updateLookup($topic)
    {
        $topic_lookup = [];

        $topic_lookup['topic_id'] = $topic->id;
        $topic_lookup['status'] = $topic->status;
        $topic_lookup['sub_category_id'] = $topic->parent->id;
        $topic_lookup['subject_id'] = $topic->parent->parent->id;
        $topic_lookup['category_id'] = $topic->parent->parent->parent->id;

        $lookup = TopicsLookup::firstOrCreate(['topic_id' => $topic->id]);

        $lookup->fill($topic_lookup);
        $lookup->save();

        return true;
    }

    public static function getTopicChildren($topic)
    {
        switch ($topic->type) {
            case 'subject':
                    return TopicsLookup::where('subject_id',$topic->id)->pluck('topic_id')->toArray();
                    break;

            case 'category':
                    return TopicsLookup::where('category_id',$topic->id)->pluck('topic_id')->toArray();
                    break;

            case 'sub-category':
                    return TopicsLookup::where('sub_category_id',$topic->id)->pluck('topic_id')->toArray();
                    break;

            default:
                    return [$topic->id];
                    break;
        }
    }

}