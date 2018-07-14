<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;

use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Student;

class FixSelectedTimings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:fix_selected_timings';

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

        foreach($students as $student)
        {
            $student->selected_times_text = $student->selected_times_of_day;
            $student->save();
        }

    }
}
