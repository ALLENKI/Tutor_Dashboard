<!DOCTYPE html>
<html lang="en" dir="ltr" itemscope itemtype="http://schema.org/WebPage">
<head style="background:grey;">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_token" content="{{ csrf_token() }}" />

    <meta name="Content-Type-Script" content="text/javascript">
    <meta name="Content-Type-Style" content="text/css">

    <link rel="icon" type="image/png" href="/favicon.png">
    
    {{--
    <link href="https://fonts.googleapis.com/css?family=Play:400,700&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">


    <link href="https://fonts.googleapis.com/css?family=Sintony:400,700&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300italic,400,400italic,700,700italic&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">
    --}}

    <!-- Css -->
    <?php

    $asset_source = env('ASSET_SOURCE', 'local');
    Assets::add('css/aham.css');
    $css_link = Assets::css();

    if ($asset_source == 'cdn') {
        $css_link = str_replace(
            '/assets/front/min/',
            env('AWS_STATIC').'assets/front/min/',
            $css_link
        );
    }

    ?>

    {!! $css_link !!}
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    <title>Aham - The Learning Hub</title>
    <meta name="keywords" content="The learning hub etc.," />
    <meta name="description" content="Concept based learning.,">

    @section('styles')

    @show

    <script type="text/javascript">
        var BASE = '<?php echo URL::to('/').'/'; ?>';
    </script>
</head>

<body class="{{ $bodyClass }}">

    <div id="wrapper" class="regular-layout">
        
    @include('frontend.layouts.header')

    <section id="contents"> 

    @if(Session::has('flash_notification.message') && 0)
    <div class="alert alert-{{ Session::get('flash_notification.level') }} text-center" role="alert" style="margin-bottom:0px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <div class="container">
            {{ Session::get('flash_notification.message') }}  
        </div>
    </div>
    @endif

    @yield('content')

    </section>

    @include('frontend.layouts.footer')

    </div>
    
    <?php

    Assets::add('js/aham.js');
    $js_link = Assets::js();

    if ($asset_source == 'cdn') {
        $js_link = str_replace(
            '/assets/front/min/',
            env('AWS_STATIC').'assets/front/min/',
            $js_link
        );
    }

    ?>

    {!! $js_link !!}

    @section('scripts')

    @include('analytics')

    <script type="text/javascript">

        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
        d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
        $.src="//v2.zopim.com/?4BPitns93Qsf280U7tVWAMBhxMfCkacZ";z.t=+new Date;$.
        type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");

        // $(".rest").restfulizer();

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                }
            });
        });

        @if(Session::has('flash_notification.message'))

        $(function(){

                $.toast({
                    text: '{{ Session::get("flash_notification.message") }}',
                    position: {
                        right: 80,
                        top: 120
                    },
                    stack: false,
                    hideAfter : false,
                    allowToastClose : true,
                    icon: "{{ Session::get('flash_notification.level') == 'danger' ? 'error' :  Session::get('flash_notification.level') }}"
                });

        });

        @endif

    </script>

    @show

</body>
</html>