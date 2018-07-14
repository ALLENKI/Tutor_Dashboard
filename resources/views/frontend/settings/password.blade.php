@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">
  <section id="main-area">
    <section class="section p-top-40">
      <div class="container">

      <div class="row">
        <div class="col-md-3">
            @include('frontend.settings.sidebar')
        </div> 

        <div class="col-md-9">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Change Password</h3>
          </div>
          <div class="panel-body">
            
              {!! BootForm::open()->action(route('settings::update-password')) !!}

              {!! BootForm::password('Old Password', 'old_password')
                          ->placeholder('Password')
                          ->attribute('required','true') !!}

              {!! BootForm::password('New Password', 'password')
                          ->placeholder('Password')
                          ->attribute('required','true') !!}
              
              {!! BootForm::password('Confirm New Password', 'password_confirmation')
                          ->placeholder('Password Confirmation')
                          ->attribute('required','true') !!}

              <hr>

              <div>
                  <input type="submit" value="Update Password" class="btn-extra-small btn-primary btn-rounded">
                  <a href="{{ route('auth::forgot-password') }}">Forgot Password</a>
              </div>



              {!! BootForm::close() !!}

          </div>
        </div>

        </div>

      </div>

      </div>
    </section>
  </section>
</section>

@stop

@section('scripts')
@parent
<script type="text/javascript">
  $(document).ready(function(){

    $('#changeAvatar').on('click',function(){

      $('#avatarModal').modal();

    });

  });

</script>

@stop