@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Classes</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::classes.index') }}">Classes</a></li>
				<li><a href="{{ route('admin::classes_mgmt::classes.show',$class->id) }}">{{ $class->code }}</a></li>
				<li class="active">Edit</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Edit Class - {{ $class->code }}</h5>
	</div>

	<div class="panel-body">

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::classes_mgmt::classes.update',$class->id)) !!}

		{!! BootForm::bind($class) !!}

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="no_tutor_comp">
		</label>
		<div class="col-sm-8 col-lg-10">
		
			<label class="checkbox-inline">
				<input type="checkbox" class="styled" name="no_tutor_comp" value="yes" {{ $class->no_tutor_comp ? 'checked' : '' }}>
				No compensation for Tutor
			</label>

		</div>
		</div>

		{!! BootForm::text('Maximum Days *', 'maximum_days')
              ->placeholder('Maximum Days') !!}

        {!! BootForm::select('Grade', 'grades[]')
                      ->options([
                            '5-8' => 'Middle School(Grade 5-8)',
                            '9-12' => 'High School(Grade 9-12)',
                            'Under_Grad' => 'Under Grad',
                            'Grad_or_Higher' => 'Grad or Higher',
                            'Working_Professional' => 'Working Professional',
                            'other' => 'Other',
                        ])
                      ->select(explode(',', $class->grades))
                      ->attribute('id','grade')
                      ->attribute('multiple','true') 
                      ->placeholder('Grade') !!}

  		{!! BootForm::select('Scheduling Rule *', 'scheduling_rule_id')
					->options($scheduling_rules)
					->attribute('required','true')
					->attribute('id','scheduling_rule_id')
		!!}

		{!! BootForm::text('Minimum Enrollment *', 'minimum_enrollment')
              ->placeholder('Minimum Enrollment')
              ->attribute('id','minimum_enrollment')
       !!}

  		{!! BootForm::text('Maximum Enrollment *', 'maximum_enrollment')
              ->placeholder('Maximum Enrollment')
              ->attribute('id','maximum_enrollment')
       !!}


		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){


    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });

	$('#grade').multiselect({
		nonSelectedText: "Select grades",
		buttonClass: 'btn btn-default btn-xs btn-block text-left',
		buttonContainer: '<div class="btn-group" style="width:100%;background:white"></div>',
	    onChange: function() {
	        $.uniform.update();
	    }
	});

});
</script>

@stop