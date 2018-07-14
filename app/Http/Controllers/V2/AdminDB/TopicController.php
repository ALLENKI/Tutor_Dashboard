<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\TopicRepository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicPrerequisite;
use Input;
use Aham\CourseCatalog\TopicHelper;
use Aham\Services\Storage\CDNInterface;
use Aham\Models\SQL\File;

class TopicController extends BaseController
{
    private $topic;

    public function __construct(TopicRepository $topic)
    {
        $this->topic = new TopicRepository();
    }

    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        $topic = $this->topic->createNewTopicWithNeo4J($request->only('name', 'parent_id', 'description', 'units','status','approve','hub','visibility'));
        if ($topic) {
            return response()->json([
                'success' => true,
                'topic' => $topic
            ], 200);
        }
        return response()->json(['error' => true], 200);
    }

    public function show($id)
    {
        $topic = $this->topic->makeModel()->with('parent')->find($id);
        $result = [];
        $result['id'] = $topic->id;
        $result['name'] = $topic->name;
        $result['status'] = $topic->status;
        $result['approve'] = (bool) $topic->approve;
        $result['visibility'] = (bool) $topic->visibility;
        $result['units'] = $topic->units;
        $result['description'] = $topic->description;
        $result['sub_category_id'] = $topic->parent_id;
        $result['sub_category_name'] = $topic->parent->name;
        $result['subject_id'] = $topic->parent->parent_id;
        $result['subject_name'] = $topic->parent->parent->name;
        $result['category_id'] = $topic->parent->parent->parent_id;
        $result['category_name'] = $topic->parent->parent->parent->name;
        $result['prerequisites'] = $topic->prerequisites;
        return $result;
    }

    public function update(Request $request, $id)
    {
        $topic = $this->topic->updateTopicWithNeo4j($id, $request->only('approve','name', 'parent_id', 'description', 'units','status','hub','visibility'));
        if ($topic) {
            return response()->json([
                'success' => true,
                'topic' => $topic
            ], 200);
        }
        return response()->json(['error' => true], 200);
    }

    public function destroy($id)
    {
        if ($this->topic->deleteTopicWithNeo4j($id)) {
            return response()->json(['success' => true], 200);
        }
        return response()->json(['error' => true], 200);
    }

    public function getAllActiveTopics($id)
    {
        return Topic::whereIn('type',['topic','sub-category'])
                    ->whereNotIn('id',[$id])
                    ->where('status','active')
                    ->select('name','id')
                    ->get();
    }
    
    public function addPrerequisite($id)
    {
        $requirerId = $id;
        $topicId = Input::only('topicId')['topicId'];
        $source =  Topic::find($requirerId);
        $destinations = Topic::whereIn('id',$topicId)->get();
        $neoTopic = new TopicHelper();

        foreach ($destinations as $destination) {
            if('topic' == $destination->type) {
                try {
                    $neoTopic->addTopicAsPrerequisite(
                        [
                            'id' => $source->id,
                        ],
                        [
                            'id' => $destination->id,
                        ]
                    );
                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                    echo 'Topic:'.$destination->name.' not found \n';
                    continue;
                }
                $topicPrerequisite = TopicPrerequisite::create([
                    'topic_id' => $destination->id,
                    'requirer_id' => $source->id
                ]);
            } elseif('sub-category' == $destination->type) {
                try {
                    $neoTopic->addSubCategoryAsPrerequisite(
                        [
                            'id' => $source->id,
                        ],
                        [
                            'id' => $destination->id,
                        ]
                    );
                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                    echo 'Sub Category not found \n';
                    continue;
                }
                $topicPrerequisite = TopicPrerequisite::create([
                    'topic_id' => $destination->id,
                    'requirer_id' => $source->id
                ]);
            }
        }
    }

    public function getPrerequisite($id)
    {
        $prerequisite = TopicPrerequisite::where('requirer_id',$id)
                                          ->select('topic_id')
                                          ->get();
        $prerequisite = Topic::whereIn('id',$prerequisite)
                              ->where('status','active')
                              ->get();
        return $prerequisite;
    }

    public function removePrerequisite($id)
    {
        $deleteTopicId = Input::only('topicId')['topicId'];
        $type = Topic::select('type')->find($id);
        $prerequisites = TopicPrerequisite::where('requirer_id',$id)
                                           ->where('topic_id',$deleteTopicId)
                                           ->get();
        $neoTopic = new TopicHelper();
        if('topic' == $type->type) {
            try {
                $neoTopic->deleteRequiredForTopic(
                    [
                        'id' => (int)$id,
                    ],
                    [
                        'id' => $deleteTopicId,
                    ]
                );
            } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                return 'Topic not found';
            }
            if($prerequisites->count() >= 1){
                foreach($prerequisites as $prerequisite){
                    $prerequisite->delete();
                }
            }
        } elseif('sub-category' == $type->type) {
            try {
                $neoTopic->deleteRequiredForTopic(
                    [
                        'id' => $id,
                    ],
                    [
                        'id' => $deleteTopicId,
                    ]
                );
            } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                return 'Topic not found';
            }
            if($prerequisites->count() > 1){
                foreach($prerequisites as $prerequisite){
                    $prerequisite->delete();
                }
            }
        }
        return response()->json(['success' => true], 200);
    }

    public function uploadDoc($id,CDNInterface $cdn)
    {
        $topic = Topic::find($id);
        if(Input::file('doc') != null) {
            $code = $topic->code;
            $formFile = Input::file('doc');
            $extension = $formFile->getClientOriginalExtension();
            $filename = $topic->code . '-' . time() . '.' . $extension;
            $upload_success = $formFile->move(storage_path('uploads'), $filename);
            $data['key'] = 'course_catalog/topic/file/' . $filename;
            $data['source'] = storage_path('uploads/' . $filename);
            $result = $cdn->upload($data);
            $fileUrl = $result['url'];
            if(Input::get('filename')) {
                $filename = Input::get('filename');
            } 
            File::create([
                'user_id' => $this->auth->user()->id,
                'file_name' => $filename,
                'file_url' => $fileUrl,
                'of_id' => $topic->id,
                'of_type' => Topic::class,
                'mime_type' => $extension,
            ]);
            \File::delete(storage_path('uploads/' . $filename));
        }
    }

    public function getDocs($id)
    {
        return Topic::find($id)->files;
    }

    public function removeDocs($id)
    {
        $topic = Topic::find($id);
        $topic->files()->find(Input::get('fileId'))->delete();
        return $topic;
    }

    public function addToCourse($id)
    {
        $this->topic->addingToCourse(Input::get('courseId'), $id);
    }

}
