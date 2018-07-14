<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class SchedulingRule extends RevSoftModel implements UniquifyInterface
{
    protected $table = 'scheduling_rules';

    protected $guarded = [];

    use UniqueNoTrait;

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });
    }

    public function getUniqueNoSource()
    {
    	return 'R'.' '.$this->no_of_units.' '.$this->days;
    }
}
