<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CloudinaryImage extends RevSoftModel
{
    protected $table = 'cloudinary_images';

    protected $guarded = [];

	public function of()
    {
        return $this->morphTo();
    }

}
