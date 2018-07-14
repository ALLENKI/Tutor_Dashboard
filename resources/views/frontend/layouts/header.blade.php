<header id="header" class="{{ $headerClass }}"> 


<!-- #####Begin header main area-->
<div class="head-main">
  <div class="container">
    <!-- #####Begin logo wrapper-->

    <div class="logo-wrapper">
      <a href="{{ url('/') }}"><img src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,h_50,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png" alt="Aham Learning Hub" class="logo-light ol-retina" style="max-height:50px;margin-top: 10px;"></a>
    </div>
    <!-- #####End logo wrapper-->
    <!-- #####Begin primary menu-->
    <ul id="primary-menu">

      {{-- <li class="menu-item-has-children"><a href="" title="Workshops"><span><strong>Techpreneurship</strong></span></a>

      <ul class="sub-menu" style="z-index: 99999;">

        <li style="text-transform:uppercase;">
        <a href="https://ahamlearning.com/series/art-for-summer-2017" target=_blank style="text-transform:none;">Art
        </a>
        </li>


        <li style="text-transform:uppercase;">
        <a href="https://ahamlearning.com/series/chess-beginners-course-april" target=_blank style="text-transform:none;">Chess
        </a>
        </li> 

        <li style="text-transform:uppercase;">
        <a href="https://ahamlearning.com/series/robotics-at-aham" target=_blank style="text-transform:none;">Robotics
        </a>
        </li>

        <li style="text-transform:uppercase;">
        <a href="https://ahamlearning.com/summer-camp" target=_blank style="text-transform:none;">Creya Summer Camp
        </a>
        </li>

        <li style="text-transform:uppercase;">
        <a href="https://ahamlearning.com/series/memory-enhancement-workshop" target=_blank style="text-transform:none;">Memory Enhancement
        </a>
        </li>

      </ul>

      </li> --}}

      {{-- <li ><a href="{{ url('https://ahamlearning.com/series/leap-robots-camp') }}" target="_blank" title="Robotics"><span><strong>Robotics</strong></span></a></li> --}}
      <li ><a href="{{ url('classes') }}" title="Courses"><span><strong>Courses</strong></span></a></li>
      <li ><a href="{{ url('about-aham') }}" title="Courses"><span><strong>About Aham</strong></span></a></li>
      <li class="menu-item-has-children"><a href="" title="Locations"><span><strong>Locations</strong></span></a>

      <ul class="sub-menu" style="z-index: 99999;">

        <li>
        <a href="{{ route('aham-gachibowli') }}" style="text-transform:none;">Hyderabad - Gachibowli
        </a>
        </li><li>
        <a href="{{ route('aham-banjara-hills') }}" style="text-transform:none;">Hyderabad - Banjara Hills
        </a>
        </li>

      </ul>

      </li>

      <li class="hidden-xs hidden-sm hidden-md" style="width:420px;margin-left: 20px; height: 22px;">

        <form role="form" action="{{ url('classes') }}">
          <input id="q" type="search" name="q" placeholder="What do you want to learn?" class="form-control" value="{{ Input::get('q','') }}">
        </form>

      </li>

      @if(Sentinel::check())
      <li class="menu-item-has-children">
          <?php
              $image_url = cloudinary_url($user->present()->picture, array("height"=>34, "width"=>34));
          ?>
          <a href="#" style="color:#006696">
            <img src="{{ $image_url }}" alt="" class="img-circle" style="margin-top:-5px;border: 1px solid #a4c0ce;"> Hello, {{ explode(' ',$user->name)[0] }}
          </a>
        <ul class="sub-menu"  style="z-index: 99999;">

          @if($user->hasAccess('admin'))
          <li><a href="{{ route('admin::admin') }}">Admin</a></li>
          @endif

          @if (session('aham:impersonator'))
              <li><a href="{{ route('settings::stop_impersonation') }}">Stop Impersonation</a></li>
          @endif

          @if($user->teacher && $user->teacher->active)
          <li><a href="{{ route('teacher::home') }}">Tutor Dashboard</a></li>
          @endif

          @if($user->student && $user->student->active)
          <li><a href="{{ route('student::home') }}">Learner Dashboard</a></li>
          @endif

          <li><a href="{{ route('auth::logout') }}">Sign Out</a></li>
        </ul>
      </li>
      @else
      <li style="margin-right: 20px;"><a href="{{ route('auth::login') }}" title="Join us"><span><strong>Login</strong></span></a></li>

      {{--Register as teacher/student--}}

        <li class="menu-item-has-children"><a href="#" title="Join us" style="padding: 6px 0px;"><span><strong>Join Aham</strong></span></a>
                <ul class="sub-menu" style="z-index: 99999;">

                  <li><a href="{{ route('auth::register-as-a-student') }}" style="text-transform:none; font-size:14px;" title="Student Registration"><span>As a Learner</span></a>
                  </li>

                  <li><a href="{{ route('auth::register-as-a-teacher') }}" style="text-transform:none; font-size:14px;" title="Teacher Registration"><span>As a Tutor</span></a>
                  </li>

                </ul>
        </li>


      @endif

    </ul>

    <div class="header-icons">
      <div class="search ol-search-trigger simple hidden-lg" id="top_search">
        <a href="#"><i class="oli oli-search"></i></a>
        <div class="search-area">
          <div class="search-bar-wrapper">
            <form role="form" action="{{ route('classes') }}">
              <input id="q" type="search" name="q" placeholder="Type Topic Name to Search.." class="form-control">
            </form>
          </div>
        </div>
      </div>
      <div class="ol-mobile-trigger hamburger hamburger--elastic">
        <div class="hamburger-box">
          <div class="hamburger-inner"></div>
        </div>
      </div>
    </div>

    @if(!is_null($homepage_scroll) && $homepage_scroll->value == 'yes')

      <div class="container">
        <div class="row">
          <div class="col-sm-12" style="line-height: 30px;">

          @if(!is_null($homepage_scroll_message))

            <marquee width="100%" style="color: red; width: 100%; line-height: 35px;" scrollamount="8" behavior="scroll"> {!! $homepage_scroll_message->value !!}</marquee>

          @endif
          
          </div>
        </div>
      </div>

    @endif



    </div>
  </div>

<!-- #####End header main area-->

</header>