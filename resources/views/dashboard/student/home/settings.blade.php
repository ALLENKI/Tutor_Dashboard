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
    <h3 class="panel-title">Profile <small>({{ $user->email }})</small> </h3>
  </div>
  <div class="panel-body">

      <div class="form-group">
      <label class="control-label m-bottom-10">
      Profile Picture
        
      </label>

      <div class="row">
        <div class="col-md-2 text-center">
          <?php
            $image_url = cloudinary_url($user->present()->picture);
          ?>
          <img src="{{ $image_url }}" alt="" class="img-responsive" style="border: 1px solid #D0D0D0; border-radius:5px;">
          <small><strong><a href="javascript:;" id="changeAvatar">Change Image</a></strong></small>
        </div>
      </div>

      </div>

    
      {!! BootForm::open()->action(route('student::settings.profile')) !!}

      {!! BootForm::bind($user) !!}

      {!! BootForm::text('Name', 'name')
                            ->placeholder('Name')
                            ->attribute('required','true')
                            ->attribute('value',Input::old('name',$user->name)) !!}
                            
      <div>
          <input type="submit" value="Update Profile" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
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