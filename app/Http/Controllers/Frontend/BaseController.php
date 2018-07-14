<?php

namespace Aham\Http\Controllers\Frontend;

use Aham\Http\Controllers\Controller;
use Assets;
use Sentinel;
use Aham\Models\SQL\Setting;

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
            'css_dir' => env('ASSETS_CSS_DIR', '/theme'),
            'js_dir' => env('ASSETS_JS_DIR', '/theme'),
            'pipeline' => env('ASSETS_PIPELINE', false),
        ];

        Assets::config($assets);

        Assets::reset();

        Assets::add('css/vendors/vendors.css');
        Assets::add('css/vendors/vendors-overwrites.css');
        Assets::add('css/styles.css');
        Assets::add('css/fonts.css');
        Assets::add('js/vendors/jquery.min.js');
        Assets::add('js/vendors/vendors.js');
        // Assets::add('js/vendors/jquery.restfulizer.js');
        // Assets::add('js/vendors/jquery.fitvids.js');
        // Assets::add('js/vendors/jquery.cropit.js');

        Assets::add('js/vendors/inputs/typeahead/handlebars.min.js');
        Assets::add('js/vendors/inputs/typeahead/typeahead.bundle.min.js');
        Assets::add('js/vendors/bootstrap_multiselect.js');

        Assets::add('js/vendors/jquery-toast-plugin/jquery.toast.min.css');
        Assets::add('js/vendors/jquery-toast-plugin/jquery.toast.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.video.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.slideanims.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.actions.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.layeranimation.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.kenburn.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.navigation.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.migration.min.js');
        Assets::add('revolution/js/extensions/revolution.extension.parallax.min.js');

        Assets::add('js/custom.js');

        if (Sentinel::check()) {
            view()->share('user', Sentinel::getUser());
        }

        view()->share('bodyClass', 'fullwidth sticky-header');
        view()->share('headerClass', '');

        $skins = ['skin-blue', 'skin-purple', 'skin-yellow', 'skin-green'];

        view()->share('skins', $skins);

        $homepage_scroll = Setting::where('key', 'homepage_scrolling')->first();
        $homepage_scroll_message = Setting::where('key', 'homepage_scrolling_message')->first();

        view()->share('homepage_scroll', $homepage_scroll);
        view()->share('homepage_scroll_message', $homepage_scroll_message);
    }
}
