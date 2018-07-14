@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Teacher Certification</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li><a href="{{ route('admin::users::users.show',$user->id) }}">{{ $user->name }} ({{$user->email}})</a></li>
				<li class="active">Teacher: {{ $teacher->code }}</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

@include('backend.users.avatar')

<div class="row">

<div class="col-md-12">

<div class="panel panel-default">

  <div class="panel-heading">
    <h3 class="panel-title">Manage Commission <small>({{ $user->email }})</small></h3>
  </div>

  <div class="panel-body">

      <div class="row" id="app">
        <div class="col-md-6 col-md-offset-3">
          
          {!! BootForm::open()->action(route('admin::users::teachers.manage_commission',$user->teacher->id)) !!}

          {!! BootForm::bind($tutorCommission) !!}

          <div class="form-group">
          <label class="control-label" for="location_id">Location</label>
          <div class="multi-select-full">
            {!! Form::select('location_id', $locations,$tutorCommission->location_id,['class' => 'form-control']) !!}
          </div>
          </div>
         
          <div class="form-group">
          <label class="control-label" for="value_type">Type</label>
          <div class="multi-select-full">
            {!! Form::select('value_type',['percent' => 'Percent','amount' => 'Amount'],$tutorCommission->value_type,['class' => 'form-control','v-model' => 'value_type']) !!}
          </div>
          <span class="help-block">You can pay in percentage of units or in flat amount<span>
          </div>


          <div class="form-group" v-if="value_type == 'percent'">
          <div class="multi-select-full">
           {!! BootForm::text('Min Enrollment', 'min_enrollment')
                          ->value(Input::old('min_enrollment',$tutorCommission->min_enrollment))
                          ->type('number')
           !!}
          </div>
          <span class="help-block">
          If min enrollment is 0, tutor will be paid as many students enrolled in the unit. If min enrollment is 4, then tutor will be paid atleast for 4 learners enrollment.
          <span>
          </div>

          <div class="form-group" v-if="value_type == 'amount'">
          <label class="control-label" for="apply_value_to">Apply To</label>
          <div class="multi-select-full">
            {!! Form::select('apply_value_to',['per_unit' => 'Per Unit','per_student' => 'Per Student/Per Unit'],$tutorCommission->apply_value_to,['class' => 'form-control','v-model' => 'apply_value_to']) !!}
          </div>
          <span class="help-block">
          Do you want pay amount per unit or per student per unit?
          <span>
          </div>

          <div class="form-group">
          <div class="multi-select-full">
           {!! BootForm::text('Value', 'value')
                          ->placeholder('Value..')
                          ->value(Input::old('value',$tutorCommission->value))
                          ->type('number')
           !!}
          </div>
          <span class="help-block">You can pay in percentage of units or in flat amount. Note that this is per unit (2 hours session), not per class.<span>
          </div>


          <div>
              <input type="submit" value="Update Commission" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
          </div>

          {!! BootForm::close() !!}

        </div>
      </div>
    


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

<script type="text/javascript" src="https://unpkg.com/vue"></script>

<script type="text/javascript">

  var app = new Vue({
    el: '#app',
    data: {
      value_type:'{!! $tutorCommission->value_type !!}',
      apply_value_to:'{!! $tutorCommission->apply_value_to !!}'
    }

  });

  $(document).ready(function(){

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

  });

</script>

@stop
