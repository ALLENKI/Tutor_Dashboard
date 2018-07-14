<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;

use Aham\Helpers\AssessmentHelper;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        // AssessmentHelper::addStudentAssessment(1, 52);

        // dd('bye');

        DB::table('students')->truncate();
        DB::table('student_assessments')->truncate();

        $faker = Faker\Factory::create();

        $users = User::orderBy(DB::raw('RAND()'))->take(20)->get();

        foreach($users as $user)
        {
            $student = new Student();

            $student = $user->student()->save($student);

            $student = $user->student;

            $topics = Topic::orderBy(DB::raw('RAND()'))
                        ->take(5)
                        ->get();

            foreach ($topics as $topic) 
            {
                AssessmentHelper::addStudentAssessment($student->id, $topic->id);
            }
        }

    }
}
