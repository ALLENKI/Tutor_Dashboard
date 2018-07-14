<!-- #####Begin footer-->
<footer id="footer" class="dark-wrapper">
<div id="footer-main">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-sm-4"><a href="{{ url('/') }}" class="img"><img src="https://res.cloudinary.com/ahamlearning/image/upload/q_auto:eco,f_auto/v1472549302/aham-logo_aogj5a.png" alt="Aham Learning Hub" class="ol-retina"></a></div>
          <div class="col-sm-8">
            <p style="color:#fff;">We believe that learning is best achieved within a supportive community that values individual learning styles and authentic learning experiences. Our vision at Aham is to bring together passionate subject matter experts, 21st century learners, committed team, organized content, innovative teaching strategies and relevant technology to create the world’s best learning hubs where authentic and personalized learning on any subject is experienced.</p>
          </div>
        </div>
        <div class="sp-line-50"></div>
        <div class="row">
          <div class="col-sm-4">
            <!-- #####Begin widget-->
            <div class="widget">
              <div class="links">
                <ul>
                  <li><a href="{{ url('classes') }}">Aham Courses</a></li>
                  <li><a href="{{ url('classes-in-physics') }}">Physics Courses</a></li>
                  <li><a href="{{ url('classes-in-mathematics') }}">Mathematics Courses</a></li>
                  <li><a href="{{ url('classes-in-leadership-1') }}">Leadership Courses</a></li>
                </ul>
              </div>
            </div>
            <!-- #####End widget-->
          </div>
          <div class="col-sm-4">
            <!-- #####Begin widget-->
            <div class="widget">
              <div class="links">
                <ul>
                  <li><a href="{{ url('about-aham') }}">About Aham</a></li>
                  <li><a href="{{ url('join-as-a-student') }}">Be a Learner</a></li>
                  <li><a href="{{ url('join-as-a-tutor') }}">Be a Tutor</a></li>
                  <li><a href="{{ route('auth::login') }}">Login</a></li>
                  <li><a href="{{ url('aham-location') }}">Locations</a></li>
                  
                </ul>
              </div>
            </div>
            <!-- #####End widget-->
          </div>
          <div class="col-sm-4">
            <!-- #####Begin widget-->
            <div class="widget">
              <div class="links">
                <ul>
                  
                  <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                  <li><a href="{{ route('pages::terms') }}">Terms of service</a></li>
                  <li><a href="{{ route('pages::privacy-policy') }}">Privacy Policy</a></li>
                  <li><a href="{{ route('pages::pricing') }}">Pricing & Refunds</a></li>

                </ul>
              </div>
            </div>
            <!-- #####End widget-->
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <!-- #####Begin number item-->
        <div class="fact-item left-alined">
          <div class="fact-icon-wrap left">
            <div class="fact-icon oli oli-literature"></div>
          </div>
          <div class="fact-number-wrap">
            <div class="fact-number">1000+</div>
          </div>
          <h2 class="fact-title">Topics</h2>
        </div>
        <!-- #####End number item-->
        <div class="sp-blank-10"></div>
        <p style="color:#fff;">Browse 1000s of topics from various subjects like Physics, Mathematics, Programming etc.</p>
        <div class="sp-blank-10"></div>
        <!-- #####Begin number item-->
        <div class="fact-item left-alined">
          <div class="fact-icon-wrap">
            <div class="fact-icon oli oli-user_male_circle"></div>
          </div>
          <div class="fact-number-wrap">
            <div class="fact-number">7000+</div>
          </div>
          <h2 class="fact-title">Hours of Learning</h2>
        </div>
        <!-- #####End number item-->
        <div class="sp-blank-10"></div>
        <p style="color:#fff;">This is just the beginning of a great journey</p>
      </div>
    </div>
  </div>
</div>

<div id="footer-bar">
  <div class="container">
    <div class="row table-wrapper bottom-bar">
      <div class="col-sm-8 vcenter">
        <ul class="footer-menu">
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('classes') }}">Courses</a></li>
          <li><a href="{{ url('about-aham') }}">What We Do</a></li>
          <li><a href="{{ route('auth::register-as-a-teacher') }}">Join as Tutor</a></li>
          <li><a href="{{ route('auth::register-as-a-student') }}">Join as Learner</a></li>
        </ul>
        <div class="sp-blank-10"></div>
        <div class="inline-wrapper">
          <div class="copyright">&copy; 2017. Aham Technologies Inc.</div>
        </div>
      </div>
      {{-- <div class="col-sm-4 vcenter footer-socials">
        <ul class="social-icons border-circle hover-tb-theme text-right">
          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
          <li><a href="https://plus.google.com/116420091986382925463"><i class="fa fa-google-plus"></i></a></li>
          <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
          <li><a href="https://www.youtube.com/watch?v=1DwTx4z75IQ"><i class="fa fa-youtube"></i></a></li>
        </ul>
      </div> --}}
    </div>
  </div>
</div>
</footer>
<!-- #####End footer-->