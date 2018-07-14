<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Models\SQL\CreditsUsed;
use Aham\Models\SQL\CreditsRefund;
use Aham\Models\SQL\User;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentEnrollment;

class CreditsUsedDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:credits_used_date';

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
        $nullDates = CreditsUsed::with('user.student')->whereNull('used_on')->get();

        foreach($nullDates as $nullDate)
        {
              $class = $nullDate->of;
              $user = $nullDate->user;

              $studentEnrolled = StudentEnrollment::where('student_id',$user->student->id)
                                            ->where('class_id',$class->id)
                                            ->first();

              $nullDate->used_on = $studentEnrolled->created_at;

              $nullDate->save();

              print_r($nullDate->id,'\n');
        }

        $nullDates = CreditsRefund::whereNull('refunded_on')->get();

        foreach($nullDates as $nullDate)
        {
              $nullDate->refunded_on = $nullDate->created_at;

              $nullDate->save();

              print_r($nullDate->id,'\n');
            
        }

    }
}
