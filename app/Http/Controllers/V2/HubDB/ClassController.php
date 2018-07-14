<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Aham\Repositories\ClassRepository;
use League\Fractal;
use Aham\Managers\EnrollmentManager;
use Aham\Helpers\PaymentCalculatorHelper;

use Aham\Transformers\AhamClassTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Note;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\RepeatClass;
use Aham\Models\SQL\StudentAssessment;
use Carbon;
use Input;
use Aham\Helpers\TeacherHelper;
use Aham\Transformers\NoteTransformer;
use Aham\Models\SQL\StudentInvitation;
use Aham\Managers\ClassStatusManager;


class ClassController extends BaseController
{
    private $ahamClass;

    public function __construct(ClassRepository $ahamClass)
    {
        $this->ahamClass = $ahamClass;
    }

    public function show($id)
    {
        $ahamClass = $this->ahamClass->find($id);

        return $this->response->item($ahamClass, new AhamClassTransformer);
    }

    public function enrollments($id)
    {
        $ahamClass = $this->ahamClass
                            ->makeModel()
                            ->with('classUnits.enrollments.learner.user')
                            ->find($id);


        $students = Student::with('user.creditBuckets')
                            ->where('active', true)
                            ->get();

        $allStudents = [];

       
        foreach ($students as $student) {
            $allStudents[] = [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'text' => $student->user->name.' ('.$student->user->email.')'.' Credits:'.$student->user->creditBuckets()->sum('total_remaining')
            ];
        }////

        $students = Student::with('user.creditBuckets')
                            ->where('active', true)
                            ->whereNotIn('id', $ahamClass->enrollments->pluck('student_id')->toArray())
                            ->whereHas('hubs',function($query) use ($ahamClass){
                                $query->whereIn('id',[$ahamClass->location->id]);
                            })
                            ->get();


        $eligibleStudents = [];

        $students = $this->removeAllocatedTutorFromStudent($ahamClass, $students);                            

        foreach ($students as $student) {
            $eligibleStudents[] = [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'text' => $student->user->name.' ('.$student->user->email.')'.' Credits:'.$student->user->creditBuckets()->sum('total_remaining')
            ];
        }
        $enrolledStudents = [];

        foreach ($ahamClass->allEnrollments as $enrollment) {
            $student = $enrollment->student;
            
            $user = $student->user;

            $enrolledStudents[] = [
                'code' => $student->code,
                'id' => $student->id,
                'enrolled_id' => $enrollment->id,
                'class_id' => $ahamClass->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'enrolled_on' => $enrollment->created_at->format('jS M Y H:i A'),
                'ghost' => $enrollment->ghost,
                'status' => $enrollment->status,
                'cancelled' => $enrollment->cancelled,
                'feedback' => $enrollment->present()->typeFeedback,
                'feedbacks' => $enrollment->feedback,
                'remarks' => $enrollment->remarks
            ];
        }

        return [
            'eligibleStudents' => $eligibleStudents,
            'enrolledStudents' => $enrolledStudents,
            'allStudents' => $allStudents,
            'classUnits' => $ahamClass->classUnits
        ];
    }

