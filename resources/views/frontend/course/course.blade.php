@extends('frontend.layouts.master')

@section('content')

<div data-img-src="http://res.cloudinary.com/ahamlearning/image/upload/v1466613647/iStock_000041836734_Large_r62mxk.jpg" class="page-head parallax-layer ov-light-alpha-50">
  <div class="container">
    <!-- #####Begin course introduction-->
    <div class="course-intro">
      <div class="row sync-cols-height" style="max-height:585px;">
        
        <div class="col-md-4 col-sm-12 col-xs-12" style="padding-top: 65px;">

          <div class="course-info">

            <span class="category">
            	<a href="{{ url('classes-in-'.$course->parent->parent->slug) }}">{{ $course->parent->parent->name }}</a>
            	>
            	<a href="{{ url('classes-in-'.$course->parent->slug) }}">{{ $course->parent->name }}</a>
            </span>

            <h2 class="title">{{ $course->name }}</h2>
            <ul class="course-meta-list">
              <li><i class="oli oli-training"></i><span style="color: #8d8d8d;">No of Units</span><span class="pull-right" style="color: #8d8d8d;">{{ $course->units->count() }}</span></li>
              <li><i class="oli oli-clock"></i><span style="color: #8d8d8d;">Hours of Instruction</span><span class="pull-right" style="color: #8d8d8d;">{{ $course->units->count()*2 }}</span></li>
              <li><i class="oli oli-global"></i><span style="color: #8d8d8d;">Language</span><span class="pull-right" style="color: #8d8d8d;">English</span></li>
            </ul>

            <div class="text-center">
              @if(Sentinel::check())

              @if($user->student && $user->student->active)
                  <a href="{{ route('student::courses.show', $course->slug) }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; margin-top: 70px;">Go To Dashboard</a>

              @elseif($user->teacher && $user->teacher->active)
                  <a href="{{ route('teacher::courses.show', $course->slug) }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; margin-top: 70px;">Go To Dashboard</a>
              @else

              @endif

              @else
                <a href="{{ route('auth::register-as-a-student') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; margin-top: 70px;">Join Aham</a>
              @endif
            </div>

          </div>

        </div>

        <div class="col-md-8 hidden-sm hidden-xs course-picture">

              {!! cl_image_tag($course->present()->Picture,array('class' => 'img-responsive')) !!}

        </div>
      </div>
    </div>
    <!-- #####End course introduction-->
  </div>
</div>

<section class="page-contents">
<section id="main-area">
<section>
	<div class="ol-tab course-single-tab">

	<div class="body-wrapper p-top-40">
	<div class="container">
	<div class="row">
		<div class="col-md-12">
      @include('frontend.course._description')
		</div>
	</div>
	</div>
	</div>

	</div>
</section>
</section>
</section>

@stop


@section('styles')
@parent

<style>
	
	.course-intro .course-shop-data .course-purchase-btn {

		padding-left: 20px;
		background-color: #006696;
	}

  .course-meta-list li {
    font-size: 14px;
      border-bottom: 1px solid #ebebeb;
}

.editor >  *, font,.editor span{
  font-family: 'Sintony',sans-serif !important;
  font-size: 14px !important;
  color: black;
}

.course-picture .img-responsive{
  height: 100%;
}
/*
  .col-md-11 .editor li,span{
    margin-top: 5.76pt;
    margin-bottom: 0pt;
    margin-left: 0in;
    text-indent: 0in;
    direction: ltr;
    unicode-bidi: embed;
    word-break: normal;
    font-size: 14px !important;
    font-family: Tahoma;
    color: black;
  }

  .editor p {
    font-size: 14px;
    font-family: Tahoma;
    color: black;
    margin-top: 5.76pt;
    margin-bottom: 0pt;
    margin-left: 0in;
    text-indent: 0in;
    direction: ltr;
    unicode-bidi: embed;
    word-break: normal;
  }

  .col-md-11 .editor span {
    font-size: 14px;
    font-family: Tahoma;
    color: black;
    margin-top: 5.76pt;
    margin-bottom: 0pt;
    margin-left: 0in;
    text-indent: 0in;
    direction: ltr;
    unicode-bidi: embed;
    word-break: normal;
  }

   .editor font, span{
    font-size: 14px;
    font-family: Tahoma;
    color: black;
    margin-top: 5.76pt;
    margin-bottom: 0pt;
    margin-left: 0in;
    text-indent: 0in;
    direction: ltr;
    unicode-bidi: embed;
    word-break: normal;
  }

  .editor li{
    font-size: 14px;
    font-family: Tahoma;
    color: black;
  }*/

</style>
@stop

@section('scripts')
@parent
<script>
$(document).ready(function(){
// Target your .container, .wrapper, .post, etc.
	$(".h_iframe").fitVids();
});
</script>
@stop