@extends('frontend.layouts.master')

@section('content')

<div data-img-src="{{ cdn('assets/front/img/backgrounds/19.jpg') }}" class="page-head parallax-layer ov-light-alpha-50">
  <div class="container">
    <!-- #####Begin course introduction-->
    <div class="course-intro">
      <div class="row sync-cols-height">
        <div class="col-md-4 col-sm-12 col-xs-12">
          <div class="course-info" style="padding-bottom: 0px;">

            <span class="category">
            	<a href="{{ url('classes-in-'.$course->parent->parent->slug) }}">{{ $course->parent->parent->name }}</a>
            	>
            	<a href="{{ url('classes-in-'.$course->parent->slug) }}">{{ $course->parent->name }}</a>
            </span>

            <h2 class="title">{{ $course->name }}</h2>
            <ul class="course-meta-list">
              <li><i class="oli oli-training"></i><span>Lectures</span><span class="pull-right">25</span></li>
              <li><i class="oli oli-training"></i><span>Course</span><span class="pull-right"><a href="{{ url('classes-in-'.$course->slug) }}">{{ $course->name }}</a></span></li>
              <li><i class="oli oli-borrow_book"></i><span>Quizes</span><span class="pull-right">5</span></li>
              <li><i class="oli oli-clock"></i><span>Duration</span><span class="pull-right">01:22:30</span></li>
              <li><i class="oli oli-global"></i><span>Language</span><span class="pull-right">English</span></li>
              <li><i class="oli oli-conference_call"></i><span>Reviews (5)</span><span class="pull-right">
                  <div class="ol-review-rates"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"> </i></div></span></li>
            </ul>
          </div>
        </div>
        <div class="col-md-8 hidden-sm hidden-xs">

			<div class="wrapper">
			    <div class="h_iframe">
	                    <iframe src="{{ $course->google_slide }}" frameborder="0" width="100%" height="400" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
			    </div>
			</div>

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

    <div class="head-wrapper">
      <div class="container">
        <!-- #####Begin tab navigation-->
        <ul class="tab-navigation">
          <li class="active"><a href="#">Description</a></li>
          <li><a href="#">Learners</a></li>
          @if($ahamClass->status == 'completed')
          <li><a href="#">Feedback</a></li>
          @endif
        </ul>
        <!-- #####End tab navigation-->
      </div>
    </div>

	<div class="body-wrapper">
	<div class="container">
	<div class="row">
		<div class="col-md-9">
		<div class="tab-content">

		<div class="tab-pane active">
			<section>
				
				@include('frontend.course._description')

				<div class="m-top-30"></div>

				<h3>Timings</h3>

	        	@foreach($ahamClass->topic->units as $index => $unit)
		        <br>
		        <div class="date">
		          Unit {{ $index+1 }} : <i class="oli oli-clock"></i> {{ schedule($unit->id,$ahamClass->id) }}
		        </div>
		        @endforeach

		        <div class="m-top-30"></div>
				
				<section>
	              <h3>About Tutor</h3>
					<?php
					  $classCourse = $ahamClass->topic;
					  $classTeacher = $ahamClass->teacher;
					  $image_url = cloudinary_url($classTeacher->user->present()->picture, array( "gravity"=>"face:center", "crop"=>"crop"));
					?>
	              <div class="author-bio">
	                <div class="avatar avatar-md"><img src="{{ $image_url }}" alt="Owl Image" class="img-circle"></div>
	                <div class="author-bio-content">
	                  <h4>
	                  <a href="{{ route('tutor::profile',$ahamClass->teacher->user->username) }}">
	                  {{ $ahamClass->teacher->user->name }}
	                  </a>
	                  </h4>

	                  <p>We are a small, quick and talented design agency with big, vast and creative ideas. We don't work for you, we work with you.</p>
	                </div>
	              </div>
	              <!-- #####End author biography-->
	              <div class="sp-line-120"></div>
	            </section>

			</section>

		</div>

		<div class="tab-pane">

			<section>
				
			<h2>Learners</h2>

			<div class="row multi-columns-row col-margin-bottom-40">
				@foreach($ahamClass->enrollments as $enrollment)
				<?php
				  $student = $enrollment->student;
				  $image_url = cloudinary_url($student->user->present()->picture, array( "gravity"=>"face:center", "crop"=>"crop"));
				?>
				<div class="col-xs-6">
                  <!-- #####Begin person card-->
                  <div class="vc-card boxed hoverable-links mini-card"><a href="#" class="set-bg" style="background-image: url({{ $image_url }}); border: 1px solid #EAE7E7;"> <img src="{{ $image_url }}" alt="people" class="set-me" style="display: none;"></a>
                    <div class="vc-card-wrapper"><a href="#" class="title">Dr. Quinlan Granville</a>
                      <div class="subtitle">Associate Dean</div>
                    </div>

                    <div class="links">
                    	<a href="#"><i class="oli oli-facebook"></i><span>Send Email</span></a>
                    </div>
                  </div>
                  <!-- #####End person card-->
                </div>

                @endforeach
			</div>

			</section>

		</div>

		<div class="tab-pane">
			@include('frontend.class._feedback')
		</div>

		</div>
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