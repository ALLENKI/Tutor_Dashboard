@extends('frontend.layouts.master')


@section('content')


      <?php 

        $name = '';
        if(isset($public_profile['name']))
        {
          $name = $public_profile['name'];
        }

        $tagline = '';
        if(isset($public_profile['tagline']))
        {
          $tagline = $public_profile['tagline'];
        }

        $bio = '';
        if(isset($public_profile['bio']))
        {
          $bio = $public_profile['bio'];
        }

        $linkedin = '';
        if(isset($public_profile['linkedin']))
        {
          $linkedin = $public_profile['linkedin'];
        }

        $twitter = '';
        if(isset($public_profile['twitter']))
        {
          $twitter = $public_profile['twitter'];
        }

        $facebook = '';
        if(isset($public_profile['facebook']))
        {
          $facebook = $public_profile['facebook'];
        }

        $educationRows = [];

        if(isset($public_profile['education']))
        {
          $educationRows = $public_profile['education'];
        }

        $researchRows = [];

        if(isset($public_profile['research']))
        {
          $researchRows = $public_profile['research'];
        }


        $experienceRows = [];

        if(isset($public_profile['experience']))
        {
          $experienceRows = $public_profile['experience'];
        }
      ?>

      <!-- #####Begin contents-->
      <section id="contents">
        
        <section class="page-contents">
          <!-- #####Begin main area-->
          <section id="main-area">
            <section class="section p-top-50">
              <div class="container">
                <div class="row">
                  <div class="col-sm-4 col-md-3 pull-up-50"><img src="{{ cloudinary_url($tutor->user->present()->picture, ['secure' => true]) }}" alt="">
                    <div class="sp-blank-40"></div>
                    
                    <!-- #####Begin widget-->
                    <div class="widget">
                      <h4 class="with-undeline">Social Media</h4>
                      <!-- #####Begin social icons-->
                      <ul class="social-icons shape-circle">
                        <li><a href="{{ $facebook }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="{{ $twitter }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="{{ $linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                      </ul>
                      <!-- #####End social icons-->
                    </div>
                    <!-- #####End widget-->
                  </div>
                  <div class="col-sm-8 col-md-8 col-md-offset-1">
                    <h2>{{ $name }}</h2>
                    <ul class="list-unstyled">
                      <li>{{ $tagline }}</li>
                    </ul>

                    <div class="sp-blank-60"></div>
                    <h3 class="with-sideline m-bottom-40">Subjects</h3>
                    <ul class="list-unstyled">
                      @foreach(explode(',', $user->interested_subjects) as $subject)
                      <li>{{ trim($subject) }}</li>
                      @endforeach
                    </ul>

                    <div class="sp-blank-60"></div>
                    <h3 class="with-sideline m-bottom-40">Biography</h3>
                    <p>{{ $bio }} </p>
                    <div class="sp-blank-50"></div>
                    <h3 class="with-sideline m-bottom-40">Educations</h3>
                    <!-- #####Begin timeline-->
                    <div class="ol-timeline">

                      @foreach($educationRows as $index => $educationRow)
                      <!-- #####Begin timeline item-->
                      <div class="tl-item pub-item with-icon">
                        <div class="elem-wrapper"><i class="oli oli-bookmark"></i></div>
                        <div class="content-wrapper">
                          <h3 class="title">{{ $educationRow['degree'] }}</h3>
                          <div class="description">
                            <p>{{ $educationRow['field'] }}<br> {{ $educationRow['school'] }}</p>
                          </div>
                        </div>
                      </div>
                      <!-- #####End timeline item-->
                      @endforeach

                    </div>
                    <!-- #####End timeline-->


                    <!-- #####End timeline-->

                    <div class="sp-blank-50"></div>
                    <h3 class="with-sideline m-bottom-40">Experience</h3>
                    <!-- #####Begin timeline-->
                    <div class="ol-timeline">

                      @foreach($experienceRows as $index => $experienceRow)
                      <!-- #####Begin timeline item-->
                      <div class="tl-item pub-item with-icon">
                        <div class="elem-wrapper"><i class="oli oli-bookmark"></i></div>
                        <div class="content-wrapper">
                          <h3 class="title">{{ $experienceRow['title'] }}</h3>
                          <div class="description">
                            <p>in {{ $experienceRow['company'] }} <br> {{ $experienceRow['location'] }}</p>
                          </div>
                        </div>
                      </div>
                      <!-- #####End timeline item-->
                      @endforeach

                      
                    </div>
                    <!-- #####End timeline-->




                    <div class="sp-blank-50"></div>
                    <h3 class="with-sideline m-bottom-40">Research Interests</h3>
                    <ol class="with-shaded-label ol-greek">
                      @foreach($researchRows as $index => $researchRow)
                      <li>{{ $researchRow }}</li>
                      @endforeach
                    </ol>
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