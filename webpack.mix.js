let mix = require("laravel-mix");
var UglifyJsPlugin = require('uglifyjs-webpack-plugin');
let plugins = [
        new UglifyJsPlugin({
          test: /\.js($|\?)/i,
          sourceMap: true,
          uglifyOptions: {
              compress: true
          }
        }),
      ];



/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

if (mix.inProduction()) {

    mix.webpackConfig({

      plugins: plugins,

      module: {
          rules: [

            {
              test: /\.(js|jsx)$/,
              exclude: /(node_modules|bower_components)/,
              use: {
                loader: 'babel-loader',
                options: {  // << add options with presets env
                  presets: ['env']
                }
              }
            }

          ]
      }
  });


}

mix
  .js(
    "resources/assets/js/learner-db/learnerdb.js",
    "public/dist/js/learnerdb-app.js"
  )
  .js(
    "resources/assets/js/tutor-db/tutordb.js",
    "public/dist/js/tutordb-app.js"
  )
  .js(
    "resources/assets/js/admin-db/admindb.js",
    "public/dist/js/admindb-app.js"
  )
  .js("resources/assets/js/hub-db/hubdb.js", "public/dist/js/hubdb-app.js")
  .combine(
    [
      "metronic/tools/bower_components/jquery/dist/jquery.js",
      "node_modules/popper.js/dist/umd/popper.js",
      "metronic/tools/bower_components/bootstrap/dist/js/bootstrap.js",
      "metronic/tools/bower_components/js-cookie/src/js.cookie.js",
      "metronic/tools/bower_components/bootstrap-select/dist/js/bootstrap-select.js",
      "metronic/tools/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js",
      "metronic/tools/bower_components/blockUI/jquery.blockUI.js",
      "metronic/theme/src/js/framework/base/*.js",
      "metronic/theme/src/js/framework/components/general/*.js",
      "node_modules/datatables.net/js/jquery.dataTables.js",
      "node_modules/datatables.net-buttons/js/dataTables.buttons.js",
      "node_modules/datatables.net-buttons/js/buttons.colVis.js"
    ],
    "public/dist/js/theme-build.js"
  )
  .combine(
    [
      "public/dist/js/theme-build.js",
      "metronic/theme/src/js/demo/demo5/base/layout.js",
      "metronic/theme/src/js/app/custom/layout-builder.js",
      "public/dist/js/admindb-app.js"
    ],
    "public/dist/js/admindb.js"
  )
  .combine(
    [
      "public/dist/js/theme-build.js",
      "metronic/tools/bower_components/moment/min/moment.min.js",
      "metronic/tools/bower_components/jquery-ui-draggable/dist/jquery-ui-draggable.min.js",
      "metronic/tools/bower_components/fullcalendar/dist/fullcalendar.min.js",
      "metronic/tools/bower_components/fullcalendar-scheduler/dist/scheduler.min.js",
      "metronic/theme/src/js/demo/demo5/base/layout.js",
      "metronic/theme/src/js/app/custom/layout-builder.js",
      "public/dist/js/hubdb-app.js"
    ],
    "public/dist/js/hubdb.js"
  )
  .combine(
    [
      "public/dist/js/theme-build.js",
      "metronic/theme/src/js/demo/default/base/layout.js",
      "metronic/theme/src/js/app/custom/layout-builder.js",
      "public/dist/js/learnerdb-app.js"
    ],
    "public/dist/js/learnerdb.js"
  )
  .combine(
    [
      "public/dist/js/theme-build.js",
      "metronic/theme/src/js/demo/default/base/layout.js",
      "metronic/theme/src/js/app/custom/layout-builder.js",
      "public/dist/js/tutordb-app.js"
    ],
    "public/dist/js/tutordb.js"
  )
  .sass("resources/assets/sass/app.scss", "public/dist/css/theme-build.css")
  .sass(
    "metronic/theme/src/sass/demo/default/style.scss",
    "public/dist/css/learner-theme.css"
  )
  .sass(
    "metronic/theme/src/sass/demo/demo5/style.scss",
    "public/dist/css/admin-theme.css"
  )
  .combine(
    ["public/dist/css/theme-build.css", "public/dist/css/learner-theme.css"],
    "public/dist/css/learnerdb.css"
  )
  .combine(
    [
      "public/dist/css/theme-build.css", 
      "public/dist/css/admin-theme.css"
    ],
    "public/dist/css/admindb.css"
  )
  .copyDirectory(
    "metronic/theme/src/vendors/line-awesome/fonts",
    "public/dist/fonts"
  )
  .copyDirectory(
    "metronic/theme/src/vendors/flaticon/fonts",
    "public/dist/fonts"
  )
  .copyDirectory(
    "metronic/theme/src/vendors/metronic/fonts",
    "public/dist/fonts"
  )
  .copyDirectory("metronic/theme/src/media/app", "public/dist/media")
  .copyDirectory("metronic/theme/src/media/demo/default", "public/dist/media")
  .copyDirectory("metronic/theme/src/media/demo/demo5", "public/dist/media")
  .options({
    processCssUrls: false
  })
  .version();
