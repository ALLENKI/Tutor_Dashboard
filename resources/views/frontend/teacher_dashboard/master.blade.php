@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">
<section id="main-area">
<section class="section p-top-50">

<div class="container">
  <div class="row">
    <div class="col-md-12">
    
    <div class="row">

      <div class="col-sm-2 hidden-xs">
        <img src="{{ cloudinary_url($teacher->user->present()->picture) }}" style="border: 1px #E4E4E4 solid;" class="img-responsive img-circle">
      </div>

      <div class="col-sm-10 col-lg-7">
        <h1>{{ $teacher->user->name }}</h1>
        <blockquote>{{ $teacher->about_me }}</blockquote>
        <p>
          {{ $teacher->description }}
        </p>
  
        <button class="btn btn-small btn-circle">Follow</button>

        <button class="btn btn-small btn-circle">Code: {{ $teacher->code }}</button>

      </div>

      <div class="col-lg-3 hidden-xs hidden-sm hidden-md">
          
          @if($courses->count())
          <div data-cols="1" data-margin="30" data-nav="true" data-loop="true" class="owl-carousel items owwwlab-theme nav-top-center" style="padding:40px!important; padding-bottom:10px !important;">

            @foreach($courses as $course)

            <?php
              $image_url = cloudinary_url($course->present()->picture, array("height"=>500, "width"=>333, "crop"=>"thumb"));
            ?>

            <!-- #####Begin  book element-->
            <a href="{{ url('classes-in-'.$course->slug) }}" class="book-el mini shadow set-bg skin-theme" style="margin: 0 auto;">
            <img src="{{ $image_url }}" class="set-me">
              <div class="contents">
                <div class="title-wrapper">
                  <h6 class="sub-title">{{ $course->parent->name }}</h6>
                  <h2 class="title">{{ $course->name }}</h2>
                </div>
              </div>
            </a>
            <!-- #####End  book element-->
            @endforeach

          </div>
          
          <h3 class="text-center">Topics I Teach</h3>
          @endif

      </div>

    </div>

    <hr style="border-top: 2px solid #e2e2e2;">

    <div class="row">

      <div class="col-md-3">

      <ul class="icon-list profile-navigation">
        <?php $current = Request::segment(3); ?>

        <li class="{{ $current == '' ? 'active' : '' }}"><i class="theme-color oli oli-calendar"></i> <a href="{{ route('tutor::profile', $teacher->user->username) }}">Upcoming Classes</a></li>


        <li class="{{ $current == 'certification' ? 'active' : '' }}"><i class="theme-color oli oli-linegraph"></i> <a href="{{ route('tutor::certification', $teacher->user->username) }}">Certified To Teach</a></li>

        @if(Sentinel::check() && $user->teacher && $user->teacher->id == $teacher->id)
        <li class="{{ $current == 'invitations' ? 'active' : '' }}"><i class="theme-color oli oli-diamond"></i> <a href="{{ route('tutor::invitations.index', $teacher->user->username) }}">My Invitations</a></li>
        @endif

      </ul>

      </div>

      <div class="col-md-9">
        @yield('subcontent')
      </div>

    </div>

    </div>


    <div class="col-md-3">
      
      <h4></h4>

    </div>

  </div>
</div>

</section>
</section>
</section>

@stop

