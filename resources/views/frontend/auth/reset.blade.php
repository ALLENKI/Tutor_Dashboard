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
                  <div id="login">
                    <h6 class="title">Reset Password</h6>
                    <hr>
                    {!! BootForm::open() !!}


                    {!! BootForm::password('Password', 'password')
                                ->hideLabel()
                                ->placeholder('Password')
                                ->attribute('required','true') !!}
                    
                    {!! BootForm::password('Password Confirmation', 'password_confirmation')
                                ->hideLabel()
                                ->placeholder('Password Confirmation')
                                ->attribute('required','true') !!}

                      <div class="form-group">
                        <input type="submit" value="Reset Password" class="btn btn-small btn-block">
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
.has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline {
    color: #fff;
}

.form-group{
    margin-bottom: 0px;
}

.form-group input, .form-group select{
    font-family: 'Lato', sans-serif;
    font-size: 14px;
    color: #A5A5A5;
    padding: 0 12px;
    height: 40px;
    width: 100%;
    border-radius: 4px;
    border: 0;
    margin-top: 20px;
}
</style>
@stop