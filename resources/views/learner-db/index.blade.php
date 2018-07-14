<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>
			Learner | Dashboard
		</title>

		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<!--end::Web font -->
		<script>
			window.domainUrl = "{{ url('/') }}";
			window.baseUrl = "{{ url('ahamapi') }}";
			window.authToken = "{{ $token }}";
			
			@if(session('aham:impersonator'))
				window.Impersonation = true;
			@else
				window.Impersonation = false;
			@endif
		</script>

        <link rel="stylesheet" href="{{ mix('dist/css/learnerdb.css') }}">
		<link rel="shortcut icon" href="assets/demo/default/media/img/logo/favicon.ico" />
	</head>

	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<div id="app">
			<layout></layout>
		</div>

		<script type="text/javascript" src='https://maps.google.com/maps/api/js?libraries=places&amp;key=AIzaSyCcBZB3yPhBMIHNQJNZDvz7nf9Pfgv5-3E'></script>

        <script type="text/javascript" src="{{ mix('dist/js/learnerdb.js') }}"></script>

	</body>

</html>