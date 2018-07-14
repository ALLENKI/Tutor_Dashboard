<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class ClassAttachment extends Model
{

    protected $table = 'class_attachments';

    protected $guarded = [];

    public function ahamClass()
    {
    	return $this->belongsTo('Aham\Models\SQL\AhamClass','class_id');
    }

    public function uploader()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','uploader_id');
    }
}
