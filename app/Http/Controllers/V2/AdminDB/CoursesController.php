<?php
namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Coursable;
use Illuminate\Http\Response;
use Aham\Repositories\CourseRepository;
use Aham\CourseCatalog\CoursesHelper;
use Validator;
use Input;

class CoursesController extends Controller
{
    public $course;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->course = new CourseRepository;
    }

    public function index(Request $request)
    {
    }

    public function show($id)
    {
      return Course::find($id);
    }

    public function courseTree($id)
    {
        $tree = Course::find($id);
        
        if(is_null($tree)) {
            return  response()->json(['error' => true],200);
        } else {
            if( $tree->type == 'collection_of_topics' ) {
                return $tree->topics()->get();
            } else {
                return $tree->courses()->get();
            }
        }
    }

    /*
        course -requires-> topic
        course -requires-> course
        pass the course id and the topic id 
        added the require realtion to both of them and check the circular dependancy
    */

    public function addRequire($request)
    {
        if($request->has('topic')){
            if($this->course->addRequire($request->only('course','topic'))){
                return response()->json(['success' => true],200);
            }

            return  response()->json(['error' => true],200);

        } else {
            if($this->course->addRequire($request->only('course','requiredCourse'))){
                return response()->json(['success' => true],200);
            }

            return  response()->json(['error' => true],200);
        }
    }
    
    public function store(Request $request)
    {
        
        if($request->has('dependency')) {
            
            $this->addRequire($request);
            
        } else {

            if($request->has('topics')) {

                    $course = $this->course->createNewCourseWithNeo4J($request->only('course.name','course.desc','course.type','topics','hub_id'));

                    if($course != false) {
                        return $course;
                    } else {
                        return false;
                    }
                       
                    

            } else {

                if($this->course->createNewCourseWithNeo4J($request->only('course.name','course.desc','course.type','course_require','hub_id'))) {
                    return response()->json(['success' => true],200);
                }

            }

            return  response()->json(['error' => true],200);
        }

    }

    public function update($id,Request $request)
    {

        if($request->has('topics')){

                if($this->course->updateCourseWithNeo4j($id,$request->only('course.name','course.desc','course.type','topics'))){
                    return response()->json(['success' => true],200);
                }

        } else {

            if($this->course->updateCourseWithNeo4j($id,$request->only('course.name','course.desc','course.type','course_require'))){
                return response()->json(['success' => true],200);
            }

        }
        
        return response()->json(['error' => true],200);
    }

    public function deletetopic($id)
    {
        $deleteId =  Input::only('topicId')['topicId'];
        
        $course = Course::find($id);

        $neoCourse = new CoursesHelper();

        if('collection_of_topics' == $course->type) {

            $topic =   $course->topics()->find($deleteId);

                try {
        
                    $neoCourse->deleteHasForTopic(
                                    [
                                        'id' => (int)$id,
                                    ],
                                    [
                                        'id' => $topic->id,
                                    ]
                                );
        
                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                    return 'Topic not found';
                }

            
                Coursable::where('course_id',$course->id)->where('coursable_id',$topic->id)->delete();

        } else {

            $coursable =   $course->courses()->find($deleteId);

            try {
    
                $neoCourse->deleteHasForCourse(
                                [
                                    'id' => (int)$id,
                                ],
                                [
                                    'id' => $coursable->id,
                                ]
                            );
    
            } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                return 'Course not found';
            }

            Coursable::where('course_id',$course->id)->where('coursable_id',$coursable->id)->delete();

        }

    }

    public function destroy($id)
    {
        if($this->course->deleteCourseWithNeo4j($id)){
            return response()->json(['success' => true],200);
        }

        return response()->json(['errror' => true],200);
    }

    public function deleteCourseWithNo4j($id)
    {
        $deleteTopicId =  Input::only('topicId')['topicId'];
        
        $course = Course::find($id);

        $neoCourse = new CoursesHelper();
        
        if('collection_of_topics' == $course->type) {

            foreach($course->topics() as $topic) {
                
                try {
    
                    $neoCourse->deleteRequiredForTopic(
                                    [
                                        'id' => (int)$id,
                                    ],
                                    [
                                        'id' => $topic->id,
                                    ]
                                );
        
                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                    return 'Topic not found';
                }

                Coursable::where('course_id',$course->id)->where('coursable_id',$topic->id)->delete();
            }

            $course->delete();

        } else {

            foreach($course->courses() as $coursable) {

                try {
    
                    $neoCourse->deleteRequiredForCourse(
                                    [
                                        'id' => (int)$id,
                                    ],
                                    [
                                        'id' => $coursable->id,
                                    ]
                                );
        
                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
                    return 'Course not found';
                }

                // deleting from course table, coursables
                Coursable:where('course_id',$course->id)->where('coursable_id',$coursable->id)->delete();
                 $course->delete();
            }

            $course->delete();

        }

    }

    public function getCourses()
    {
        $coursesModel = Course::where('type','collection_of_topics');

        if (Input::has('id')) {
            $course = Course::find(Input::get('id'));
            $coursesList = $course->courses->pluck('id');
            $coursesModel = $coursesModel->whereNotIn('id',$coursesList);
        }
       
        return $coursesModel->select('name','id','type')->get();
    }

    public function getTopics()
    {
        $topicModel = Topic::where('type','topic')->where('status','active');

        if (Input::has('id')) {
            $course = Course::find(Input::get('id'));
            $topicList = $course->topics->pluck('id');
            $topicModel = $topicModel->whereNotIn('id',$topicList);
        }
        
        return $topicModel->select('name','id')->get();
    }

}