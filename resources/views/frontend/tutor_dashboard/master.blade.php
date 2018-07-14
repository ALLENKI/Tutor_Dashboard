@extends('frontend.layouts.master')


@section('content')


<section class="page-contents">
<section id="main-area">
<section class="section p-top-20">

<div class="container">
  <div class="row">

	<div class="col-xs-12 col-md-3">

		<div class="row">

			<div class="col-xs-4 col-xs-4 col-md-12">
				
				<img src="{{ cloudinary_url($teacher->user->present()->picture) }}" style="border: 1px #E4E4E4 solid;border-radius: 6px;" class="img-responsive">
				
				@if(Sentinel::check() && ($user->id != $teacher->user->id))
				<a href="" class="btn-success btn-small btn-block m-top-20">Follow</a>
				@endif

			</div>


			<div class="col-xs-8 col-md-12 m-top-20 m-top-xs-0 m-top-sm-0">

				<div class="icon-box ib-v3 ib-boxed ib-dark set-bg" style="background-image: url('https://res.cloudinary.com/ahamlearning/image/upload/c_scale,w_450/c_scale,l_black_opacity_90_kvowki,w_450/v1466164427/aham_icon_m6ljr5.png');">
				<h1>{{ $teacher->user->name }}</h1>
				<p class="m-top-20">{{ $teacher->about_me }}</p>

				<hr>

				<ul class="list-unstyled">
					@if($teacher->user->city)
					<li><i class="oli oli-map-pin"></i> {{ $teacher->user->city->name }}</li>
					@endif

					<li><i class="oli oli-time"></i> Joined {{ $teacher->user->created_at->format('jS M Y') }}</li>
				</ul>
				</div>
			</div>
			
		</div>

	</div>

	<div class="col-xs-12 col-md-9 m-top-xs-20 m-top-sm-20">

		<div>
		  <ul class="nav nav-tabs" role="tablist">

		  <?php $current = Request::segment(3); ?>

		    <li class="{{ $current == '' ? 'active' : '' }}"><a href="{{ route('tutor::profile', $teacher->user->username) }}">Overview</a></li>

		    <li class="{{ $current == 'certification' ? 'active' : '' }}"><a href="{{ route('tutor::certification', $teacher->user->username) }}">Certification</a></li>

		    @if(Sentinel::check() && $user->teacher && $user->teacher->id == $teacher->id)
		    <li><a href="#messages">Completed Classes</a></li>
		    <li class="{{ $current == 'invitations' ? 'active' : '' }}"><a href="{{ route('tutor::invitations.index', $teacher->user->username) }}">Invitations</a></li>
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