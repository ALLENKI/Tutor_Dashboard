<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\CreditsEngine\Add;
use Aham\CreditsEngine\Refund;
use Aham\CreditsEngine\Used;
use Aham\CreditsEngine\Sync;

use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\UserEnrollment;
use Aham\Models\SQL\Student;
use DB;

class ResetBuckets extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aham:reset_buckets';

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
        $learners = Student::all();

        foreach($learners as $learner)
        {
            $sync = new Sync();
            $sync->resetBuckets($learner->id);
        }
    }
}
