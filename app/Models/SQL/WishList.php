<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $table = 'wishlists';

    protected $guarded = [];

    public function of()
    {
        return $this->morphTo();
    }

    public function student()
    {
        return $this->hasMany('Aham\Models\SQL\Student', 'student_id');
    }

    public function hub()
    {
        return $this->hasMany('Aham\Models\SQL\Hub', 'hub_id');
    }

}