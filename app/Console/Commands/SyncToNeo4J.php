<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Aham\CourseCatalog\TopicHelper;
use Aham\CourseCatalog\CategoryHelper;
use Aham\CourseCatalog\SubjectHelper;
use Aham\CourseCatalog\SubCategoryHelper;
use Aham\CourseCatalog\CoursesHelper;
use Aham\CourseCatalog\NeoHelper;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Course;

class SyncToNeo4J extends Command
{

    public $topicHelper;
    public $categoryHelper;
    public $subjectHelper;
    public $subCategoryHelper;
    public $courseHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:topics_courses_syncneo4j';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->topicHelper = new TopicHelper();
        $this->categoryHelper = new CategoryHelper();
        $this->subjectHelper = new SubjectHelper();
        $this->subCategoryHelper = new SubCategoryHelper();
        $this->courseHelper = new CoursesHelper();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $neoHelper = new NeoHelper();
        $neoHelper->deleteAllNodesAndRelationships();
        $topics = Topic::orderBy('id', 'asc')->with('prerequisites')->get();
        $courses = Course::orderBy('id', 'asc')->with('topics')->with('courses')->get();
        
        $this->createNodesAndHasRelationship(Topic::where('type','category')->orderBy('id', 'asc')->get());
        $this->createNodesAndHasRelationship(Topic::where('type','subject')->orderBy('id', 'asc')->get());
        $this->createNodesAndHasRelationship(Topic::where('type','sub-category')->orderBy('id', 'asc')->get());
        $this->createNodesAndHasRelationship(Topic::where('type','topic')->orderBy('id', 'asc')->get());
        $this->createCourses($courses);
        $this->createCourseHasRelationship($courses);
        $this->createRequiredRelationship($topics);

    }

    public function createNodesAndHasRelationship($topics)
    {

        foreach ($topics as $topic) {
            $this->line('Topic:' . $topic->id);

            try {
                switch ($topic->type) {
                case 'topic':
                    $this->topicHelper->create(
                        ['id' => $topic->parent_id],
                        [
                            'name' => $topic->name,
                            'id' => $topic->id
                        ]
                    );
                    break;

                case 'subject':
                    $this->subjectHelper->create(
                        ['id' => $topic->parent_id],
                        [
                            'name' => $topic->name,
                            'id' => $topic->id
                        ]
                    );
                    break;

                case 'category':
                    $this->categoryHelper->create(
                        [
                            'name' => $topic->name,
                            'id' => $topic->id
                        ]
                    );
                    break;

                case 'sub-category':
                    $this->subCategoryHelper->create(
                        ['id' => $topic->parent_id],
                        [
                            'name' => $topic->name,
                            'id' => $topic->id
                        ]
                    );
                break;

                default:
                    // code...
                    break;
            }
            } catch (\Exception $e) {
                $this->line('Not Found');
                \Log::error($e);
            }
        }

        return true;
    }

    public function createRequiredRelationship($topics)
    {

        foreach ($topics as $topic) {

            $this->line('Create Required ID:' . $topic->id);

            try {
                switch ($topic->type) {

                case 'topic':

                    $this->requirerTopic($topic,$topic->prerequisites);

                break;

                case 'sub-category':

                     $this->requirerSubCategory($topic,$topic->prerequisites);

                break;

                default:
                    // code...
                    break;
            }
            } catch (\Exception $e) {
                $this->line('Not Found');
                \Log::error($e);
            }

        }
        
    }

    public function requirerTopic($requirer,$topics)
    {
        
        foreach ($topics as $topic) {
            $this->line('Create Required Topic' . $topic->id);

            try {
                switch ($topic->type) {

                    case 'topic':
                        
                            try {

                                $this->topicHelper->createRequiredToTopic(
                                            [
                                                'id' => $requirer->id,
                                            ],
                                            [
                                                'id' => $topic->id,
                                            ]
                                );
                
                            } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                                return 'Topic not found';
                            }

                        break;

                    case 'sub-category':

                        try {

                            $this->topicHelper->createRequiredToSubCategory(
                                        [
                                            'id' => $requirer->id,
                                        ],
                                        [
                                            'id' => $topic->id,
                                        ]
                            );
            
                        } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                            return 'Topic not found';
                        }

                    break;

                default:
                    // code...
                    break;
            }
            } catch (\Exception $e) {
                $this->line($e);
                $this->line('Not Found');
                \Log::error($e);
            }
            
        }

    }

    public function requirerSubCategory($requirer,$topics)
    {

        foreach ($topics as $topic) {
            $this->line('Create Requirer Sub Category:' . $topic->id);

            try {
                switch ($topic->type) {
                    case 'sub-category':

                        try {

                            $this->subCategoryHelper->createRequired(
                                        [
                                            'id' => $requirer->id,
                                        ],
                                        [
                                            'id' => $topic->id,
                                        ]
                            );
            
                        } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                            return 'Topic not found';
                        }

                    break;

                default:
                    // code...
                    break;
            }
            } catch (\Exception $e) {
                $this->line('Not Found');
                \Log::error($e);
            }
            
        }
        
    }

    public function createCourses($courses)
    {
        foreach ($courses as $course) {
            $this->line('Course :' . $course->id);
            $this->courseHelper->create(
                [
                    'id' => $course->id,
                    'name' => $course->name
                ]
            );
        }
        return true;
    }

    public function createCourseHasRelationship($courses) 
    {
        foreach ($courses as $course) {
            try {
                switch ($course->type) {
                    case 'collection_of_topics':
                        foreach ($course->topics as $topic) {
                            try {
                                $this->courseHelper->addHasRelationToTopic(
                                    ['id' => $course->id],
                                    ['id' => $topic->id]
                                );
                            } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
                                $this->line('Topic Not Found');
                            }
                        }
                        break;

                    case 'collection_of_courses':
                        foreach ($course->courses as $childCourse) {
                            try {    
                                $this->courseHelper->addHasRelationToCourse(
                                    ['id' => $course->id],
                                    ['id' => $childCourse->id]
                                );
                            } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
                                $this->line('Course Not Found');
                            }
                        }
                        break;
                    default:
                        break;
                }
            } catch (\Exception $e) {
                $this->line('Not Found');
                \Log::error($e);
            }
        }
        return true;
    }

}
