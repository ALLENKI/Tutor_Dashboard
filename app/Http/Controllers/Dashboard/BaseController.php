<?php

namespace Aham\Http\Controllers\Dashboard;

use Aham\Http\Controllers\Controller;
use Assets;
use Sentinel;

class BaseController extends Controller
{
    public $assets;
    public $user;

    /**
     * undocumented function.
     *
     * @author
     **/
    public function __construct()
    {
        $assets = [
            'css_dir' => env('DASHBOARD_ASSETS_CSS_DIR', '/theme'),
            'js_dir' => env('DASHBOARD_ASSETS_JS_DIR', '/theme'),
            'pipeline' => env('DASHBOARD_ASSETS_PIPELINE', false),
        ];

        Assets::config($assets);

        Assets::reset();


        Assets::registerCollection('bootstrap-modal', 
        [
            'js/plugins/ui/bootstrap-modal/css/bootstrap-modal-bs3patch.css',
            'js/plugins/ui/bootstrap-modal/css/bootstrap-modal.css',
            'js/plugins/ui/bootstrap-modal/js/bootstrap-modalmanager.js',
            'js/plugins/ui/bootstrap-modal/js/bootstrap-modal.js',
        ]);

        Assets::add("css/icons/icomoon/styles.css");
        Assets::add("css/bootstrap.css");
        Assets::add("css/core.css");
        Assets::add("css/components.css");
        Assets::add("css/colors.css");

        Assets::add("js/plugins/loaders/pace.min.js");
        Assets::add("js/core/libraries/jquery.min.js");
        Assets::add('js/core/libraries/jquery_ui/full.min.js');
        Assets::add("js/core/libraries/bootstrap.min.js");
        Assets::add("js/plugins/loaders/blockui.min.js");
        Assets::add('js/plugins/jquery.restfulizer.js');
        Assets::add('js/plugins/jquery.form.min.js');
        Assets::add("js/plugins/ui/ripple.min.js");
        Assets::add("js/plugins/jquery.cropit.js");
        Assets::add('js/plugins/forms/styling/uniform.min.js');

        Assets::add("js/plugins/forms/styling/switchery.min.js");
        Assets::add("js/plugins/forms/styling/switch.min.js");

        Assets::add('js/plugins/forms/selects/select2.min.js');
        Assets::add('js/plugins/forms/selects/bootstrap_multiselect.js');
        Assets::add('js/plugins/loaders/blockui.min.js');
        Assets::add('bootstrap-modal');

        Assets::add('js/plugins/notifications/jquery-toast-plugin/jquery.toast.min.css');
        Assets::add('js/plugins/notifications/jquery-toast-plugin/jquery.toast.min.js');
        
        $user = Sentinel::getUser();

        view()->share('user', $user);

        $this->user = $user;
        
    }
}