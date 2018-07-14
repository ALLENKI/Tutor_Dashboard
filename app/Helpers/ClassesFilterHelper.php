<?php

namespace Aham\Helpers;

use Aham\Models\SQL\City;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicsLookup;

use Input;

use Illuminate\Support\Collection;

class ClassesFilterHelper {


	public static function getResults($path)
	{
		// Let's check if there is a city

		$parts = explode('/',$path);

		$city = static::getCity($parts);
		$locality = static::getLocality($parts);

		$q = Input::get('q','');

        $tags_search = false;

        if($q != '')
        {
            $tags_search = true;

			$tagged_topics =  Topic::topic()
									->where('visibility',true)
            						->where(function ($query) use ($q) {
                                    
                                    $query
                                    ->where('name', 'LIKE', '%'.$q.'%')
                                    ->orWhereHas('tags', function($query) use ($q)
                                    {
                                        $query->where(function ($query) use ($q)
                                        {
                                            $query->where('normalized', 'LIKE', '%'.$q.'%');
                                        });
                                        
                                    });

                                })->pluck('id')->toArray();

        }

		// Question is get all topics?

		// Or topics in a particular subject/sub-category/cateogory

		$topic = static::isATopicInPath($parts);

		if($topic)
		{
			if($topic->type == 'topic')
			{
				$result = [
					'type' => 'item',
					'item' => $topic
				];
			}
			else
			{

 				$topics = TopicsLookup::with('topic')
                            ->where('subject_id',$topic->id)
                            ->whereIn('topics_lookup.status',['active','in_progress'])
                            ->whereHas('topic',function($query){
								$query->whereNull('deleted_at')
									  ->where('visibility',true);
                            });

                if($tags_search)
                {
                    $topics = $topics->whereIn('topic_id',$tagged_topics);
                }

                $topics = $topics
                            ->distinct()
                            ->pluck('topic_id')
                            ->toArray();
	
				$topics = Topic::whereIn('id',$topics)
								->where('visibility',true)->get();

				// $childrenTopics = new Collection;

				// static::getAllChildrenTopics($topic, $childrenTopics);

				// dd($childrenTopics->pluck('id'));

				$result = [
					'type' => 'list',
					'list' => $topics
				];

				if($topic->type == 'subject')
				{
					$result['subject'] = $topic;
				}
			}

		}
		else
		{

			$topics = Topic::with('picture','parent')
							->topic()
							->whereIn('status',['active','in_progress']);

			// dd($tagged_topics);

            if($tags_search)
            {
                $topics = $topics->whereIn('id',$tagged_topics);
            }

			$topics = $topics->where('visibility',true)->get();

			// dd($topics);

			$result = [
				'type' => 'list',
				'list' => $topics
			];
		}

		return $result;
	}

	public static function getCity($parts)
	{
		$city = null;

		if(isset($parts[1]))
		{
			$city = $parts[1];

			City::where('slug',$city)->firstOrFail();
		}

		return $city;
	}

	public static function getLocality($parts)
	{
		$locality = null;

		if(isset($parts[1]))
		{
			$locality = $parts[1];
		}

		return $locality;
	}

	public static function isATopicInPath($parts)
	{
		$parts = explode('classes-in-',$parts[0]);

		if(isset($parts[1]))
		{
			$topic = $parts[1];

			$topic = Topic::where('slug',$topic)
							->firstOrFail();

			return $topic;
		}

		return false;
	}

	public static function getAllChildrenTopics($topic, &$childrenTopics)
	{
		foreach($topic->children as $child)
		{
			if($child->children->count())
			{
				static::getAllChildrenTopics($child, $childrenTopics);
			}
			else
			{
				if(($child->status == 'active' || $child->status == 'in_progress') && ($child->type == 'topic'))
				{
					$childrenTopics->push($child);
				}
				
			}
			
		}

		return true;
	}

	public static function getAllChildrenTopicsCount($topic)
	{
		$childrenTopics = new Collection;

		static::getAllChildrenTopics($topic, $childrenTopics);

		return $childrenTopics->count();
	}
}