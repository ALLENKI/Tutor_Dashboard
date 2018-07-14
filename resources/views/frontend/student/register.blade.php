@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">
  <!-- #####Begin main area-->
  <section id="main-area">
    <section data-img-src="/assets/front/img/student_register.jpg"" class="section parallax-layer hvh-100 tb-vcenter-wrapper">
      <div class="v-center">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-md-offset-3 ofx-auto">
              <!-- #####Begin tab element-->
              <div class="login-form ol-tab" style="max-width:100%;text-align:left;">

                  <!-- #####Begin tab panel item-->
                  <div id="login">
                    <h2 class="title text-center">Join Aham As Learner</h2>
                    <hr>
                        {!! BootForm::open()->action(route('auth::register-as-a-student'))

                        !!}

                        	{!! BootForm::hidden('interested_in','student')
                              ->value('student') !!}

                            {!! BootForm::text('Name', 'name')
                                          ->hideLabel()
                                          ->placeholder('Name *')
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

                            {!! BootForm::select('Grade', 'grade')
                                          ->options([
                                                '' => 'Your Current Education Level',
                                                '5-8' => 'Middle School(Grade 5-8)',
                                                '9-12' => 'High School(Grade 9-12)',
                                                'Under_Grad' => 'Under Grad',
                                                'Grad_or_Higher' => 'Grad or Higher',
                                                'Working_Professional' => 'Working Professional',
                                                'other' => 'Other',
                                            ])
                                          ->hideLabel()
                                          ->placeholder('Grade') !!}


                             {!! 

                                BootForm::select('Preferred City', 'city_id')
                                        ->options($cities)
                                        ->placeholder('City') !!}


                             {!! 

                                BootForm::textarea('Why do you want to learn?', 'what_learn')
                                            ->placeholder('Please tell us what do you want to learn')
                                            ->hideLabel()
                                            ->attribute('rows',3)

                             !!}

                            <hr>

                            <div class="form-submit-1 text-center">
                                <button type="submit" value="Join Aham" class="btn btn-medium" style="background-color: #3083aa; border: 1px solid #3083aa; color: #f9faf8;">Join Aham</button>
                            </div>

                            <div class="link text-center m-top-20">
                                <a href="{{ url('join-as-a-student') }}" style="text-decoration: underline;">
                                    Know More
                                </a>
                            </div>

                            <div class="link text-center m-top-20">
                                <a href="{{ route('auth::register-as-a-teacher') }}" style="text-decoration: underline;">
                                    Do you want to join aham as a tutor?
                                </a>
                            </div>

                            <hr>

                            <p>By clicking "Join Aham" you are agreeing to <a href="{{ route('pages::privacy-policy') }}">"Privacy Policy"</a>  and <a href="{{ route('pages::terms') }}">"Terms and Conditions"</a></p>

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

$('#send_otp').on('click',function(event)
{
$('#error_block').empty().hide();

event.preventDefault();

var data = {mobile: $('#mobile').val()};

console.log(data);

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
        icon: "success"
    });

},
error: function (xhr) {

    var errors = xhr.responseJSON.errors;
    var errorsHtml= '';

    $.each( errors, function( key, value ) {
        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
    });

    console.log(errorsHtml);
    
    $.toast({
        text: errorsHtml,
        position: {
            right: 20,
            top: 120
        },
        stack: false,
        icon: "error"
    });
    
}
});

});

});
</script>
@stop