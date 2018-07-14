<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;
use Aham\Models\SQL\AhamClass;
use Aham\Managers\ClassStatusManager;
use Carbon;

class CancelUselessClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:cancel_useless_classes';

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
        $classes = AhamClass::whereBetween('id',[4112,6624])->get();

        foreach($classes as $ahamClass)
        {

            $this->line('Aham Class:',$ahamClass->id);

            $ahamClass->status = 'cancelled';
            $ahamClass->cancelled_at = Carbon::now();
            $ahamClass->cancellation_reason = 'System created invalid class';
            $ahamClass->save();

            foreach($ahamClass->timings as $timing)
            {
                $timing->status = 'cancelled';
                $timing->save();
            }

            ClassStatusManager::giveBackCredits($ahamClass);
        }

    }
}
