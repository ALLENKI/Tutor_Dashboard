@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">
  <!-- #####Begin main area-->
  <section id="main-area">
    <section data-img-src="/assets/front/img/backgrounds/011.jpg" class="section parallax-layer hvh-100 tb-vcenter-wrapper ol-para-bg-iR1kD">
      <div class="vcenter">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-md-offset-3 ofx-auto">
              <!-- #####Begin tab element-->
              <div class="login-form ol-tab">

                  <!-- #####Begin tab panel item-->
                  <div id="login">
                    <h3 class="title">Register</h3>
                    <hr>
                        {!! BootForm::open()->action(route('auth::register')) !!}

                            {!! BootForm::text('Name', 'name')
                                          ->hideLabel()
                                          ->placeholder('Name')
                                          ->attribute('required','true') !!}

                            {!! BootForm::email('Email', 'email')
                                        ->hideLabel()
                                        ->placeholder('Email')
                                        ->attribute('required','true') !!}


                            {!! BootForm::password('Password', 'password')
                                        ->hideLabel()
                                        ->placeholder('Password')
                                        ->attribute('required','true') !!}
                            
                            {!! BootForm::password('Password Confirmation', 'password_confirmation')
                                        ->hideLabel()
                                        ->placeholder('Password Confirmation')
                                        ->attribute('required','true') !!}

                            <div class="form-submit-1">
                                <input type="submit" value="Join Aham" class="btn btn-medium">
                            </div>

                            <hr>

                            <p>By clicking "Join Aham" you are agreeing to "Privacy" and "Terms and Conditions" Policies.</p>

                            <div class="link">
                                <a href="{{ route('auth::login') }}">
                                    <i class="icon md-arrow-right"></i>Already have account ? Log in
                                </a>
                            </div>
                        {!! BootForm::close() !!}
                  </div>
                  <!-- #####End tab panel item-->


                <div class="copyright">Copyrights © All Rights Reserved by Aham Learning Hub.</div>
              </div>
              <!-- #####End tab element-->
            </div>
          </div>
        </div>
      </div>
    <div class="parallax-bg-elem parallax-default skrollable skrollable-between" data-anchor-target=".ol-para-bg-iR1kD" type="distance" data-top-top="transform:translate3d(0px, 0px, 0.1px);" data-top-bottom="transform:translate3d(0px, 174px, 0.1px);" style="height: 1047px; top: -174.5px; transform: translate3d(0px, 0px, 0.1px); background-image: url('/assets/front/img/backgrounds/011.jpg');"></div></section>
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