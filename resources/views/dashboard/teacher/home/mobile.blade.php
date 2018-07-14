@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.home.avatar')
{{-- 
<div class="page-header page-header-inverse has-cover" style="    background: url(http://res.cloudinary.com/ahamlearning/image/upload/c_scale,h_860,l_black_opacity_60_sgz5cd,w_1841/v1466663558/Collaboration-Technology_zncjmr.jpg);background-size: cover;background-position-y: -135px;">
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
    @include('dashboard.teacher.home.sidebar')
</div> 

<div class="col-md-9">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Change Mobile Number</h3>
  </div>
  <div class="panel-body">
    
      {!! BootForm::open()->action(route('teacher::settings.mobile')) !!}

      <div class="form-group input-group {!! $errors->has('mobile') ? 'has-error' : '' !!}">

      <label class="control-label sr-only" for="mobile">Mobile</label>

      <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile *" required="true" value="{{ Input::old('mobile',$user->mobile) }}">
      
      <span class="input-group-btn">
        <button class="btn btn-small btn-skin-blue" type="button" id="send_otp">Generate OTP</button>
      </span>
      
      </div>

      <div class="form-group {!! $errors->has('mobile') ? 'has-error' : '' !!}">


      {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}

      </div>

      {!! BootForm::text('OTP', 'otp')
                ->hideLabel()
                ->placeholder('Enter a valid OTP *')
                ->attribute('required','true') !!}

                            
      <div>
          <input type="submit" value="Update Mobile" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
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

$(function(){

$('#send_otp').on('click',function(event)
{

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

    },
    error: function (xhr) {

        var errors = xhr.responseJSON.errors;
        var errorsHtml= '';

        $.each( errors, function( key, value ) {
            errorsHtml += value[0] + '<br />'; //showing only the first error.
        });
        
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