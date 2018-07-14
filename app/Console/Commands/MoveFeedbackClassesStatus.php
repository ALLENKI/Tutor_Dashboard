<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Aham\Models\SQL\AhamClass;

class MoveFeedbackClassesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:move_feedback_classes_state';

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
        $feedbackClasses = AhamClass::whereIn('status',['get_feedback','got_feedback'])->get();

        foreach($feedbackClasses as $feedbackClass)
        {
            var_dump('ClassId: '.$feedbackClass->id.' Class Status: '.$feedbackClass->status);
            $feedbackClass->status = 'completed';
            $feedbackClass->save();
        }

    }
}
