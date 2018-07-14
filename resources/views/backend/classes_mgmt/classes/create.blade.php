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
				<li class="active">Create New Class</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Create a New Class</h5>
	</div>

	<div class="panel-body">

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::classes_mgmt::classes.store')) !!}

		{!! BootForm::select('Topic *', 'topic_id')
					->options($topics)
					->attribute('required','true')
					->attribute('id','topic_id')
		!!}

		{!! BootForm::select('Location *', 'location_id')
					->options(['' => ''] + $locations->toArray())
					->attribute('required','true')
					->attribute('id','location_id')
					->attribute('data-placeholder','Select a Location')
		!!}


        {!! BootForm::select('Grade', 'grades[]')
                      ->options([
                            '5-7' => 'Middle School(Grade 5-8)',
                            '8-10' => 'High School(Grade 9-12)',
                            'Under_Grad' => 'Under Grad',
                            'Grad_or_Higher' => 'Grad or Higher',
                            'Working_Professional' => 'Working Professional',
                            'other' => 'Other',
                        ])
                      ->attribute('id','grade')
                      ->attribute('multiple','true') 
                      ->attribute('class','form-control multiselect')
                      ->placeholder('Grade') !!}

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="free">
		</label>
		<div class="col-sm-8 col-lg-10">
		
			<label class="checkbox-inline">
				<input type="checkbox" class="styled" name="free" value="yes">
				This Class is Free
			</label>

		</div>
		</div>

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="no_tutor_comp">
		</label>
		<div class="col-sm-8 col-lg-10">
		
			<label class="checkbox-inline">
				<input type="checkbox" class="styled" name="no_tutor_comp" value="yes">
				No compensation for Tutor
			</label>

		</div>
		</div>

		{!! BootForm::text('Maximum Days *', 'maximum_days')
              ->placeholder('Maximum Days') !!}

  		{!! BootForm::select('Scheduling Rule *', 'scheduling_rule_id')
					->options([])
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

@section('styles')
@parent
<style type="text/css">
.btn-group{
	width: 100%;
}
</style>
@stop



@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){

	$('#topic_id').select2();
	$('#location_id').select2({
        minimumResultsForSearch: Infinity
    });

	// $('#location_id').multiselect({
	// 	nonSelectedText: "Select Location",
	// 	buttonClass: 'btn btn-default btn-xs btn-block text-left',
	// 	buttonContainer: '<div class="btn-group" style="width:100%;background:white"></div>',
	//     onChange: function() {
	//         $.uniform.update();
	//     }
	// });

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

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


	$('#topic_id').on('change',function(){

		//Fetch available slots

		$.get('/api/get_topic_rules/'+$(this).val(), function(data){

			// console.log(data);

			var slot_div = $('#scheduling_rule_id');

	        slot_div.empty();

            var option = document.createElement("option");

            option.text = 'Select a scheduling Rule';
            option.value = '';

            slot_div.append(option);


	        $.each(data, function(index,value){

	        	// console.log(index,value);

	            var option = document.createElement("option");

	            option.text = value;
	            option.value = index;

	            slot_div.append(option);

	        });

		});

		$.get('/api/get_topic_details/'+$(this).val(), function(data){

			$('#minimum_enrollment').val(data.minimum_enrollment);
			$('#maximum_enrollment').val(data.maximum_enrollment);

		});

	});

	$('#topic_id').trigger('change');

});
</script>

@stop