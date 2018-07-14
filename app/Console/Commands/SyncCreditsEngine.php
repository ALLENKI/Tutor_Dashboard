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

class SyncCreditsEngine extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aham:sync_credits_engine';

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
        // First let's add

        DB::table('credits_purchased')->truncate();
        DB::table('credits_hub_only')->truncate();
        DB::table('credits_promotional')->truncate();
        DB::table('credits_buckets')->truncate();
        DB::table('credits_used')->truncate();
        DB::table('credits_refunds')->truncate();

        $learners = Student::all();

        foreach($learners as $learner)
        {
            $sync = new Sync();
            $sync->learner($learner->id);
        }
    }
}
