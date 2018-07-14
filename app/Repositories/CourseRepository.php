<?php
namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\Coursable;
use Aham\CourseCatalog\CoursesHelper;
use Aham\Repositories\Util;
use Validator;



class CourseRepository extends Repository
{
    public $course;

    public function __construct()
    {
        $this->neoCourse = new CoursesHelper();
    }
    
    public function model()
    {
        return 'Aham\Models\SQL\Course';
    }

    public function getCourse($id)
    {
        return $this->neoCourse->get(['id' => $id]);
    }

    public function createNewCourseWithNeo4J($array)
    {
        // create course

        if(isset($array['hub_id'])) {

             $array['hub_id'] = Location::where('slug',$array['hub_id'])->get();

        } else {
            
            $array['hub_id'] = null;
            
        }

        $courseDetails = Course::create([
            'name' => $array['course']['name'],
            'type' => $array['course']['type'],
            'description' => $array['course']['desc']
        ]);
        $this->neoCourse->create([
            'id' => $courseDetails->id,
            'name' => $courseDetails->name
        ]);
        
        $course = $this->neoCourse->get(['id' => $courseDetails->id]);
        
        // create topics
        if('collection_of_topics' == $array['course']['type'] ) {

            foreach ($array['topics'] as $topic) {

                Coursable::create([
                        'course_id' => $course['id'],
                        'coursable_id' => $topic,
                        'coursable_type' => Topic::Class
                ]);


                try {

                    $this->neoCourse->addHasRelationToTopic(
                        ['id' => $course['id']],
                        ['id' => $topic]
                    );

                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {

                    echo 'Topic:'.$topic.' not found';
                    continue;   
                }

            }

        } else {

            foreach ($array['course_require'] as $course_require) {

                Coursable::create([
                    'course_id' => $course['id'],
                    'coursable_id' => $course_require,
                    'coursable_type' => Course::Class
                ]);


                try {

                    $this->neoCourse->addHasRelationToCourse(
                        ['id' => $course['id']],
                        ['id' => $course_require]
                    );

                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {

                    echo 'Course:'.$course_require.' not found';
                    continue;
                }


            }

        }            
        if ( count(Course::find($course['id'])->toArray()) > 0 ) {
              return $courseDetails;
        }

        return false;
    }

    public function updateCourseWithNeo4j($id,$updates)
    {
        $course = Course::find($id);

        $course->name = $updates['course']['name'];

        if(Util::findKey('desc',$updates)) {
            $course->description = $updates['course']['desc'];
        }

        $course->save();
    
        if('collection_of_topics' == $updates['course']['type']) {

            foreach($updates['topics'] as $topic) {

                $coursable =  Coursable::where('course_id',$course->id)
                                        ->firstOrCreate(['coursable_id' => $topic]);

                $coursable->course_id = $course->id;
                $coursable->coursable_type = Topic::Class;

                $coursable->save();

                try {

                    $this->neoCourse->addHasRelationToTopic(
                        ['id' => $course->id],
                        ['id' => $topic]
                    );

                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {

                    echo 'Topic:'.$topic.' not found';
                    continue;

                }

            }

        } else {

            foreach($updates['course_require'] as $course_require) {

                // var_dump($course_require);

                $coursable =  Coursable::where('course_id',$id)
                                        ->firstOrCreate(['coursable_id' => $course_require]);

                $coursable->course_id = $id;
                $coursable->coursable_type = Course::class;

                $coursable->save();

                try {

                    $this->neoCourse->addHasRelationToCourse(
                        ['id' => $course->id],
                        ['id' => $course_require]
                    );

                } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {

                    echo 'Course:'.$course_require.' not found';
                    continue;

                }

            }

        }

        return true;
    }

    public function deleteCourseWithNeo4j($id)
    {
        $course = Course::find($id);
        
        Coursable::where('course_id',$course->id)->delete();

        // delete in neo4j.

        if(is_null(Course::find($id))){
            return response()->json(['success' => true], 200);
        }

        return response()->json(['error' => true], 200);
    }

}
