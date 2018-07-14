<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;
use Log;

use Aham\Helpers\RepeatClassHelper;
use Aham\Models\SQL\RepeatClass;


class ProcessRepeatClasses extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:process_repeat_classes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install New Aham';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$repeatClasses = RepeatClass::where('processed',false)->get();

		foreach($repeatClasses as $repeatClass)
		{	
	        $repeatClass->processed = true;
	        $repeatClass->save();

	        $helper = new RepeatClassHelper($repeatClass);
	        $helper->process();

		}

	}


}
