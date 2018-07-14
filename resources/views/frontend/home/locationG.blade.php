@extends('frontend.layouts.master')

@section('content')

     <!-- #####Begin contents-->
      <section id="contents">
        
          <div class="page-head dark auto-height">
          <div class="rev_slider_wrapper fullwidthbanner-container">
            <!-- START REVOLUTION SLIDER 5.2.4 fullwidth mode-->
            <div id="rev_slider_5_1" class="rev_slider fullwidthabanner">
              <ul>
                <!-- SLIDE-->
                <li data-index="rs-13" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-delay="17220" data-rotate="0" data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                  <!-- MAIN IMAGE--><img src="assets/front/img/synergy.png" alt="" data-bgposition="center center" data-kenburns="on" data-duration="20000" data-ease="Linear.easeNone" data-scalestart="110" data-scaleend="100" data-rotatestart="0" data-rotateend="0" data-offsetstart="0 0" data-offsetend="0 0" data-bgparallax="15" data-no-retina="" class="rev-slidebg">
                  <!-- LAYERS-->
                  <!-- LAYER NR. 1-->
                  <h4 data-x="['left','left','center','center']" data-hoffset="['71','71','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['-39','-39','-65','-70']" data-width="none" data-height="none" data-whitespace="nowrap" data-transform_idle="o:1;" data-transform_in="y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;" data-transform_out="s:1.875;" data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" data-start="0" data-splitin="none" data-splitout="none" data-responsive_offset="on" class="head tp-caption tp-resizeme" style="color:#ffffff; text-transform:none; font-size:16px;">Aham, The Learning Hub</h4>
                  <!-- LAYER NR. 2-->
                  <h1 data-x="['left','left','center','center']" data-hoffset="['70','70','0','-92']" data-y="['middle','middle','middle','middle']" data-voffset="['0','0','-23','-37']" data-fontsize="['40','40','28','20']" data-lineheight="['50','50','36','36']" data-width="none" data-height="none" data-whitespace="nowrap" data-transform_idle="o:1;" data-transform_in="y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;" data-transform_out="s:1.875;" data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" data-start="0" data-splitin="none" data-splitout="none" data-responsive_offset="on" class="title tp-caption tp-resizeme" style="color:#ffffff;">Gachibowli, Hyderabad </h1>
                  
                  
                </li>
              </ul>
              <div style="visibility: hidden !important;" class="tp-bannertimer tp-bottom"></div>
            </div>
          </div>
          <!-- END REVOLUTION SLIDER-->
        </div>

        <section class="page-contents">
          <!-- #####Begin main area-->
          <section id="main-area">
            <section class="section bg-gray" style="padding:85px 0;">
              <div class="container">
                <div class="row">
                  <div class="col-md-4" sty>
                    <h3 class="with-shaded-label"> <span class="shaded-label">Address</span>Address</h3>
                    <div class="sp-blank-20"></div>
                    <p> <strong>Synergy Building, 3rd Floor, Plot No: 6-11, Survey No. 40, Above Andhra Bank, Khajaguda, Near Delhi Public School, Naga Hills Rd, Madhura Nagar Colony, Gachibowli, Hyderabad, Telangana-500008.<br>
                    Contact: 7330666701</strong>
                    </p>

                    <div id="playlist" style="margin-top: 60px;"></div>
                    
                  </div>
                  
                  <div class="col-md-8">

                    <div class="google-maps-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.8404894448167!2d78.37270991506615!3d17.419440988059435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb940c3cb677db%3A0x32f4b4c0ace8c4dc!2sAham+Learning+Hub+at+Gachibowli+(+Learning+Centre+)!5e0!3m2!1sen!2sin!4v1520236707322" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>
                    {{-- <img src="assets/front/img/loc1.png" style="width:100%;"> --}}
                  </div>
                </div>
              </div>
            </section>
       
          </section>
          <!-- #####End main area
          -->
          <div class="clearfix"></div>
        </section>
      </section>
      <!-- #####End contents-->

@stop

@section('styles')
@parent

  <style>
    .page-head.dark h1, .page-head.dark h2, .page-head.dark h3, .page-head.dark h4, .page-head.dark h5, .page-head.dark h6, .page-head.dark .info-wrapper {
          color: #f98507;
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
        videos: ['LBemzm4EQi8'],
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
      var tpj=jQuery;
      var revapi5;
      tpj(document).ready(function() {
          if(tpj("#rev_slider_5_1").revolution == undefined){
              revslider_showDoubleJqueryError("#rev_slider_5_1");
          }else{
              revapi5 = tpj("#rev_slider_5_1").show().revolution({
                  sliderType:"hero",
                  jsFileLocation: "/assets/front/revolution/js/",
                  sliderLayout:"fullwidth",
                  dottedOverlay:"none",
                  delay:12000,
                  navigation: {
                  },
                  responsiveLevels:[1240,1024,778,480],
                  visibilityLevels:[1240,1024,778,480],
                  gridwidth:[1170,1170,778,480],
                  gridheight:[700,600,400,500],
                  lazyType:"none",
                  parallax: {
                      type:"scroll",
                      origo:"slidercenter",
                      speed:400,
                      levels:[5,10,15,20,25,30,35,40,45,46,47,48,49,50,51,55],
                      type:"scroll",
                      disable_onmobile:"on"
                  },
                  shadow:0,
                  spinner:"spinner0",
                  autoHeight:"off",
                  disableProgressBar:"on",
                  hideThumbsOnMobile:"off",
                  hideSliderAtLimit:0,
                  hideCaptionAtLimit:0,
                  hideAllCaptionAtLilmit:0,
                  debugMode:false,
                  fallbacks: {
                      simplifyAll:"off",
                      disableFocusListener:false,
                  }
              });
          }
      });
    </script>


@stop