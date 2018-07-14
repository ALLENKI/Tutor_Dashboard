@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">

  <!-- #####Begin main area-->
  <section id="main-area">
    <section data-img-src="https://res.cloudinary.com/ahamlearning/image/upload/c_fill,f_auto,g_north,h_2244,q_auto:eco,w_2460/v1473761206/AdobeStock_97109542_r7yztg.jpg" class="section parallax-layer tb-vcenter-wrapper" style="padding-bottom: 320px;">
      <div class="vcenter">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-md-offset-0 ofx-auto">
              <!-- #####Begin tab element-->
              <div class="login-form ol-tab" style="max-width:100%;text-align:left;">

                  <!-- #####Begin tab panel item-->
                  <div id="login">
                    <h2 class="title text-center">Join Aham As Tutor</h2>
                    <hr>
                        {!! BootForm::open()->action(route('auth::register-as-a-teacher')) 
                        ->attribute('enctype',"multipart/form-data")

                        !!}

                          <h3>Account Details</h3>

                        	{!! BootForm::hidden('interested_in','teacher')
                              ->value('teacher') !!}

                            {!! BootForm::text('Name', 'name')
                                          ->hideLabel()
                                          ->placeholder('Name *')
                                          ->attribute('required','true') !!}

                            <div class="form-group input-group {!! $errors->has('mobile') ? 'has-error' : '' !!}">
                            <label class="control-label sr-only" for="mobile">Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile *" required="true" value="{{ Input::old('mobile','') }}">
                            <span class="input-group-btn">
                              <button class="btn btn-small btn-skin-blue" type="button" id="send_otp">Generate OTP</button>
                            </span>
                            
                            </div>

                            <div class="form-group {!! $errors->has('mobile') ? 'has-error' : '' !!}">


                            {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}

                            </div>

                            <div id="after_otp" style="display:none;">

                            {!! BootForm::text('OTP', 'otp')
                                      ->hideLabel()
                                      ->placeholder('Enter a valid OTP *')
                                      ->attribute('required','true') !!}	

                            {!! BootForm::email('Email', 'email')
                                        ->hideLabel()
                                        ->placeholder('Email *')
                                        ->attribute('required','true') !!}


                            {!! BootForm::password('Password', 'password')
                                        ->hideLabel()
                                        ->placeholder('Create a Password *')
                                        ->attribute('required','true') !!}
                            
                            {!! BootForm::password('Password Confirmation', 'password_confirmation')
                                        ->hideLabel()
                                        ->placeholder('Confirm Password *')
                                        ->attribute('required','true') !!}

                            <h3>More About You</h3>


                           {!! BootForm::textarea('Why do you want to teach?', 'why_teacher')
                                          ->placeholder('Please describe briefly your passion for teaching')
                                          ->hideLabel()
                                          ->attribute('rows',3)

                           !!}

                            {!! BootForm::select('Interested Subjects', 'interested_subjects[]')
                                          ->options($topics)
                                          ->hideLabel()
                                          ->placeholder('Interested Subjects')
                                          ->attribute('id','interested_subjects')
                                          ->attribute('multiple','true') !!}


                           {!! BootForm::text('Linkedin Profile', 'linkedin')
                                          ->hideLabel()
                                          ->placeholder('Linkedin Profile if you have one')
                           !!}


                            {!! BootForm::select('Current Profession', 'current_profession')
                                          ->options([
                                                '' => 'Please select your current profession *',
                                                'Teaching' => 'Teaching',
                                                'Research' => 'Research',
                                                'Technology' => 'Technology',
                                                'Business' => 'Business',
                                                'Other' => 'Other',
                                            ])
                                          ->hideLabel()
                                          ->placeholder('Current Profession') !!}


                            <div class="form-group {!! $errors->has('resume') ? 'has-error' : '' !!}">
                              <label style="margin-bottom:10px;">Please upload your resume (recommended)</label>
                              <input type="file" name="resume">
                                    {!! $errors->first('resume', '<p class="help-block">:message</p>') !!}
                            </div>

                            <hr>

                            <div class="form-submit-1 text-center">
                                <button type="submit" value="Join Aham" class="btn btn-medium" style="background-color: #3083aa; border: 1px solid #3083aa; color: #f9faf8;">Join Aham</button>
                            </div>

                            <hr>

                            <p>By clicking "Join Aham" you are agreeing to <a href="{{ route('pages::privacy-policy') }}">"Privacy Policy"</a>  and <a href="{{ route('pages::terms') }}">"Terms and Conditions"</a></p>

                            </div>


                             <div class="link text-center m-top-20">
                                <a href="{{ url('join-as-a-tutor') }}" style="text-decoration: underline;">
                                    Know More
                                </a>
                            </div>

                            <div class="link text-center m-top-20">
                                <a href="{{ route('auth::register-as-a-student') }}" style="text-decoration: underline;">
                                    Do you want to join aham as a student?
                                </a>
                            </div>

                            <hr>

                            <div class="link">
                                <a href="{{ route('auth::login') }}">
                                    <i class="icon md-arrow-right"></i>Already have account ? Log in
                                </a>
                            </div>
                        {!! BootForm::close() !!}
                  </div>
                  <!-- #####End tab panel item-->


                <div class="copyright">Copyrights © All Rights Reserved by Aham Technologies Inc.</div>
              </div>
              <!-- #####End tab element-->
            </div>
          </div>
        </div>
      </div>
  </section>
  </section>
  <!-- #####End main area
  -->
  <div class="clearfix"></div>
</section>

@stop

@section('styles')
@parent
<style type="text/css">

</style>
@stop


@section('scripts')
@parent
<script type="text/javascript">

$(function(){

  $('#interested_subjects').multiselect({
    nonSelectedText: "Selects subjects which interest you",
    buttonClass: 'btn btn-default btn-xs btn-block text-left',
    buttonContainer: '<div class="btn-group" style="width:100%;background:white"></div>',
  });

  
  @if(Session::has('flash_notification.message'))
      $('#after_otp').show();
      $('.parallax-bg-elem').css('height',1500);
      $('.parallax-bg-elem').css('height',1500);
  @endif

  $('#send_otp').on('click',function(event)
  {
  $('#error_block').empty().hide();

  event.preventDefault();

  var data = {mobile: $('#mobile').val()};

  $.ajax({
  url: BASE+'get_otp',
  data: data, // returns all cells' data
  dataType: 'json',
  type: 'POST',
  success: function (res) {

      $.toast({
          text: 'Please check your mobile for OTP',
          position: {
              right: 20,
              top: 120
          },
          stack: false,
          hideAfter : false,
          allowToastClose : true,
          icon: "success"
      });

      $('.parallax-bg-elem').css('height',1500);
      $('#after_otp').show();

  },
  error: function (xhr) {

      var errors = xhr.responseJSON.errors;
      var errorsHtml= '';

      $.each( errors, function( key, value ) {
          errorsHtml += value[0] + '<br />'; //showing only the first error.
      });

      console.log(errorsHtml);
      
      $.toast({
          text: errorsHtml,
          position: {
              right: 20,
              top: 120
          },
          stack: false,
          hideAfter : false,
          allowToastClose : true,
          icon: "error"
      });
      
  }
  });

  });

});
</script>
@stop