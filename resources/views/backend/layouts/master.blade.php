<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aham Admin - The Learning Hub</title>


    <link href="https://fonts.googleapis.com/css?family=Open+Sans::400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Play::400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Sintony::400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Merriweather::400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <?php

    $asset_source = env('ASSET_SOURCE', 'local');
    Assets::add('css/custom.css');
    $css_link = Assets::css();

    if ($asset_source == 'cdn') {
        $css_link = str_replace(
            '/assets/back/min/',
            env('AWS_STATIC').'assets/back/min/',
            $css_link
        );
    }

    ?>

    {!! $css_link !!}


    @section('styles')

    @show

    <script type="text/javascript">
        var BASE = '<?php echo URL::to('/').'/'; ?>';
    </script>
    <!-- /global stylesheets -->


</head>

<body>

    @include('backend.layouts.header')

    @yield('page_header')

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

            @if (Session::has('flash_notification.message'))
            <div class="alert alert-{{ Session::get('flash_notification.level') }} text-center" role="alert" style="margin-bottom:0px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <div class="container">
                    {{ Session::get('flash_notification.message') }}  
                </div>
            </div>
            @endif

            @yield('content')

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        @include('backend.layouts.footer')


    </div>
    <!-- /page container -->

    <div id="ajax-modal" class="modal fade" tabindex="-1"></div>

    <?php

    Assets::add('js/core/app.js');
    Assets::add('js/custom.js');
    $js_link = Assets::js();

    if ($asset_source == 'cdn') {
        $js_link = str_replace(
            '/assets/back/min/',
            env('AWS_STATIC').'assets/back/min/',
            $js_link
        );
    }

    ?>

    
    <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyD6yd1GX6fFm2074DoQ9y68ZF8w4TQqk-k'></script>

    {!! $js_link !!}

    @section('scripts')
    <script type="text/javascript">

       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    

       $(document)
         .ajaxStart(function () {

                console.log('Ajax Start');

                $.blockUI({ 
                    message: '<i class="icon-spinner4 spinner"></i>',
                    overlayCSS: {
                        backgroundColor: '#1b2024',
                        opacity: 0.8,
                        zIndex: 1200,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        color: '#fff',
                        padding: 0,
                        zIndex: 1201,
                        backgroundColor: 'transparent'
                    }
                });

         })
        .ajaxStop(function () {
                
            $.unblockUI();

        });


        $(".rest").restfulizer();
    </script>


    @show

</body>
</html>
