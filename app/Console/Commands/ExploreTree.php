<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;
use Log;

use Aham\Helpers\AssessmentHelper;

class ExploreTree extends Command {

	public $explored = [];
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:exploreTree';

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
		$result = $this->getFullTree([79]);

		dd($result);
	}

    public function getFullTree(array $topics)
    {
    	// Log::info('Get Full Tree For');
    	Log::info($this->explored);

        $items = [];

        foreach($topics as $topic)
        {
        	// var_dump($items);

        	if(in_array($topic, $this->explored))
        	{
        		return [];
        	}

        	$this->explored[] = $topic;

        	Log::info('Exploring '.$topic);

            $result = AssessmentHelper::getPrerequisitesAndChildren($topic);

            // var_dump($result);

            if(count($result))
            {
                $result = array_merge($result,$this->getFullTree($result));
            }

            $items = array_merge($items, $result);
        }

    	// Log::info('Items');
    	// Log::info($items);

        // var_dump($items);

        return $items;
    }
}
