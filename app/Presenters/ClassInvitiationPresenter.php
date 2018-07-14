<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class ClassInvitiationPresenter extends Presenter{

    public function statusStyled()
    {
        switch ($this->status) {
        	case 'pending':
        		return '<span class="label label-primary">PENDING</span>';
        		break;
        	
			case 'accepted':
				return '<span class="label label-success">ACCEPTED</span>';
				break;

            case 'rejected':
                return '<span class="label label-danger">REJECTED</span>';
                break;

            case 'alternate_timing':
                return '<span class="label label-warning">Interested</span>';
                break;
        }
    }

}
