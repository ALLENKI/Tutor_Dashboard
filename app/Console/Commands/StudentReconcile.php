<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;

use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Student;

use Excel;
use Carbon;

class StudentReconcile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:student_reconcile';

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $students = Student::all();

        $data = [];

        foreach($students as $student)
        {
            $reconcileHelper = new \Aham\Helpers\StudentReconcileHelper($student);

            $row = [];

            $row['Student_ID'] = $student->id;
            $row['User_ID'] = $student->user->id;
            $row['Name'] = $student->user->name;
            $row['Email'] = $student->user->email;

            $reconcileValues = $reconcileHelper->evaluate();

            $row = array_merge($row,$reconcileValues);

            $data[] = $row;
        }


        $filename = 'Students'.'-'.Carbon::now()->format('d-m-Y H:i:s');

        $result = Excel::create($filename, function($excel) use($data) {

            $excel->sheet('Sheet 1', function($sheet) use($data) {

                $sheet->fromArray($data);

            });

        })->store('xls',false, true);

        $filePath = $result['full'];

        \Mail::send('emails.recon',[],function($message) use ($filePath) {
            $message->to(['rajiv@betalectic.com','ajitha@ahamlearning.com','manasa@betalectic.com'])
                    ->subject('Recon on '.Carbon::now()->format('d-m-Y'))
                    ->attach($filePath);
        });

    }
}
