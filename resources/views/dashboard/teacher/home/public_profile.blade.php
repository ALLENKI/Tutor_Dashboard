@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.home.avatar')

<div class="row">

<div class="col-md-12">

<div class="panel panel-default">

  <div class="panel-heading">
    <h3 class="panel-title">Public Profile <small>({{ $user->email }})</small></h3>
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

    
      {!! BootForm::open()->action(route('teacher::settings.public_profile')) !!}

      {!! BootForm::bind($user) !!}

      <?php 
        $name = '';
        if(isset($public_profile['name']))
        {
          $name = $public_profile['name'];
        }

        $tagline = '';
        if(isset($public_profile['tagline']))
        {
          $tagline = $public_profile['tagline'];
        }

        $bio = '';
        if(isset($public_profile['bio']))
        {
          $bio = $public_profile['bio'];
        }

        $linkedin = '';
        if(isset($public_profile['linkedin']))
        {
          $linkedin = $public_profile['linkedin'];
        }

        $twitter = '';
        if(isset($public_profile['twitter']))
        {
          $twitter = $public_profile['twitter'];
        }

        $facebook = '';
        if(isset($public_profile['facebook']))
        {
          $facebook = $public_profile['facebook'];
        }

        $educationRows = [];

        if(isset($public_profile['education']))
        {
          $educationRows = $public_profile['education'];
        }

        $researchRows = [];

        if(isset($public_profile['research']))
        {
          $researchRows = $public_profile['research'];
        }


        $experienceRows = [];

        if(isset($public_profile['experience']))
        {
          $experienceRows = $public_profile['experience'];
        }
      ?>

      {!! BootForm::text('Name', 'name')
                            ->placeholder('Name')
                            ->attribute('required','true')
                            ->attribute('value',Input::old('name',$name)) !!}


     {!! 

        BootForm::text('Tagline', 'tagline')
                    ->placeholder('Tagline *')
                    ->value(Input::old('tagline',$tagline))

     !!}

     {!! 

        BootForm::textarea('Biography', 'bio')
                    ->placeholder('Biography *')
                    ->attribute('rows',3)
                    ->value(Input::old('bio',$bio))

     !!}

    <div class="form-group">
    <label class="control-label" for="city_id">Preferred Subjects</label>
    <div class="multi-select-full">
      {!! Form::select('interested_subjects[]', $topics, explode(',', $user->interested_subjects),['multiple' => 'multiple', 'class' => 'multiselect']) !!}
    </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        Your Education
        <button type="button" class="btn btn-primary btn-xs pull-right" onClick="addEducationRow()">+</button>
      </div>

      <div class="panel-body">
        

        <table class="table">

          <thead>
            <th></th>
            <th>Degree</th>
            <th>School</th>
            <th>Field</th>
            <th></th>
          </thead>

          <tbody id="educationRows">
            
            @foreach($educationRows as $index => $educationRow)
            <?php $key = $index+1; ?>
            <tr class="educationRow" id="educationRow{{$key}}">
              <td width="5%">
                <i class='icon-move'></i>
              </td>
              <td width="30%">
                <input type="text" name="education[{{$key}}][degree]" required="" class="form-control" value="{{ $educationRow['degree'] }}">
              </td>
              <td width="30%">
                <input type="text" name="education[{{$key}}][school]" required="" class="form-control" value="{{ $educationRow['school'] }}">
              </td>
              <td width="30%">
                <input type="text" name="education[{{$key}}][field]" required="" class="form-control" value="{{ $educationRow['field'] }}">
              </td>
              <td width="5%">
                <button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteEducationRow('educationRow{{$key}}')">X</button>
              </td>

            </tr>
            @endforeach

          </tbody>
        </table>

        
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        Your Experience
        <button type="button" class="btn btn-primary btn-xs pull-right" onClick="addExperienceRow()">+</button>
      </div>

      <div class="panel-body">
        

        <table class="table">

          <thead>
            <th></th>
            <th>Company</th>
            <th>Title</th>
            <th>Location</th>
            <th></th>
          </thead>

          <tbody id="experienceRows">
            
            @foreach($experienceRows as $index => $experienceRow)
            <?php 
              $key = $index + 1;
            ?>
            <tr class="experienceRow" id="experienceRow{{$key}}">

              <td width="5%">
                <i class='icon-move'></i>
              </td>

              <td width="30%">
                <input type="text" name="experience[{{$key}}][company]" required="" class="form-control" value="{{ $experienceRow['company'] }}">
              </td>
              <td width="30%">
                <input type="text" name="experience[{{$key}}][title]" required="" class="form-control" value="{{ $experienceRow['title'] }}">
              </td>
              <td width="30%">
                <input type="text" name="experience[{{$key}}][location]" required="" class="form-control" value="{{ $experienceRow['location'] }}">
              </td>
              <td width="5%">
                <button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteExperienceRow('experienceRow{{$key}}')">X</button>
              </td>

            </tr>
            @endforeach

          </tbody>
        </table>

        
      </div>
    </div>


    <div class="panel panel-default">
      <div class="panel-heading">
        Research Interests
        <button type="button" class="btn btn-primary btn-xs pull-right" onClick="addResearchRow()">+</button>
      </div>

      <div class="panel-body">
        

        <table class="table">

          <thead>
            <th>Interest</th>
            <th></th>
          </thead>

          <tbody id="researchRows">
            
            @foreach($researchRows as $index => $researchRow)
            <?php 
              $key = $index + 1;
            ?>
            <tr class="researchRow" id="researchRow{{$key}}">

              <td width="5%">
                <i class='icon-move'></i>
              </td>

              <td width="90%">
                <input type="text" name="research[{{$key}}]" required="" class="form-control" value="{{ $researchRow }}">
              </td>

              <td width="5%">
                <button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteResearchRow('researchRow{{$key}}')">X</button>
              </td>

            </tr>
            @endforeach

          </tbody>
        </table>

        
      </div>
    </div>

     {!! BootForm::text('Linkedin Profile', 'linkedin')
                    ->placeholder('Linkedin Profile if you have one')
                    ->value(Input::old('linkedin',$linkedin))
     !!}

     {!! BootForm::text('Twitter Profile', 'twitter')
                    ->placeholder('Twitter Profile if you have one')
                    ->value(Input::old('twitter',$twitter))
     !!}

     {!! BootForm::text('Facebook Profile', 'facebook')
                    ->placeholder('Facebook Profile if you have one')
                    ->value(Input::old('facebook',$facebook))
     !!}
                 
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

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.2.1/mustache.min.js"></script>

  <script id="educationTemplate" type="x-tmpl-mustache">

      <tr class="educationRow" id="educationRow@{{index}}">

        <td width="5%">
          <i class='icon-move'></i>
        </td>

        <td width="30%">
          <input type="text" name="education[@{{index}}][degree]" required class="form-control">
        </td>
        <td width="30%">
          <input type="text" name="education[@{{index}}][school]" required class="form-control">
        </td>
        <td width="30%">
          <input type="text" name="education[@{{index}}][field]" required class="form-control">
        </td>
        <td width="5%">
          @{{#a_boolean}}
          <button type="button" class="btn btn-danger btn-xs pull-right" onClick="deleteEducationRow('educationRow@{{index}}')">X</button>
          @{{/a_boolean}}
        </td>

      </tr>

  </script>


  <script id="experienceTemplate" type="x-tmpl-mustache">

      <tr class="experienceRow" id="experienceRow@{{index}}">

        <td width="5%">
          <i class='icon-move'></i>
        </td>

        <td width="30%">
          <input type="text" name="experience[@{{index}}][company]" required class="form-control">
        </td>
        <td width="30%">
          <input type="text" name="experience[@{{index}}][title]" required class="form-control">
        </td>
        <td width="30%">
          <input type="text" name="experience[@{{index}}][location]" required class="form-control">
        </td>
        <td width="5%">
          @{{#a_boolean}}
          <button type="button" class="btn btn-danger btn-xs pull-right" onClick="deleteExperienceRow('experienceRow@{{index}}')">X</button>
          @{{/a_boolean}}
        </td>

      </tr>

  </script>

  <script id="researchTemplate" type="x-tmpl-mustache">

      <tr class="researchRow" id="researchRow@{{index}}">

        <td width="5%">
          <i class='icon-move'></i>
        </td>

        <td width="90%">
          <input type="text" name="research[@{{index}}]" required class="form-control">
        </td>
        <td width="5%">
          @{{#a_boolean}}
          <button type="button" class="btn btn-danger btn-xs pull-right" onClick="deleteResearchRow('researchRow@{{index}}')">X</button>
          @{{/a_boolean}}
        </td>

      </tr>

  </script>

<script type="text/javascript">

  var educationIndex = {{ count($educationRows) }};

  function deleteEducationRow(identifier)
  {
    $('#'+identifier).remove();
    educationIndex--;
  }

  function addEducationRow()
  {
    var a_boolean = true;

    educationIndex++;

    var template = $('#educationTemplate').html();
      Mustache.parse(template);

      var rendered = Mustache.render(template, {index:educationIndex, a_boolean:a_boolean});
      $("#educationRows").append(rendered);
  }

  var experienceIndex = {{ count($experienceRows) }};

  function deleteExperienceRow(identifier)
  {
    $('#'+identifier).remove();
    experienceIndex--;
  }

  function addExperienceRow()
  {
    var a_boolean = true;

    experienceIndex++;

    var template = $('#experienceTemplate').html();
      Mustache.parse(template);

      var rendered = Mustache.render(template, {index:experienceIndex, a_boolean:a_boolean});
      $("#experienceRows").append(rendered);
  }

  var researchIndex = {{ count($researchRows) }};

  function deleteResearchRow(identifier)
  {
    $('#'+identifier).remove();
    researchIndex--;
  }

  function addResearchRow()
  {
    var a_boolean = true;

    researchIndex++;

    var template = $('#researchTemplate').html();
      Mustache.parse(template);

      var rendered = Mustache.render(template, {index:researchIndex, a_boolean:a_boolean});
      $("#researchRows").append(rendered);
  }

  $(document).ready(function(){


    $("#educationRows").sortable({
        items: ".educationRow", 
        opacity: 0.8,
    }).disableSelection();
    
    $("#experienceRows").sortable({
        items: ".experienceRow", 
        opacity: 0.8,
    }).disableSelection();

    $("#researchRows").sortable({
        items: ".researchRow", 
        opacity: 0.8,
    }).disableSelection();

    if(educationIndex == 0)
    {
      addEducationRow();
    }
    
    if(experienceIndex == 0)
    {
      addExperienceRow();
    }

    if(researchIndex == 0)
    {
      addResearchRow();
    }

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