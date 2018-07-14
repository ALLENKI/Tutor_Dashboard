
<!-- main/homepages/home-2.html -->
<!DOCTYPE html>
<html lang="en" dir="ltr" itemscope itemtype="http://schema.org/WebPage">
<head style="background:grey;">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="piTVwFsIOzV8V7RmNAltl2RtPjcAxnZSWCc8dSoY">
    <meta name="Content-Type-Script" content="text/javascript">
    <meta name="Content-Type-Style" content="text/css">

    <link href="https://fonts.googleapis.com/css?family=Play:400,700&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">


    <link href="https://fonts.googleapis.com/css?family=Sintony:400,700&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300italic,400,400italic,700,700italic&amp;subset=latin,greek,cyrillic" rel="stylesheet" type="text/css">

    <!-- Css -->
    
    <link href="/assets/front/css/vendors/vendors.css" type="text/css" rel="stylesheet" />
<link href="/assets/front/css/vendors/vendors-overwrites.css" type="text/css" rel="stylesheet" />
<link href="/assets/front/css/styles.css" type="text/css" rel="stylesheet" />
<link href="/assets/front/css/fonts.css" type="text/css" rel="stylesheet" />
<link href="/assets/front/revolution/css/revolution-all.css" type="text/css" rel="stylesheet" />
<link href="/assets/front/css/aham.css" type="text/css" rel="stylesheet" />

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    <title>Aham - The Learning Hub</title>
    <meta name="keywords" content="The learning hub etc.," />
    <meta name="description" content="Concept based learning.,">
    <meta http-equiv="refresh" content="5">


      <style>

        a.btn:hover, .btn:focus {
          background-color: #F98506;
          color: #fff;
      }

      </style>

    <script type="text/javascript">
        var BASE = '<?php echo URL::to('/').'/'; ?>';
    </script>
</head>

<body class="fullwidth sticky-header">

    <div id="wrapper" class="regular-layout">
        @include('frontend.layouts.header')

        <section id="contents"> 

        @yield('content')

        </section>

        @include('frontend.layouts.footer')
    </div>

<script src="/assets/front/js/vendors/jquery.min.js" type="text/javascript"></script>
<script src="/assets/front/js/vendors/vendors.js" type="text/javascript"></script>
<script src="/assets/front/js/vendors/jquery.restfulizer.js" type="text/javascript"></script>
<script src="/assets/front/js/vendors/jquery.fitvids.js" type="text/javascript"></script>
<script src="/assets/front/js/custom.js" type="text/javascript"></script>
<script src="/assets/front/js/aham.js" type="text/javascript"></script>

</body>