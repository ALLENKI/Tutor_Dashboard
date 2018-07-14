<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;

use Aham\Helpers\AssessmentHelper;

class TeachersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('teachers')->truncate();
        DB::table('teacher_certification')->truncate();

        $faker = Faker\Factory::create();

        $users = User::orderBy(DB::raw('RAND()'))->take(20)->get();

        foreach($users as $user)
        {
            $teacher = new Teacher();

            $teacher = $user->teacher()->save($teacher);

            $teacher = $user->teacher;

            $topics = Topic::orderBy(DB::raw('RAND()'))
                        ->take(5)
                        ->get();

            foreach ($topics as $topic) 
            {
                AssessmentHelper::addTeacherCertification($teacher->id, $topic->id);
            }
        }

    }
}
