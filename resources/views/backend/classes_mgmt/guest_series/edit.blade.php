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
				<li class="active">Edit Guest Series - {{ $guestSeries->name }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Edit Guest Series - {{ $guestSeries->name }}</h5>
	</div>

	<div class="panel-body">

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::classes_mgmt::guest_series.update',$guestSeries->id)) !!}

		{!! BootForm::bind($guestSeries) !!}

		{!! BootForm::select('Location *', 'location_id')
					->options(['' => ''] + $locations->toArray())
					->attribute('required','true')
					->attribute('id','location_id')
					->select($guestSeries->location->id)
					->attribute('data-placeholder','Select a Location')
		!!}


		{!! BootForm::text('Name of the Series *', 'name')
              ->placeholder('Name of the Series')
              ->attribute('required',true)
              ->attribute('value',$guestSeries->name) !!}


		{!! BootForm::text('Cost Per Episode *', 'cost_per_episode')
              ->placeholder('Cost Per Episode')
              ->attribute('value',$guestSeries->cost_per_episode)
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
                      ->select($guestSeries->enrollment_restriction)
                      ->attribute('data-placeholder','Select a Enrollment Restriction') !!}


        {!! BootForm::select('Enrollment User', 'enrollment_user')
                      ->options([
                      		'' => '',
                            'aham_student' => 'Aham Student',
                            'public' => 'Public',
                        ])
                      ->attribute('class','form-control')
                      ->attribute('required','true')
                      ->select($guestSeries->enrollment_user)
                      ->attribute('data-placeholder','Select a Enrollment User') !!}


        {!! BootForm::select('Enrollment Type', 'enrollment_type')
                      ->options([
                      		'' => '',
                            'per_episode' => 'Per Episode',
                            'full_series' => 'Full Series',
                        ])
                      ->attribute('class','form-control')
                      ->attribute('required','true')
                      ->select($guestSeries->enrollment_type)
                      ->attribute('data-placeholder','Select a Enrollment Type') !!}


		{!! 

			BootForm::textarea('Requirement *', 'requirement')
		              ->placeholder('Requirement')
		              ->attribute('required','true') 
		              ->attribute('rows',3) 
		!!}

		{!! 

			BootForm::textarea('Optional Information *', 'optional')
		              ->placeholder('Optional Information')
		              ->attribute('rows',3) 
		!!}

		{!! 

			BootForm::textarea('Description *', 'description')
		      ->attribute('rows',3) 
		      ->attribute('id','description') 
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

	editor = $('#description').summernote({
		defaultFontName: 'Open Sans',

		toolbar: [
		    ['style', ['style']],
		    ['font', ['bold', 'italic', 'underline', 'clear']],
		    ['fontname', ['fontname']],
		    ['fontsize', ['fontsize']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['height', ['height']],
		    ['table', ['table']],
		    ['insert', ['link', 'picture', 'hr']],
		    ['view', ['fullscreen', 'codeview']],
		    ['help', ['help']]
		  ],

		fontNames: [
			'Open Sans'
		],

		fontNamesIgnoreCheck: [
			'Open Sans'
		]

	});

});
</script>

@stop