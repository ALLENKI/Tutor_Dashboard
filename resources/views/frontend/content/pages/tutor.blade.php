@extends('frontend.layouts.master')

@section('content')

<div class="page-head dark hvh-70 center-it parallax-layer ov-grad1-alpha-60" data-img-src="/assets/front/img/backgrounds/011.jpg" data-parallax-mode="mode-title">
  <div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2" style="padding-top:80px;">
      <div id="playlist"></div>
    </div>
  </div>
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


.yesp .yesp-volume .yesp-volume-bar {
    float: left;
    position: relative;
    width: 55px;
    height: 10px;
    margin: 10px 12px 10px 9px;
    background: rgba(255,255,255,.5);
    cursor: pointer;
}

.icon-box.ib-v2 a:hover i:after, .icon-box.ib-v2 a:focus i:after {
    transform: scale(1.2, 1.2);
    opacity: 1;
}

.icon-box.ib-v2 > a > i:after {
    content: '';
    position: absolute;
    width: 75px;
    height: 75px;
    left: 0px;
    top: 0px;
    border-radius: 50%;
    border: 1px solid #FF3366;
    transition: all .25s ease;
    opacity: 0;
}

.icon-box.ib-v2 > a:hover i, .icon-box.ib-v2 > a:focus i {
    border: none;
    background-color: #f98507;
    color: #fff;
}

.icon-box.ib-v2 > a > i:after {
  border: 1px solid #f98507;
}

.icon-box.ib-v2>a>i {
    font-size: 30px;
    width: 75px;
    height: 75px;
    line-height: 75px;
    border: 1px solid #636363;
    display: block;
    margin: 0 auto;
    border-radius: 50%;
    margin-bottom: 20px;
    position: relative;
    transition: color .4s ease;
    box-sizing: border-box;
}
  </style>

@stop


@section('scripts')
@parent
  <script type="text/javascript">
    $(document).ready(function() {
      $("#playlist").youtube_video({
        playlist: false,
        channel: false,
        user: false,
        videos: ['DkAwXUSbX5Y'],
        max_results: 1,
        shuffle: false,
        pagination: true,
        continuous: true,
        first_video: 0,
        show_playlist: false,
        playlist_type: 'horizontal',
        show_channel_in_playlist:true,
        show_channel_in_title: false,
        width: '100%',
        show_annotations: false,
        now_playing_text: 'Now Playing',
        load_more_text: 'Load More',
        autoplay: false,
        force_hd: false,
        hide_youtube_logo: true,
        play_control: true,
        time_indicator: 'full',
        volume_control: true,
        share_control: true,
        fwd_bck_control: true,
        youtube_link_control: true,
        fullscreen_control: true,
        playlist_toggle_control:true,
        volume: 100,
        show_controls_on_load: true,
        show_controls_on_pause: true,
        show_controls_on_play: false,
        player_vars: {},
        colors: {},
         
        on_load: function() {},
        on_done_loading: function() {},
        on_state_change: function() {},
        on_seek: function() {},
        on_volume: function() {},
        on_time_update: function() {},   
      });
    });

  </script>
@stop
