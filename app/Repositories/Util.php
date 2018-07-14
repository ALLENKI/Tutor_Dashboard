<?php

namespace Aham\Repositories;

class Util
{

    static public function findKey($keySearch, $array) {

        foreach ($array as $key => $item) {
            if ($key == $keySearch) {
                return true;
            } elseif (is_array($item) && Util::findKey($keySearch, $item)) {
                return true;
            }
        }

        return false;
    }
    
}