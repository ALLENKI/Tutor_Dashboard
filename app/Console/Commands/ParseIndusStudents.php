<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use DB;

use Excel;
use Sentinel;
use Aham\CreditsEngine\Add;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;

class ParseIndusStudents extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aham:parse_indus_students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Credits Engine';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reader = Excel::load(public_path('Indus_Boarders_Email.xlsx'), function($reader) {

        })->get();

        $rows = $reader->all();

        foreach($rows as $row)
        {
            $row = $row->toArray();

            $this->line(strtolower(trim($row['student_email_id'])));

            $existingUser = User::where('email',strtolower(trim($row['student_email_id'])))->first();

            if(is_null($existingUser))
            {
                $credentials = [
                    'email'    => strtolower(trim($row['student_email_id'])),
                    'password' => 'indus@123',
                    'name' => $row['name'],
                    'grade' => $row['grade']
                ];

                $user = \Sentinel::registerAndActivate($credentials);

                $student = new Student();

                $user->student()->save($student);
                $student->hubs()->sync([3]);

                $creditsAddEngine = new Add($user->id, 'INR', 3);
                $creditsAddEngine->hubOnly(10, 11000, "Enrolling Indus Student",'cheque');

            }
            else
            {
                $learner = $existingUser->student;
                $learner->hubs()->sync([3]);
            }


            
        }

    }
}
