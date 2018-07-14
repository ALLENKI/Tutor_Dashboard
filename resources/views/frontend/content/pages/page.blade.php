@extends('frontend.layouts.master')

@section('content')

<!-- #####Begin page head-->

<div class="page-head dark hvh-40 center-it parallax-layer ov-grad1-alpha-60" data-img-src="http://res.cloudinary.com/ahamlearning/image/upload/c_scale,w_1688/v1466667987/Teacher-Guide_w0qqmm.jpg" data-parallax-mode="mode-title">
  <div class="container">
  @if($page->slug == 'about-aham')
  
    <div class="tb-vcenter-wrapper">
      <div style="padding-top:150px;">
        <div class="row">
		  	<div class="col-xs-12 col-sm-6 col-sm-push-3 col-lg-5 col-lg-push-3">
		  		<div class="video-container"><iframe width="560" height="350" src="https://www.youtube.com/embed/1DwTx4z75IQ" frameborder="0" allowfullscreen></iframe></div>
		  	</div>
		  </div>
      </div>
    </div>

    @else

     <div class="tb-vcenter-wrapper">
      <div class="title-wrapper vcenter parallax-layer" data-parallax-mode="mode-header-content">
        <h1 class="title">{{ $page->name }}</h1>
      </div>
    </div>

    @endif


  </div>

</div>

@if($page->slug == 'join-as-a-student')

   <section class="section pad-20" style="background-color: #d7d7da;">
    <div class="container">
      <div class="tb-vcenter-wrapper">
        <div class="title-wrapper vcenter" style="text-align: center;">

        @if(Sentinel::check())

          @if($user->student && $user->student->active)

          <a href="{{ route('student::home') }}" class="btn btn-medium btn-wide btn-skin-blue btn-circle"> <span>Go to Dashboard</span></a>

          @elseif($user->teacher && $user->teacher->active)
            <a href="{{ route('teacher::home') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff;">Go To Dashboard</a>

          @else

          @endif

          @else

          <a href="{{ route('auth::register-as-a-student') }}" class="btn btn-medium btn-wide btn-skin-blue btn-circle"> <span>Join Aham as Learner</span></a>

        @endif

        </div>
        
      </div>
    </div>
  </section>

@else

   <section class="section pad-20" style="background-color: #d7d7da;">
    <div class="container">
      <div class="tb-vcenter-wrapper">
        <div class="title-wrapper vcenter" style="text-align: center;">

        @if(Sentinel::check())

          @if($user->teacher && $user->teacher->active)

          <a href="{{ route('teacher::home') }}" class="btn btn-medium btn-wide btn-skin-blue btn-circle"> <span>Go to Dashboard</span></a>

          @elseif($user->student && $user->student->active)
            <a href="{{ route('student::home') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff;">Go To Dashboard</a>

          @else

          @endif

          @else

          <a href="{{ route('auth::register-as-a-teacher') }}" class="btn btn-medium btn-wide btn-skin-blue btn-circle"> <span>Join Aham as Tutor</span></a>


        @endif


        </div>
        
      </div>
    </div>
  </section>

@endif
<!-- #####End page head
-->

<section class="page-contents">
<!-- #####Begin main area-->
<section id="main-area">
<section class="section slim-container" style="padding: 70px 0;">
<div class="container">
<div class="single-post large-typo">

<div class="post-body">
{!! $page->content !!}
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
  
  .col-sm-push-4 {
      left: 29.333333%;
  }

  .btn-skin-dark, .btn-skin-dark > a:hover {
    background-color: #006696;
    border-color: rgb(0, 102, 150);
    color: #fff;
}

  </style>

@stop
