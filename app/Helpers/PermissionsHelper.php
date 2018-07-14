<?php

namespace Aham\Helpers;

use Carbon;
use Config;
use DB;

use Aham\Models\SQL\Permission;

class PermissionsHelper {

    public static function load()
    {
        $permissions = Config::get('permissions');

        Permission::truncate();

        foreach($permissions as $permission)
        {
            $model = Permission::firstOrNew(['permission' => $permission['permission']]);

            $model->group = $permission['group'];
            $model->description = $permission['description'];
            $model->level = $permission['level'];
            $model->type = $permission['type'];

            $model->save();
        }

        return true;
    }

}
