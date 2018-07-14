<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;
use Log;

class ExpireCoupons extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:expireCoupons';

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
		dd('expireCoupons');
	}


}
