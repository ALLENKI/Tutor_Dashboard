<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class RevSoftModel extends Model
{
    use SoftDeletes;
    
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $historyLimit = 10;
    protected $revisionCleanup = true;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

	protected $dates = ['deleted_at'];
}