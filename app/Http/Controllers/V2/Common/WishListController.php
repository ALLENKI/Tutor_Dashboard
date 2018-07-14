<?php

namespace Aham\Http\Controllers\V2\common;

use Aham\Http\Controllers\Controller;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\WishList;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\HubTopic;
use Input;

class WishListController extends Controller
{

    public function __construct()
    {

    }

    public function viewTopicsForLeanrer($learnerId)
    {
        $wishlists =  WishList::where('learner_id',$learnerId)->get();

        $data = [];
 
         foreach($wishlists as $wishlist) {
             
             $array = $wishlist->of->only('name','type');
             $array['id'] = $wishlist->of_id;
             $data[] = $array;
 
         }
 
         return $data;
    }

    public function store($learnerId)
    {
        $insert['topics'] = Input::get('topics');
        $insert['learner_id'] = (int) $learnerId;
        $insert['of_type'] = Topic::class;
        $insert['added_by'] = Input::get('added_by');

        foreach($insert['topics'] as $topic) {

            $insert['of_id'] = $topic;
            // dd(array_only($insert,['topics','learner_id','of_type','added_by','of_id']));
            WishList::create(
                              ['of_id' => $topic,
                              'learner_id' => (int) $learnerId,
                              'of_type' => Topic::class,
                              'added_by' => Input::get('added_by')]
                            );
                              
        } 

        return 'true';
    }

    public function destroy($learnerId,$wishlistId)
    {
         WishList::find($wishlistId)->delete();

         return 'true';
    }

    public function hubStoreWishList($learnerId,$hub)
    {
        $hub =  Location::where('slug',$hub)->get()->first();
        $insert['topics'] = Input::get('topics');

        foreach($insert['topics'] as $topic) {
            WishList::create(
                              ['of_id' => $topic,
                              'learner_id' => (int) $learnerId,
                              'hub_id' => $hub->id,
                              'of_type' => Topic::class,
                              'added_by' => Input::get('added_by')]
                            );
        }
         
    }

    public function hubDestroy($learnerId,$hub,$wishlistId)
    {
         WishList::find($wishlistId)->delete();

         return 'true';
    }

    public function hubWishlistTopics($learnerId,$hub)
    {
        $hub =  Location::where('slug',$hub)->first();

        $wishlistIds =  WishList::where('hub_id',$hub->id)
                                  ->where('learner_id',$learnerId)
                                  ->pluck('of_id')
                                  ->toArray();

        $topics = HubTopic::where('hub_id',$hub->id)
                            ->pluck('of_id')
                            ->toArray();  

        $topics = Topic::with('children.children.children')
                        ->whereIn('id',$topics)
                        ->where('approve',true)
                        ->get();

        $nodes = getNodes($topics);

        return Topic::with('units')
                      ->whereIn('id',$nodes->pluck('id')->toArray())
                      ->whereNotIn('id',$wishlistIds)
                      ->get();
    }

    public function adminWishlistTopics($learnerId)
    {
       $wishListIds  =  WishList::where('learner_id',$learnerId)
                                ->pluck('of_id')
                                ->toArray();
                        
        $topics = Topic::whereNotIn('id',$wishListIds)
                         ->get();

        return $topics;
    }

}