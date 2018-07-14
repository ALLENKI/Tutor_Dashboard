<?php namespace Aham\Managers;

use Carbon;

class RoomAvailability{

	public $availability;
	public $timeStrings;

	public function __construct()
	{
		$classrooms = ['CL1','CL2','CL3'];

		$faker = \Faker\Factory::create();

		$availability = [];

		foreach($classrooms as $classroom)
		{
			for ($i=1; $i <= 48 ; $i++) 
			{ 
				$availability[$classroom][] = (int) $faker->boolean;
			}
		}

		$Y2K = Carbon::create(2000, 1, 1, 0, 0, 0);

		$timeStrings = [];

		for ($i=1; $i <= 48 ; $i++) 
		{ 
			if($i == 1)
			{
				$timeStrings[] = $Y2K->format('H:i');
			}
			else
			{
				$timeStrings[] = $Y2K->addMinutes(30)->format('H:i');
			}
		}

		$this->timeStrings = $timeStrings;

		$str = $this->makeStorableString($availability);

		$availability = $this->readAvailabilityString($str);

		$occupiedSlots = $this->getOccupiedSlots($availability['CL1']);
		$freeSlots = $this->getFreeSlots($availability['CL1']);

		var_dump($availability['CL1']);

		dd($freeSlots);

		$occupiedTimings = $this->getHumanReadableTimings($slots['occupied']);
		$freeTimings = $this->getHumanReadableTimings($slots['free']);

		var_dump($occupiedTimings);
		var_dump($freeTimings);

		dd($this->timeStrings);
	}

	public function makeStorableString($someArray)
	{
		$storableString = serialize($someArray);

		return $storableString;
	}

	public function readAvailabilityString($str)
	{
		return unserialize($str);
	}

	public function getOccupiedSlots($someArray)
	{
		$length = count($someArray);
		$occupied = [];

		for ($i=0; $i < $length; $i++) 
		{ 
			$occ = [];

			if(isset($someArray[$i]) && $someArray[$i] != 0)
			{
				$occ[] = $i;
				$i++;

				while(isset($someArray[$i]) && $someArray[$i] != 0)
				{
					$occ[] = $i;
					$i++;
				}
			}


			if(count($occ))
			{
				$occupied[] = $occ;
			}
		}

		return $occupied;

	}

	public function getFreeSlots($someArray)
	{
		$length = count($someArray);
		$free = [];

		for ($i=0; $i < $length; $i++) 
		{ 
			$fre = [];

			if(isset($someArray[$i]) && !$someArray[$i])
			{
				//Before and after shouldn't be 1

				//What if $i = -1 and $i = 48

				if(
					isset($someArray[$i-1]) && 
					$someArray[$i-1] == 0 && 
					isset($someArray[$i+1]) && 
					$someArray[$i+1] == 0
				)
				{
					$fre[] = $i;
				}
				
				if(!isset($someArray[$i-1]) && isset($someArray[$i+1]) && $someArray[$i+1] == 0)
				{
					$fre[] = $i;
				}

				if(!isset($someArray[$i+1]) && isset($someArray[$i-1]) && $someArray[$i-1] == 0)
				{
					$fre[] = $i;
				}

				$i++;

				while(isset($someArray[$i]) && $someArray[$i] == 0)
				{
					if(
						isset($someArray[$i-1]) && 
						$someArray[$i-1] == 0 && 
						isset($someArray[$i+1]) && 
						$someArray[$i+1] == 0
					)
					{
						$fre[] = $i;
					}
					
					if(!isset($someArray[$i-1]) && isset($someArray[$i+1]) && $someArray[$i+1] == 0)
					{
						$fre[] = $i;
					}

					if(!isset($someArray[$i+1]) && isset($someArray[$i-1]) && $someArray[$i-1] == 0)
					{
						$fre[] = $i;
					}

					$i++;
				}
			}

			if(count($fre))
			{
				$free[] = $fre;
			}
		}

		return $free;

	}

	public function getHumanReadableTimings($slots)
	{
		$readableTimings = [];

		foreach($slots as $slot)
		{
			$firstSlot = $slot[0];
			$lastSlot = $slot[count($slot)-1];

			if($lastSlot == 47)
			{
				$lastSlot = -1;
			}

			$readableTimings[] = $this->timeStrings[$firstSlot].'-'.$this->timeStrings[$lastSlot+1];
		}

		return $readableTimings;
		
	}

}