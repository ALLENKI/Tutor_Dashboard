@extends('frontend.layouts.master')

@section('content')


<section data-img-src="https://res.cloudinary.com/ahamlearning/image/upload/v1465800843/background_1_sydkco.jpg" data-parallax-mode="mode-5" class="section ov-grad9-alpha-90 bg-gray parallax-layer ol-particles dark">
  <div class="container">
    <div class="row call-out tb-vcenter-wrapper">
      <div class="col-md-7 vcenter text-left">
		<h1 class="title" style="color:#fff; font-size:40px;">Enroll Now and Start Learning!</h1>
      </div>
    </div>
  </div>
</section>


<section class="page-contents">	
	<section class="section courses pad-60">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					@include('frontend.search._sidebar')
				</div>
				<div class="col-md-9">
					<div id="main-area">
					@include('frontend.search._combined')
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

@stop


@section('styles')
@parent
      <style>

        .section .courses {
          padding: 80px 0;
      	}

      	.page-head h1.title {
      		text-transform: none;
		}

		.parallax-bg-elem{
			opacity: 1 !important;
		}

      </style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
	$(function(){


	});
</script>

@stop