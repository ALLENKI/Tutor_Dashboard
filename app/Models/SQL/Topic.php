<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;
use Karl456\Presenter\Traits\PresentableTrait;
use \Cviebrock\EloquentTaggable\Taggable;
use Artisan;

class Topic extends RevSoftModel implements UniquifyInterface
{
    use Taggable;
    use UniqueNoTrait;
    use PresentableTrait;

    protected $presenter = 'Aham\Presenters\TopicPresenter';

    use \Cviebrock\EloquentSluggable\Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'separator' => ''
            ]
        ];
    }

    protected $table = 'topics';

    public $typeOptions = [
                              'category' => 'Category',
                              'subject' => 'Subject',
                              'sub-category' => 'Sub Category',
                              'topic' => 'Topic'
                             ];

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

        static::created(function ($topic) {
            Artisan::queue('aham:sync_topics');
        });

        static::updated(function ($topic) {
            Artisan::queue('aham:sync_topics');
        });

        static::deleting(function ($topic) {
            if ($topic->children->count()) {
                dd('This ' . $topic->type . ' has children. You cannot delete it');
            }

            foreach ($topic->children as $child) {
                $child->parent_id = 0;
                $child->save();
            }

            foreach ($topic->units as $unit) {
                $unit->delete();
            }

            foreach ($topic->studentAssessments as $assessment) {
                $assessment->delete();
            }

            foreach ($topic->teacherCertifications as $certification) {
                $certification->delete();
            }

            TopicPrerequisite::where('requirer_id', $topic->id)->delete();
            TopicPrerequisite::where('topic_id', $topic->id)->delete();
        });
    }

    public function picture()
    {
        return $this->morphOne('Aham\Models\SQL\CloudinaryImage', 'of')->where('type', 'picture');
    }

    public function coverPicture()
    {
        return $this->morphOne('Aham\Models\SQL\CloudinaryImage', 'of')->where('type', 'cover');
    }

    /**
     * [scopeOrdered description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * [scopeOrdered description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeTopic($query)
    {
        return $query->where('type', 'topic')
                    ->where('parent_id', '<>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * [getUniqueNoSource description]
     * @return [type] [description]
     */
    public function getUniqueNoSource()
    {
        return $this->type . ' ' . trim($this->name);
    }

    /**
     * [children description]
     * @return [type] [description]
     */
    public function children()
    {
        return $this->hasMany('Aham\Models\SQL\Topic', 'parent_id')
                    ->ordered();
    }

    /**
     * [units description]
     * @return [type] [description]
     */
    public function units()
    {
    
        return $this->hasMany('Aham\Models\SQL\Unit')->ordered();
    }

    public function classes()
    {
        return $this
                ->hasMany('Aham\Models\SQL\AhamClass');
    }

    public function ahamClass()
    {
        return $this->morphMany('Aham\Models\SQL\AhamClass','of ');
    }
    
    /**
     * [units description]
     * @return [type] [description]
     */
    public function studentAssessments()
    {
        return $this->hasMany('Aham\Models\SQL\StudentAssessment', 'topic_id');
    }

    /**
     * [units description]
     * @return [type] [description]
     */
    public function teacherCertifications()
    {
        return $this->hasMany('Aham\Models\SQL\TeacherCertification', 'topic_id');
    }

    /**
     * [parent description]
     * @return [type] [description]
     */
    public function parent()
    {
        return $this->belongsTo('Aham\Models\SQL\Topic', 'parent_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('Aham\Models\SQL\Topic', 'parent_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo('Aham\Models\SQL\Topic', 'parent_id', 'id');
    }


    public function lookup()
    {
        return $this->hasOne('Aham\Models\SQL\TopicsLookup', 'topic_id');
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function prerequisites()
    {
        return $this->belongsToMany('Aham\Models\SQL\Topic', 'topic_prerequisites', 'requirer_id', 'topic_id')
                    ->whereNull('topic_prerequisites.deleted_at');
    }

    public function goals()
    {
        return $this->belongsToMany('Aham\Models\SQL\Goal');
    }

    public function whishList()
    {
        return $this->belongsToMany('Aham\Models\SQL\WhishList','of');
    }

    public function topics()
    {
        return $this->belongsToMany('Aham\Models\SQL\Topic', 'topics_lookup', 'subject_id', 'topic_id');
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function successor()
    {
        $keys = array_keys($this->typeOptions);
        $index = array_search($this->type, array_keys($this->typeOptions));

        if (($index + 1) >= count($keys)) {
            return null;
        }

        return $keys[$index + 1];
    }

    public function predecessor()
    {
        $keys = array_keys($this->typeOptions);
        $index = array_search($this->type, array_keys($this->typeOptions));

        if (($index - 1) < 0) {
            return null;
        }

        return $keys[$index - 1];
    }

    public function adminShowLink()
    {
        return route('admin::topic_tree::topics.show', $this->id);
    }

    public function files()
    {
        return $this->morphMany('Aham\Models\SQL\File', 'of');
    }

}
