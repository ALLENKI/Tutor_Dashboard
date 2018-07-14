@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Guest Series</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::guest_series.index') }}">Guest Series</a></li>
				<li class="active">Create New Guest Series</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Create a New Guest Series</h5>
	</div>

	<div class="panel-body">

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::classes_mgmt::guest_series.store')) !!}

		{!! BootForm::select('Location *', 'location_id')
					->options(['' => ''] + $locations->toArray())
					->attribute('required','true')
					->attribute('id','location_id')
					->attribute('data-placeholder','Select a Location')
		!!}


		{!! BootForm::text('Name of the Series *', 'name')
              ->placeholder('Name of the Series')
              ->attribute('required',true) !!}

		{!! BootForm::text('Name of Level 1 *', 'level_1')
              ->placeholder('Name of Level 1')
              ->attribute('required',true) !!}

		{!! BootForm::text('Cost Per Episode *', 'cost_per_episode')
              ->placeholder('Cost Per Episode')
              ->attribute('required',true) !!}

        {!! BootForm::select('Enrollment Restriction', 'enrollment_restriction')
                      ->options([
                      		'' => '',
                            'none' => 'None',
                            'restrict_by_level' => 'Restrict By Level',
                            'restrict_by_episode' => 'Restrict By Episode'
                        ])
                      ->attribute('class','form-control')
                      ->attribute('required','true')
                      ->attribute('data-placeholder','Select a Enrollment Restriction') !!}


        {!! BootForm::select('Enrollment User', 'enrollment_user')
                      ->options([
                      		'' => '',
                            'aham_student' => 'Aham Student',
                            'public' => 'Public',
                            'student_public' => 'Aham Student and Public'
                        ])
                      ->attribute('class','form-control')
                      ->attribute('required','true')
                      ->attribute('data-placeholder','Select a Enrollment User') !!}


        {!! BootForm::select('Enrollment Type', 'enrollment_type')
                      ->options([
                      		'' => '',
                            'per_episode' => 'Per Episode',
                            'full_series' => 'Full Series',
                            'per_level' => 'Per Level',
                        ])
                      ->attribute('class','form-control')
                      ->attribute('required','true')
                      ->attribute('data-placeholder','Select a Enrollment Type') !!}

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

	$('select').select2();
	// $('#location_id').select2({
 //        minimumResultsForSearch: Infinity
 //    });

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