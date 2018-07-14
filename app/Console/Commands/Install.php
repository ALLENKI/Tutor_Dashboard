<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;

class Install extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:install';

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
		$this->call('down');
		
		if(\Schema::hasTable('migrations'))
		{
			$this->call('migrate:reset');
		}
		
		\Schema::dropIfExists('migrations');

		// Create the migrations table
		$this->call('migrate:install');

		$this->call('migrate');

		$this->line('Creating an User');

		$credentials = [
		    'email'    => 'ajitha@ahamlearning.com',
		    'password' => 'password',
		    'name' => 'Aham Admin',
		    'interested_in' => 'teacher'
		];

		$user = \Sentinel::registerAndActivate($credentials);

		$superuser = Sentinel::getRoleRepository()->createModel()->create([
		    'name' => 'Superuser',
		    'slug' => 'superuser',
		]);

		$superuser->permissions = [
		    'superuser' => true,
		];

		$superuser->save();

		$superuser->users()->attach($user);

		$user->save();

		$this->line('Finished');

		$this->call('up');

	}

}
