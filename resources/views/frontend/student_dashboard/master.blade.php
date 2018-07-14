@extends('frontend.layouts.master')


@section('content')


<section class="page-contents">
<section id="main-area">
<section class="section p-top-20">

<div class="container">
  <div class="row">

	<div class="col-xs-12 col-sm-3 text-center">

		<div class="row">

			<div class="col-xs-3 col-sm-12">
				
				<img src="{{ cloudinary_url($student->user->present()->picture) }}" style="border: 1px #E4E4E4 solid;border-radius: 6px;" class="img-responsive">

			</div>

			<div class="col-xs-3 col-sm-12 m-top-20">
				<h1>{{ $student->user->name }}</h1>
				<h5>{{ $student->user->username }}</h5>

				<ul class="list-unstyled">
					@if($student->user->city)
					<li>{{ $student->user->city->name }}</li>
					@endif

					<li>Joined {{ $student->user->created_at->format('jS M Y') }}</li>
				</ul>
			</div>
			
		</div>

	</div>

	<div class="col-xs-12 col-sm-9 m-top-xs-20">

		<div>
		  <ul class="nav nav-tabs" role="tablist">

		  <?php $current = Request::segment(3); ?>

		    <li class="{{ $current == '' ? 'active' : '' }}"><a href="{{ route('student::profile', $student->user->username) }}">Overview</a></li>

		    <li class="{{ $current == 'assessment' ? 'active' : '' }}"><a href="{{ route('student::assessment', $student->user->username) }}">Assessment</a></li>

		    @if(Sentinel::check() && $user->student && $user->student->id == $student->id)
		    <li><a href="#messages">Completed Classes</a></li>
		    @endif

		  </ul>
		</div>

		<div class="p-top-20"></div>

		@yield('subcontent')


	</div>

  </div>
</div>

</section>
</section>
</section>

@stop