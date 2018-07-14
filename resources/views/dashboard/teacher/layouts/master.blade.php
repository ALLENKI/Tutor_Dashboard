<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Aham - Tutor Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    --}}

    <?php

    $asset_source = env('ASSET_SOURCE', 'local');
    Assets::add('css/custom.css');
    $css_link = Assets::css();

    if ($asset_source == 'cdn') {
        $css_link = str_replace(
            '/assets/dashboard/min/',
            env('AWS_STATIC').'assets/dashboard/min/',
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

<body data-spy="scroll" data-target=".sidebar-detached" class="has-detached-right">
	
	@include('dashboard.teacher.layouts.navbar')

	<div class="page-container">

		<div class="page-content">
			@include('dashboard.teacher.layouts.sidebar')

			<div class="content-wrapper">


				<div class="content">

                @if (Session::has('flash_notification.message'))
                <div class="alert alert-{{ Session::get('flash_notification.level') }} text-center" role="alert" style="margin-bottom:10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <div class="container">
                        {{ Session::get('flash_notification.message') }}  
                    </div>
                </div>
                @endif

				@yield('content')

                <div class="footer text-muted">
                    &copy; 2016. <a href="{{ '/' }}" target="_blank">Aham Technologies Inc.</a>
                </div>

				</div>
                
			</div>
		</div>
	</div>

    <div id="ajax-modal" class="modal fade" tabindex="-1"></div>

    <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyBCc1hd71_vrtPpPX8uzwngNr2JBAz0Yb4'></script>


    <?php

    Assets::add('js/core/app.js');
    Assets::add('js/custom.js');
    $js_link = Assets::js();

    if ($asset_source == 'cdn') {
        $js_link = str_replace(
            '/assets/dashboard/min/',
            env('AWS_STATIC').'assets/dashboard/min/',
            $js_link
        );
    }

    ?>

    {!! $js_link !!}

    @section('scripts')

    @include('analytics')
    
    <script type="text/javascript">

       $(document)
         .ajaxStart(function () {

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

		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

        $(".rest").restfulizer();
    </script>
    @show

</body>
</html>
