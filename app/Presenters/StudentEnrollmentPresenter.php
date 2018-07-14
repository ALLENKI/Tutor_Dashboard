<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class StudentEnrollmentPresenter extends Presenter{

    public function typeFeedback()
    {
        switch ($this->entity->feedback) {
        	case 'excellent':
        		return '<span class="label label-success">Excellent</span>';
        		break;
        	
			case 'good':
				return '<span class="label label-primary">Good</span>';
				break;

			case 'needs_practice':
				return '<span class="label label-danger">Needs Practice</span>';
				break;

            default:
                return '<span class="label label-default">NA</span>';;
                break;
        }
    }

}