    public function cancelClassEnrollment($classId, $learnerId)
    {
        $class = AhamClass::find($classId);

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        $manager->cancelEnrollment('cancelled_by_admin');

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled (enrollment) ']]
        ), 200);

    }

    public function cancelUnitEnrollment($unitId, $learnerId)
    {
        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unitId);

        $class = $studentEnrollmentUnit->ahamClass;

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        $manager->cancelUnitEnrollment($unitId,'cancelled_by_admin');

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled (enrollment) ']]
        ), 200);

    }

    public function enroll($classId, $learnerId)
    {
        $class = AhamClass::find($classId);

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        if ($class->free) {
            $manager->freeEnroll();

            return \Response::json(array(
                'success' => true,
                'errors' => [['Successfully enrolled (free) ']]
            ), 200);
        }

        if (!$manager->isEligible()) {
            $manager->enrollAsGhost();

            return \Response::json(array(
                'success' => true,
                'errors' => [['Successfully enrolled (ghost) ']]
            ), 200);
        }

        $manager->enroll();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully enrolled (direct) ']]
        ), 200);
    }

    public function checkEligibility($classId, $learnerId)
    {
        $class = AhamClass::find($classId);

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        if ($manager->alreadyEnrolled()) {
            return [
                'can_enroll' => false,
                'can_force' => true,
                'reason' => "Already enrolled to this class"
            ];
        }

        if ($manager->pendingClass()) {
            return [
                'can_enroll' => false,
                'can_force' => false,
                'reason' => "You have already enrolled to the topic, please finish that first to enroll again."
            ];
        }

        if (!$manager->hasAvailability()) {
            return [
                'can_enroll' => false,
                'can_force' => true,
                'reason' => "Maximum enrollment reached"
            ];
        }

        if (!$manager->checkAvailability()) {
            return [
                'can_enroll' => false,
                'can_force' => false,
                'reason' => "Student is busy in these timings in some other class"
            ];
        }

        if ($class->free) {
            return [
                'can_enroll' => true,
                'reason' => "Proceed to Enroll - Free"
            ];
        }


        if (!$manager->hasEnoughCredits($class)) {
            return [
                'can_enroll' => false,
                'can_force' => false,
                'reason' => "You don't have enough credits"
            ];
        }

        if (!$manager->isEligible()) {
            return [
                'can_enroll' => false,
                'can_force' => true,
                'reason' => "You are not eligible to enroll"
            ];
        }

        return [
            'can_enroll' => true,
            'can_force' => false,
            'reason' => "Proceed to Enroll"
        ];
    }

    public function tutors($id)
    {
        $ahamClass = $this->ahamClass
                            ->makeModel()
                            ->with('classUnits.enrollments.learner.user')
                            ->find($id);


        $eligibleTeachers = TeacherHelper::eligibleTeachers($ahamClass);

        $eligibleTeachers = Teacher::where('active',true)
                                    ->whereIn('id',array_keys($eligibleTeachers))
                                    ->get();

        $allEligibleTutors = [];

        foreach($eligibleTeachers as $eligibleTeacher)
        {
            $allEligibleTutors[] = [
                'id' => $eligibleTeacher->id,
                'email' => $eligibleTeacher->user->email,
                'name' => $eligibleTeacher->user->name
            ];
        }

        $certifiedTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $ahamClass->topic->id)
                                ->get();

        $allCertifiedTutors = [];

        foreach($certifiedTeachers as $certifiedTeacher)
        {
            $allCertifiedTutors[] = [
                'available' => TeacherHelper::isAvailable($ahamClass, $certifiedTeacher),
                'ignoreCalendar' => $certifiedTeacher->teacher->ignore_calendar,
                'email' => $certifiedTeacher->teacher->user->email,
                'name' => $certifiedTeacher->teacher->user->name
            ];  
        }

        return [    
            'certifiedTutors' => $allCertifiedTutors,
            'eligibleTutors' => $allEligibleTutors,
        ];
    }

    public function tutorsForUnit($id, $unitId)
    {
        $classTiming = ClassTiming::find($unitId);
    
        $eligibleTeachers = TeacherHelper::eligibleTeachersPerUnit($classTiming);

        $eligibleTeachers = Teacher::where('active',true)
                                    ->whereIn('id',array_keys($eligibleTeachers))
                                    ->get();

        $allEligibleTutors = [];

        foreach($eligibleTeachers as $eligibleTeacher)
        {
            $allEligibleTutors[] = [
                'id' => $eligibleTeacher->id,
                'email' => $eligibleTeacher->user->email,
                'name' => $eligibleTeacher->user->name
            ];
        }


        $certifiedTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $classTiming->ahamClass->topic->id)
                                ->get();

        $allCertifiedTutors = [];

        foreach($certifiedTeachers as $certifiedTeacher)
        {
            $allCertifiedTutors[] = [
                'available' => TeacherHelper::isAvailable($classTiming->ahamClass, $certifiedTeacher),
                'ignoreCalendar' => $certifiedTeacher->teacher->ignore_calendar,
                'email' => $certifiedTeacher->teacher->user->email,
                'name' => $certifiedTeacher->teacher->user->name
            ];  
        }

        return [    
            'certifiedTutors' => $allCertifiedTutors,
            'eligibleTutors' => $allEligibleTutors,
        ];
    }

    public function assignTutor($classId, $tutorId)
    {
        // Create invitation
        $invitation = ClassInvitation::firstOrCreate([
            'class_id' => $classId,
            'teacher_id' => $tutorId
        ]);

        $invitation->status = 'awarded';
        $invitation->save();


        // Award this invitation
        $ahamClass = $invitation->ahamClass;
        $ahamClass->teacher_id = $invitation->teacher_id;
        $ahamClass->commission = $invitation->teacher->commission;
        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->teacher_id = $ahamClass->teacher_id;
            $timing->commission = $invitation->teacher->commission;
            $timing->save();
        }

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully assigned (direct) ']]
        ), 200);
    }

    public function changeTutor($classId,$tutorId,$unitId)
    {
         // Create invitation
         $invitation = ClassInvitation::firstOrCreate([
            'class_id' => $classId,
            'teacher_id' => $tutorId
        ]);
        $invitation->status = 'awarded';
        $invitation->save(); 

        $classTiming = ClassTiming::find($unitId);

        $classTiming->teacher_id = $tutorId;
        $classTiming->save();

        return \Response::json(array(
            'success' => true,
             'classId' => $classId,
             'unitId' => $unitId,
             'tutorId' => $tutorId,
        ), 200);
        
    }

    public function addPayments($classId,$unitId)
    {
        $timing = ClassTiming::find($unitId);

        $tutor_payment_calculator = $timing->tutor_payment_calculator;

        if($timing->tutor_payment != Input::get('tutor_payment'))
        {
            $timing->tutor_payment_calculator = 'manual';
        }
        
        $timing->tutor_payment = Input::get('tutor_payment');

        $timing->save();

        return \Response::json(array(
            'success' => true,
        ), 200);
        // dd($classId,$unitId,Input::get('tutor_payment'));
    }

    public function getNotesForClass($classId)
    {
        $ahamClass = AhamClass::find($classId);

        return $this->response()->collection($ahamClass->notes,new NoteTransformer);
    }

    public function addNotesToClass($classId)
    {
        $rules = [
            'note' => 'required'
        ];

        $v = \Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(422);

        }

        $user = $this->auth->user();

        $ahamClass = AhamClass::find($classId);

        $note = [];
        
        $note['note'] = Input::get('note');
        $note['user_id'] = $user->id;
        $note['of_id'] = $ahamClass->id;
        $note['of_type'] = get_class($ahamClass);

        Note::create($note);

        return $this->response()->collection($ahamClass->notes,new NoteTransformer);
    }

    public function getStudentInvite($classId)
    {
        $ahamClass = AhamClass::find($classId);

        $alreadyInvited = $ahamClass->studentInvitations;

        $students = Student::where('active',true)
                            ->whereNotIn('id',$alreadyInvited->pluck('student_id')->toArray())
                            ->get();
        $students = $this->removeAllocatedTutorFromStudent($ahamClass, $students);                            
        $allStudents = [];

       

        $classTeachers = ClassTiming::with('teacher.user')
                                    ->where('class_id',$ahamClass->id)
                                    ->where('status','pending')
                                    ->WhereNotNull('teacher_id')
                                    ->groupBy('teacher_id')
                                    ->get();

        //remove the tutor is alloctated to current class 
        if(count($classTeachers)>0)
        {
            foreach ($students as $key => $student)
            {
                   foreach ($classTeachers as $classTeacher)
                    {
                        $teacher=$classTeacher->teacher;
                        if(!is_null($teacher->user->id))
                        {
                            if($student->user->id==$teacher->user->id)
                            {
                                unset($students[$key]);
                                //$students->forget($key);
                            }
                        }
                    }

            }
        }

        //who are enroled as student
        $enrollStudents = StudentEnrollment::with('student.classes','student.user')
                                                ->where('class_id',$ahamClass->id)
                                                ->where('status','enrolled')
                                                ->get();
        
        //remove the teachers whos are enroled to class
        foreach ($enrollStudents as $enrollStudent)
        {
            $estudent=$enrollStudent->student;
            foreach ($students as $key => $student)
            {
                if($student->user->id==$estudent->user->id)
                {
                    unset($students[$key]);
                }
            }

        }
        
        foreach($students as $student)
        {
            $allStudents[] = [
                'id' => $student->id,
                'email' => $student->user->email,
                'name' => $student->user->name
            ];
        }

        $invitedStudents = [];

        foreach($alreadyInvited as $invited)
        {
            $invitedStudents[] = [
                'id' => $invited->student_id,
                'email' => $invited->student->user->email,
                'name' => $invited->student->user->name,
                'status' => $invited->status
            ];
        }

        return [    
            'invitedStudents' => $invitedStudents,
            'allStudents' => $allStudents,
        ];
    }

    public function studentInvite($classId)
    {
        $invitations =  Input::get('invitations');

        $ahamClass = AhamClass::find($classId);

        // An invite is sent at Class level, not unit level.

        foreach($invitations as $invitation)
        {
            StudentInvitation::firstOrCreate([
                'class_id' => $ahamClass->id,
                'student_id' => $invitation
            ]);
        }

        $ahamClass->save();

        return response()->json([
                'success' => true
        ],200);
    }

    public function changeTopic($classId, $topicId)
    {
        $ahamClass = $this->ahamClass->find($classId);
        $topic = Topic::find($topicId);

        if($ahamClass->topic_id != $topicId)
        {
            $this->ahamClass->transferTopic($ahamClass, $ahamClass->topic, $topic);
        }

        return response()->json([
                'success' => true
        ],200);
    }


    public function cancelClass($classId)
    {

        $ahamClass = $this->ahamClass->find($classId);

        $ahamClass->status = 'cancelled';
        $ahamClass->cancelled_at = Carbon::now();
        $ahamClass->cancellation_reason = Input::get('remarks','');
        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->status = 'cancelled';
            $timing->save();
        }

        ClassStatusManager::giveBackCredits($ahamClass);

        event(new \Aham\Events\AdminCancelledClass($ahamClass));

        return response()->json([
            'success' => true
        ],200);
    }

    public function markAsDone($classId,$unitId)
    {
        $timing = ClassTiming::find($unitId);

        $ahamClass = $timing->ahamClass;

        \DB::beginTransaction();

        $timing->status = 'done';
        $timing->remarks = Input::get('remarks');
        $timing->save();

        if($ahamClass->topic->units->count() == $ahamClass->timings()->where('status','done')->count())
        {
            $ahamClass->status = 'get_feedback';
            $ahamClass->save();
        }

        \DB::commit();
        // $ahamClass = $ahamClass->refresh();

        if($ahamClass->status == 'get_feedback')
        {
            event(new \Aham\Events\Teacher\GetFeedback($ahamClass));
        }

        return response()->json([
                'success' => true
        ],200);
    }

    public function finalizePayments($classId)
    { 
        $ahamClass = AhamClass::with('topic','enrollments.student')->find($classId);
        $ahamClass->payment_finalized = true;
        $ahamClass->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Done']]
        ), 200);
    }

    public function calculatePayments($classId)
    { 
        $ahamClass = AhamClass::with('timings.enrolledLearners')->find($classId);
        PaymentCalculatorHelper::calculateForClass($ahamClass);

        return \Response::json(array(
            'success' => true,
            'errors' => [['Done']]
        ), 200);
    }
    

    public function completeClass($classId)
    {  
        $ahamClass = AhamClass::with('topic','enrollments.student')->find($classId);

        $enrollmentCount = $ahamClass->enrollments->count();
        $enrollmentCount = $enrollmentCount >  4 ? $enrollmentCount : 4;

        if($ahamClass->free)
        {
            $enrollmentCount = 4;
        }

        $totalWorth = $enrollmentCount*$ahamClass->classUnits->count()*1000;

        $amount = ($ahamClass->commission/100)*$totalWorth;

        if($ahamClass->no_tutor_comp)
        {
            $amount = 0;
        }

        $teacher = $ahamClass->teacher;

        \DB::beginTransaction();

        $teacher->earnings = $teacher->earnings + $amount;
        $teacher->save();

        $ahamClass->status = 'completed';
        $ahamClass->completed_at = Carbon::now();
        $ahamClass->teacher_amount = $amount;
        $ahamClass->save();

        \DB::commit();

        foreach($ahamClass->enrollments as $enrollment)
        {
                if( $enrollment->feedback != 'ghost' && $enrollment->feedback != 'absent')
                {
                    $assessment = StudentAssessment::firstOrCreate([
                        'student_id' => $enrollment->student->id,
                        'topic_id' => $ahamClass->topic->id
                    ]);

                    $assessment->mode = 'aham_class';
                    $assessment->save();
                }

        }

        event(new \Aham\Events\ClassCompleted($ahamClass));

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully completed']]
        ), 200);

    }

    public function repeatClass($classId)
    {
        RepeatClass::create([
            'class_id' => $classId,
            'payload' => serialize(Input::all())
        ]);

        return response()->json([
                'success' => true
        ],200);
    }

    public function removeAllocatedTutorFromStudent($ahamClass, $students)
    {
        $classTeachers = ClassTiming::with('teacher.user')
                                    ->where('class_id',$ahamClass->id)
                                    ->where('status','pending')
                                    ->WhereNotNull('teacher_id')
                                    ->groupBy('teacher_id')
                                    ->get();

        //remove the tutor is alloctated to current class 
        if(count($classTeachers)>0)
        {
            foreach ($students as $key => $student)
            {
               foreach ($classTeachers as $classTeacher)
                {
                    $teacher=$classTeacher->teacher;
                    if(!is_null($teacher->user->id))
                    {
                        if($student->user->id==$teacher->user->id)
                        {
                            unset($students[$key]);
                        }
                    }
                }
            }
        }

        return $students;
    } 


}
