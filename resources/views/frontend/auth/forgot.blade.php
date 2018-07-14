@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">
  <!-- #####Begin main area-->
  <section id="main-area">
    <section class="section parallax-layer hvh-100 tb-vcenter-wrapper ol-para-bg-iR1kD">
      <div class="vcenter">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-md-offset-3 ofx-auto">
              <!-- #####Begin tab element-->
              <div class="login-form ol-tab">

                  <!-- #####Begin tab panel item-->
                  <div id="login" class="m-bottom-20">
                    <h6 class="title">Forgot Password</h6>
                    <hr>
                    {!! BootForm::open()->action(route('auth::forgot-password'))!!}

                    {!! BootForm::email('Email', 'email')
                                    ->hideLabel()
                                    ->placeholder('Email')
                                    ->attribute('required','true') !!}

                    <div class="form-group">
                      <input type="submit" value="Send Reset Email" class="btn btn-small btn-block" style="background-color: #3083aa; border: 1px solid #3083aa; color: #f9faf8;">
                    </div>

                    {!! BootForm::close() !!}

                  </div>
                  <!-- #####End tab panel item-->


                  <a href="{{ route('auth::login') }}">
                    Go to Login Page
                  </a>


                <div class="copyright">Copyrights © All Rights Reserved by Aham Technologies Inc.</div>
              </div>
              <!-- #####End tab element-->
            </div>
          </div>
        </div>
      </div>
    <div class="parallax-bg-elem parallax-default skrollable skrollable-between" data-anchor-target=".ol-para-bg-iR1kD" type="distance" data-top-top="transform:translate3d(0px, 0px, 0.1px);" data-top-bottom="transform:translate3d(0px, 174px, 0.1px);" style="height: 1047px; top: -174.5px; transform: translate3d(0px, 0px, 0.1px); background-image: url('/assets/front/img/backgrounds/Login-background.jpg');"></div></section>
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