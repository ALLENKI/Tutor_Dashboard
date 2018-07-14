<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;
use Log;

use Aham\Models\SQL\MobileOtp;

class RemoveExpiredOTPs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:remove_otps';

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
		$otps = MobileOtp::where('expires_on','<',\Carbon::now())->delete();

	}


}
