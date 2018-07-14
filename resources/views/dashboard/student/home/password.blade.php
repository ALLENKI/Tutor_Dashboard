@extends('dashboard.student.layouts.master')

@section('content')

@include('dashboard.student.home.avatar')

{{-- <div class="page-header page-header-inverse has-cover" style="    background: url(http://res.cloudinary.com/ahamlearning/image/upload/c_scale,h_860,l_black_opacity_60_sgz5cd,w_1841/v1466663558/Collaboration-Technology_zncjmr.jpg);background-size: cover;background-position-y: -135px;">
  <div class="page-header-content">
    <div class="page-title" style="padding: 20px 36px 20px 0;">
      <h2 style="font-size: 36px;">
        <span class="text-bold">
        Your Settings
        </span>
      </h2>
    </div>
  </div>
</div> --}}

<div class="row">
<div class="col-md-3">
    @include('dashboard.student.home.sidebar')
</div> 

<div class="col-md-9">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Change Password</h3>
  </div>
  <div class="panel-body">
    
      {!! BootForm::open()->action(route('student::settings.password')) !!}

      {!! BootForm::password('Old Password', 'old_password')
                  ->placeholder('Password')
                  ->attribute('required','true') !!}

      {!! BootForm::password('New Password', 'password')
                  ->placeholder('Password')
                  ->attribute('required','true') !!}
      
      {!! BootForm::password('Confirm New Password', 'password_confirmation')
                  ->placeholder('Password Confirmation')
                  ->attribute('required','true') !!}
                            
      <div>
          <input type="submit" value="Update Password" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
      </div>

      {!! BootForm::close() !!}

  </div>
</div>

</div>

</div>

@stop


@section('scripts')
@parent
<script type="text/javascript">
  $(document).ready(function(){

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

    $('#interested_subjects').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $('#changeAvatar').on('click',function(){

      $('#avatarModal').modal();

    });

  });

</script>

@stop