@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.home.avatar')

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
    @include('dashboard.teacher.home.sidebar')
</div> 

<div class="col-md-9">

<div class="panel panel-default">

  <div class="panel-heading">
    <h3 class="panel-title">Profile <small>({{ $user->email }})</small></h3>
  </div>

  <div class="panel-body">

      <div class="form-group">
      <label class="control-label m-bottom-10" style="font-size:16px;">
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

    
      {!! BootForm::open()->action(route('teacher::settings.profile'))->multipart() !!}

      {!! BootForm::bind($user) !!}

      {!! BootForm::text('Name', 'name')
                            ->placeholder('Name')
                            ->attribute('required','true')
                            ->attribute('value',Input::old('name',$user->name)) !!}

     {!! 

        BootForm::textarea('Why do you want to teach?', 'why_teacher')
                    ->placeholder('Please tell us briefly why you are passionate about teaching *')
                    ->attribute('rows',3)
                    ->value($user->why_teacher)

     !!}

      {!! BootForm::select('Preferred City', 'city_id')
                    ->options($cities)
                    ->placeholder('City') !!}

      <div class="form-group">
      <label class="control-label" for="city_id">Preferred Subjects</label>
      <div class="multi-select-full">
        {!! Form::select('interested_subjects[]', $topics, explode(',', $user->interested_subjects),['multiple' => 'multiple', 'class' => 'multiselect']) !!}
      </div>
      </div>

     {!! BootForm::text('Linkedin Profile', 'linkedin')
                    ->placeholder('Linkedin Profile if you have one')
                    ->value($user->linkedin)
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
                    ->placeholder('Current Profession') !!}

      <div class="form-group {!! $errors->has('resume') ? 'has-error' : '' !!}">
      <label>Please upload your resume</label>
      <div class="row">
           <div class="col-md-6">
                 <input  type="file" name="resume"> 
            </div>
            <div class="col-md-6">
            @if($user->resume_file != null)
                <span class="label label-success float: right">uploaded</span>
            @else
                <span class="label label-danger float: right">upload resume</span>
            @endif
            </div>
      </div>
              {!! $errors->first('resume', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group {!! $errors->has('aadhar_card') ? 'has-error' : '' !!}">
      <label>Please upload your Aadhar card</label>
      <div class="row">
             <div class="col-md-6">
                  <input type="file" name="aadhar_card">
             </div>
             <div class="col-md-6">
                  @if($user->Aadhar_card != null)
                      <span class="label label-success float: right">Uploaded</span>
                  @else
                      <span class="label label-danger float: right">Upload Aadhar Card</span>
                  @endif
              </div>
      </div>
              {!! $errors->first('aadhar_card', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group {!! $errors->has('pan_card') ? 'has-error' : '' !!}">
      <label>Please upload your Pan Card</label>
       <div class="row">
             <div class="col-md-6">
                    <input type="file" name="pan_card">
             </div>
             <div class="col-md-6">
                  @if($user->pan_card != null)
                      <span class="label label-success float: right">Uploaded</span>
                  @else
                      <span class="label label-danger float: right">Upload Pan Card</span>
                  @endif
              </div>
       </div>
              {!! $errors->first('pan_card', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group {!! $errors->has('form_16') ? 'has-error' : '' !!}">
      <label>Please upload your Form 16</label>
       <div class="row">
             <div class="col-md-6">
                <input type="file" name="Form_16">
             </div>
             <div class="col-md-6">a

























              
                  @if($user->form_16 != null)
                      <span class="label label-success float: right">Uploaded</span>
                  @else
                      <span class="label label-danger float: right">Upload Form 16</span>
                  @endif
              </div>
       </div>
              {!! $errors->first('Form_16', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group {!! $errors->has('form_16') ? 'has-error' : '' !!}">
      <label>Upload cancelled cheque for bank details</label>
       <div class="row">
             <div class="col-md-6">
                <input type="file" name="cheque">
             </div>
             <div class="col-md-6">
                  @if($user->cheque != null)
                      <span class="label label-success float: right">Uploaded</span>
                  @else
                      <span class="label label-danger float: right">Upload cheque</span>
                  @endif
              </div>
       </div>
              {!! $errors->first('cheque', '<p class="help-block">:message</p>') !!}
      </div>

      <div>
          <input type="submit" value="Update Profile" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
      </div>

      {!! BootForm::close() !!}

  </div>
</div>

</div>

</div>

@stop

@section('styles')
@parent

<style>

label {
    font-size: 16px;
    }  

</style>

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
