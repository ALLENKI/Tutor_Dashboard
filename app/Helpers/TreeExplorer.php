<?php

namespace Aham\Helpers;

use Carbon;
use DB;
use Log;

use Aham\Helpers\AssessmentHelper;

class TreeExplorer {

    public $explored = [];

    public function getFullTree(array $topics)
    {

        foreach($topics as $topic)
        {

            if(!in_array($topic, $this->explored))
            {
                $this->explored[] = intval($topic);
            }

            $result = AssessmentHelper::getPrerequisitesAndChildren($topic);

            if(count($result))
            {
                $this->getFullTree($result);
            }

        }

        return true;
    }

}
