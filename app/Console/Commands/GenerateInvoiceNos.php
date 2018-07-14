<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;
use Log;

use Aham\Models\SQL\StudentCredits;

class GenerateInvoiceNos extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:generate_invoices';

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
		$credits = StudentCredits::all();

		foreach ($credits as $credit) 
		{
			if($credit->coupon)
			{

			}
			else
			{
	            $suffix = str_pad($credit->id, 5, '0', STR_PAD_LEFT);
	            $suffix = $credit->created_at->format('mdY').$suffix;
	            $invoice_no = 'ALH'.$suffix;
	            $this->line($invoice_no);
	            $credit->invoice_no = $invoice_no;
	            
	            $credit->save();

	            $this->line($credit->invoice_no);		
			}

		}

	}


}
