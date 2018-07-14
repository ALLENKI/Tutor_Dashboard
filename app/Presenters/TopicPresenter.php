<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class TopicPresenter extends Presenter{

    public function typeStyled()
    {
        switch ($this->entity->type) {
        	case 'subject':
        		return '<span class="label label-success">Subject</span>';
        		break;
        	
			case 'topic':
				return '<span class="label label-primary">Topic</span>';
				break;

			case 'sub-category':
				return '<span class="label label-info">Sub Category</span>';
				break;

			case 'category':
				return '<span class="label bg-purple">Category</span>';
				break;	
        }
    }

    public function levelStyled()
    {
        if($this->entity->type != 'topic')
        {
            return '<span class="label label-default">NA</span>';
        }

        switch ($this->entity->level) {
        	case '1':
        		return '<span class="label label-success">1</span>';
        		break;
        	
			case '2':
				return '<span class="label label-primary">2</span>';
				break;

			case '3':
				return '<span class="label bg-purple">3</span>';
				break;	
        }
    }

    public function statusStyled()
    {
        if($this->entity->type != 'topic')
        {
            return '<span class="label label-default">NA</span>';
        }

        switch ($this->entity->status) {
            case 'active':
                return '<span class="label label-success">Active</span>';
                break;
            
            case 'in_progress':
                return '<span class="label label-primary">In Progress</span>';
                break;

            case 'in_future':
                return '<span class="label bg-purple">In Future</span>';
                break; 

            case 'obsolete':
                return '<span class="label bg-purple">Obsolete</span>';
                break;  
        }
    }

    public function picture()
    {
        if($this->entity->picture)
        {
            return $this->entity->picture->public_id.'.'.$this->entity->picture->format;
        }
        else
        {
            return 'aham_icon_m6ljr5.png';
        }
        
    }

    public function coverPicture()
    {
        if($this->entity->coverPicture)
        {
            return $this->entity->coverPicture->public_id.'.'.$this->entity->coverPicture->format;
        }
        else
        {
            return 'Default-Cover-Page_ejhdup.png';
        }
        
    }
}
