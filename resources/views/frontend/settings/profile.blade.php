@extends('frontend.layouts.master')


@section('content')


@include('frontend.settings.avatar')

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
            <h3 class="panel-title">Public Profile <small>({{ $user->email }})</small> </h3>
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

            
              {!! BootForm::open()->action(route('settings::profile')) !!}

              {!! BootForm::text('Name', 'name')
                                    ->placeholder('Name')
                                    ->attribute('required','true')
                                    ->attribute('value',Input::old('name',$user->name)) !!}

               
              {!! BootForm::select('Interested In?', 'interested_in')
                            ->options([
                                        'user' => 'Volunteer', 
                                        'student' => 'Learner',
                                        'teacher' => 'Tutor',
                                        'student_teacher' => 'Learner & Tutor'
                                      ])
                            ->select(Input::old('interested_in',$user->interested_in))
                            ->placeholder('Interested In?')
                            ->attribute('required','true') !!}

              {!! BootForm::select('Locality', 'locality_id')
                            ->options($localities)
                            ->select(Input::old('locality_id',$user->locality_id))
                            ->attribute('required','true') !!}

              {!! BootForm::select('City', 'city_id')
                ->options($cities)
                ->select(Input::old('city_id',$user->city_id))
                ->attribute('required','true') !!}


              @if($user->student)
  
              <h6 class="m-top-20" style="color: #006696;">Learner Profile</h6>
              <hr>

              {!! BootForm::text('Grade', 'grade')
                                    ->attribute('value',Input::old('grade',$user->student->grade)) !!}

              {!! BootForm::text('Curriculum', 'curriculum')
                                    ->attribute('value',Input::old('curriculum',$user->student->curriculum)) !!}
              @endif

              @if($user->teacher)
  
              <h6 class="m-top-20" style="color: #006696;">Tutor Profile</h6>
              <hr>

              {!! BootForm::text('About Me', 'about_me')
                                    ->attribute('value',Input::old('about_me',$user->teacher->about_me)) !!}

              {!! BootForm::textarea('Description', 'description')
                          ->value(Input::old('description',$user->teacher->description))
                                    ->attribute('rows',3) !!}
              @endif

              <hr>

              <div>
                  <input type="submit" value="Update Profile" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
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