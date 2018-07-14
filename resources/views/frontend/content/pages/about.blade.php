@extends('frontend.layouts.master')

@section('content')

<!-- #####Begin page head-->

<div class="page-head dark hvh-70 center-it parallax-layer ov-grad1-alpha-60" data-img-src="/assets/front/img/backgrounds/011.jpg" data-parallax-mode="mode-title">
  <div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2" style="padding-top:80px;">
      <div id="playlist"></div>
    </div>
  </div>
  </div>
</div>

<section class="section bg-gray section-nopadding" data-gunshoe style="padding:10px 0;">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3">

            <div class="icon-box ib-v2 bg-ablue"><a href="#our-students" data-scroll=""><i class="oli oli-students"></i>
                <h3 class="title" style="text-transform:none;">Our Students</h3></a>
            </div>

      </div>
      <div class="col-md-3">

            <div class="icon-box ib-v2 bg-ablue"><a href="#our-teachers" data-scroll=""><i class="oli oli-test_tube"></i>
                <h3 class="title" style="text-transform:none;">Our Teachers</h3></a>
            </div>

      </div>
      <div class="col-md-3">

            <div class="icon-box ib-v2 bg-ablue"><a href="#our-classroom" data-scroll=""><i class="oli oli-classroom"></i>
                <h3 class="title" style="text-transform:none;">Our Classroom</h3></a>
            </div>

      </div>
      <div class="col-md-3">

            <div class="icon-box ib-v2 bg-ablue"><a href="#our-approach" data-scroll=""><i class="oli oli-courses"></i>
                <h3 class="title" style="text-transform:none;">Our Approach</h3></a>
            </div>

      </div>
    </div>
  </div>
</section>

<section class="page-contents">
  <!-- #####Begin main area-->
  <section id="main-area">

      <section id="our-students" class="section bg-white slim-container p-bottom-0">
        <div class="container">
      <p><img src="https://res.cloudinary.com/ahamlearning/image/upload/v1467099520/our-students_ldxqop.jpg" style="" class="img-responsive"></p>
        </div>
      </section>

      <section id="our-teachers" class="section bg-white slim-container p-bottom-0">
        <div class="container">
      <p id="our-teachers"><img src="https://res.cloudinary.com/ahamlearning/image/upload/v1467099523/our-teachers_k568z2.png" style="" class="img-responsive"></p>
        </div>
      </section>

      <section id="our-classroom" class="section bg-white slim-container p-bottom-0">
        <div class="container">
      <p id="our-classroom"><img src="https://res.cloudinary.com/ahamlearning/image/upload/v1472972178/classrom_qmokty.png" style="" class="img-responsive"></p>
        </div>
      </section>


      <section id="our-approach" class="section bg-white slim-container p-bottom-0">
        <div class="container">

      <p id="our-approach"><img src="https://res.cloudinary.com/ahamlearning/image/upload/v1467198305/imgpsh_fullsize_1_ndc8zf.jpg" style=" padding-bottom: 135px;" class="img-responsive"></p>
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
        videos: ['1DwTx4z75IQ'],
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

  <script type="text/javascript">
      (function($) {

        smoothScroll.init();

      })(jQuery);
    </script>
@stop
