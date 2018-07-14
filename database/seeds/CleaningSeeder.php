<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Country;
use Aham\Models\SQL\State;
use Aham\Models\SQL\City;

class CleaningSeeder extends Seeder
{
    public function run()
    {
        DB::table('classes')->truncate();
        DB::table('classrooms')->truncate();
        DB::table('classroom_slots')->truncate();
        DB::table('class_invitations')->truncate();
        DB::table('class_timings')->truncate();
        DB::table('locations')->truncate();
        DB::table('location_calendar')->truncate();
        DB::table('students')->truncate();
        DB::table('student_assessments')->truncate();
        DB::table('student_credits')->truncate();
        DB::table('student_enrollments')->truncate();
        DB::table('teachers')->truncate();
        DB::table('teacher_certification')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_users')->truncate();
        DB::table('activations')->truncate();
        DB::table('persistences')->truncate();
        DB::table('persistences')->truncate();
        DB::table('reminders')->truncate();
        DB::table('throttle')->truncate();

        $credentials = [
            'email'    => 'ajitha@ahamlearning.com ',
            'password' => 'ahamlearning.com',
            'name' => 'Aham Admin',
        ];

        $user = \Sentinel::registerAndActivate($credentials);

        $superuser = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Superuser',
            'slug' => 'superuser',
        ]);

        $superuser->permissions = [
            'superuser' => true,
        ];

        $superuser->save();

        $superuser->users()->attach($user);

        $user->save();

    }
}
