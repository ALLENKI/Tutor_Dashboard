@extends('dashboard.student.layouts.master')

@section('page-header')

@stop


@section('content')

<div class="panel panel-white">
	<div class="panel-heading">
        <h5 class="panel-title">Goal</h5>
    </div>

    <div class="panel-body">
    	
		{!! 
			BootForm::open()
					->attribute('method','GET')
					->action(Request::url())
					->attribute('id','fetch_goal')
		!!}

	      {!! BootForm::select('Goal', 'goal')
	            ->options(['' => 'Please select a goal'] + $goals)
	            ->hideLabel()
	            ->attribute('id','goal')
	            ->select(Input::get('goal',''))
	            ->placeholder('Goal') !!}

		{!! BootForm::close() !!}
    </div>
</div>


@if($goal)

<div class="panel panel-white">
	<div class="panel-heading">
        <h5 class="panel-title">Goal</h5>

        <div class="heading-elements">
        	@if(in_array($goal->id, $student_goals))
        	<a href="" class="btn btn-danger legitRipple">Added in Goals</a>
        	@else
        	<a href="{{ route('student::goals.add',$goal->id) }}" data-method="POST" class="rest btn btn-primary legitRipple">Add</a>
        	@endif
        </div>

    </div>

	<div class="panel-body">

	<div class="row">
	  <div class="col-md-12" id="mynetwork">
	    

	  </div>

	  <div class="col-md-10 col-md-offset-1">
	    <h1 id="message"></h1>
	  </div>
	</div>

	</div>

</div>


@endif

@stop


@section('scripts')
@parent

<script type="text/javascript">

$(document).ready(function(){

	// $('#goal').select2();

	$('#goal').on('change',function(){

		$('#fetch_goal').submit();

	});

});

</script>

@stop


@if($goal)
@section('styles')
@parent
<style type="text/css">
    #mynetwork {
      height: 500px;
      border: 1px solid lightgray;
      background:#d1d1d1;
    }
    p {
      max-width:600px;
    }
</style>
@stop

@include('dashboard.student.goals.graph')

@endif