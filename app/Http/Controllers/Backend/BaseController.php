<?php

namespace Aham\Http\Controllers\Backend;

use Aham\Http\Controllers\Controller;
use Assets;
use Sentinel;

class BaseController extends Controller
{
    public $assets;

    /**
     * undocumented function.
     *
     * @author
     **/
    public function __construct()
    {
        $assets = [
            'css_dir' => env('BACKEND_ASSETS_CSS_DIR', '/theme'),
            'js_dir' => env('BACKEND_ASSETS_JS_DIR', '/theme'),
            'pipeline' => env('BACKEND_ASSETS_PIPELINE', false),
        ];

        Assets::config($assets);

        Assets::reset();

        Assets::add('css/icons/icomoon/styles.css');
        Assets::add('css/bootstrap.css');
        Assets::add('css/core.css');
        Assets::add('css/components.css');
        Assets::add('css/colors.css');

        Assets::registerCollection('bootstrap-modal', 
        [
            'js/plugins/ui/bootstrap-modal/css/bootstrap-modal-bs3patch.css',
            'js/plugins/ui/bootstrap-modal/css/bootstrap-modal.css',
            'js/plugins/ui/bootstrap-modal/js/bootstrap-modalmanager.js',
            'js/plugins/ui/bootstrap-modal/js/bootstrap-modal.js',
        ]);

        Assets::registerCollection('datetime', 
        [
            'js/plugins/pickers/datetime/js/bootstrap-datetimepicker.min.js',
            'js/plugins/pickers/datetime/css/bootstrap-datetimepicker.min.css',
        ]);

        Assets::registerCollection('tags', 
        [
            'js/plugins/pickers/tags/jquery.tagsinput.min.css',
            'js/plugins/pickers/tags/jquery.tagsinput.min.js',
        ]);

        // Assets::add('js/plugins/loaders/pace.min.js');
        Assets::add('js/core/libraries/jquery.min.js');
        Assets::add('js/core/libraries/jquery_ui/full.min.js');
        Assets::add('js/core/libraries/bootstrap.min.js');
        Assets::add('js/plugins/loaders/blockui.min.js');
        Assets::add('js/plugins/ui/nicescroll.min.js');
        Assets::add('js/plugins/ui/drilldown.js');
        Assets::add('js/plugins/jquery.restfulizer.js');
        Assets::add('js/plugins/jquery.form.min.js');
        Assets::add('js/plugins/markerwithlabel_packed.js');
        Assets::add('js/plugins/ui/moment/moment.min.js');
        Assets::add('js/plugins/pickers/anytime.min.js');
        Assets::add('js/plugins/pickers/anytime.min.css');
        Assets::add('js/plugins/pickers/daterangepicker.js');
        Assets::add('js/plugins/forms/styling/uniform.min.js');

        Assets::add('js/plugins/tables/datatables/datatables.min.js');
        Assets::add('js/plugins/tables/datatables/extensions/buttons.min.js');
        Assets::add('bootstrap-modal');
        Assets::add('datetime');
        Assets::add('tags');
        Assets::add('js/plugins/editors/summernote/summernote.min.js');

        Assets::add('js/plugins/forms/selects/select2.min.js');
        
        Assets::add('js/plugins/forms/inputs/typeahead/handlebars.min.js');
        Assets::add('js/plugins/forms/inputs/alpaca/alpaca.min.js');
        Assets::add('js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js');
        Assets::add('js/plugins/forms/selects/bootstrap_multiselect.js');

        Assets::add('js/plugins/ui/ripple.min.js');

        Assets::add('js/plugins/forms/styling/switchery.min.js');
        Assets::add('js/plugins/forms/styling/switch.min.js');
        Assets::add('js/plugins/forms/styling/uniform.min.js');
        Assets::add('js/plugins/pickers/daterangepicker.js');
        Assets::add('js/plugins/pickers/datepicker/bootstrap-datepicker.js');
        Assets::add('js/plugins/notifications/jgrowl.min.js');
        Assets::add("js/plugins/jquery.cropit.js");

        Assets::add('js/plugins/loaders/blockui.min.js');

        Assets::add('js/plugins/visualization/vis/vis.min.css');     
        Assets::add('js/plugins/visualization/vis/vis.min.js');     

        if (Sentinel::check()) {
            view()->share('loggedInUser', Sentinel::getUser());
        }
    }
}
