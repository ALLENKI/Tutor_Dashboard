<?php namespace Aham\Traits;


trait UniqueNoTrait {


	public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getUniqueNoDestination()
    {
    	$field = $this->unique_no_destination;

    	return $field;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getUniqueNoSource()
    {
    	$field = $this->unique_no_source;

    	$string = $this->$field;

    	return $string;
    }

	public function uniquify()
	{
		$string = trim($this->getUniqueNoSource());

		$destination = $this->getUniqueNoDestination();

		$string = str_replace('&', '', $string);

		$string = preg_replace('/[^A-Za-z0-9\- ]/', '', $string);

		$string = preg_replace('!\s+!', ' ', $string);

		$string = trim($string);

		$words = explode(" ", $string);

		$acronym = "";

		// dd($words);

		if(count($words) > 1)
		{
			foreach ($words as $w) 
			{
		  		$acronym .= $w[0];
			}
		}
		else
		{
			$acronym = $string;
		}

		$acronym = substr($acronym, 0, 3);

		$prefix = strtoupper($acronym);
		
		$instance = new static;

		$query = $instance->where($destination, 'LIKE', $acronym.'%' );

		if(method_exists($this, 'BootSoftDeletes'))
		{
			$query = $query->withTrashed();
		}

		$count = $query->count();

		$suffix = str_pad($count, 7, '0', STR_PAD_LEFT);

		while($instance->where($destination, $prefix.$suffix)->first())
		{
			$count++;

			$suffix = str_pad($count, 7, '0', STR_PAD_LEFT);
		}

		$this->setAttribute($destination, $prefix.$suffix);

		return $this;
	}

}