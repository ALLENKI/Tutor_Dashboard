@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Group Classes</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::classes.index') }}">Classes</a></li>
				<li class="active">Create Group Class</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<div class="panel panel-white">
	<div class="panel-heading">
		<h5 class="panel-title">Choose a Goal</h5>
	</div>

	<div class="panel-body">

		{!! BootForm::open()->attribute('method','GET') !!}


		{!! BootForm::select('Goal *', 'goal')
					->options(['' => ''] + $goals->toArray())
					->attribute('required','true')
					->attribute('id','goal_id')
					->select(Input::get('goal'))
		!!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Go <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
</div>
</div>

<div class="panel panel-white">
	<div class="panel-heading">
		<h5 class="panel-title">Create Classes</h5>
	</div>

	<div class="panel-body">


		@if(!is_null($topics))
		{!! BootForm::open()->action(route('admin::classes_mgmt::group_classes.store',['goal' => Input::get('goal')])) !!}

		<div class="form-group">
			<label>Group Name</label>
			<input type="text" name="name" class="form-control" required>
		</div>


		<div class="row" style="margin:20px 0">
			<div class="col-md-3">
				{!! 
					BootForm::select('Location *', "fill_location_id")
							->options($locations->toArray())
							->attribute('class','form-control location_id')
							->attribute('id','fill_location_id')
							->attribute('data-placeholder','Select a Location')
				!!}
			</div>

			<div class="col-md-1">
				<input type="checkbox" class="styled" name="fill_free" value="yes" id="fill_free">
				Free
			</div>

			<div class="col-md-1">
				<input type="checkbox" class="styled" name="no_tutor_comp" value="yes" id="fill_no_tutor_comp">
				No Tutor Comp
			</div>

			<div class="col-md-1">
				{!! BootForm::text('Max Days *', "fill_maximum_days")
							->attribute('id','fill_maximum_days')
              				->placeholder('Maximum Days') !!}
			</div>

			<div class="col-md-2">
				<button type="button" class="btn btn-primary" id="prefill"> Fill</button>
			</div>
		</div>


		<table class="table table-responsive">
			<thead>
				<th></th>
				<th>Create</th>
				<th>Location</th>
				<th>Free</th>
				<th>No Tutor Compensation</th>
				<th>Grade</th>
				<th>Max.Days</th>
				<th>Scheduling Rule</th>
				<th>Min. Max. Enrollment</th>
			</thead>

			<tbody>

		        <?php 
		          $showLevel = 5;
		          $show = true;
		          $totalUnits = 0;
		        ?>

				@foreach($goal_topics as $index => $goal_topic)


		        <?php 
		          if($showLevel != $goal_topic->graph_level)
		          {
		              $showLevel = $goal_topic->graph_level;
		              $show = true;
		          }
		          else
		          {
		              $show = false;
		          }

		          if($index == 0)
		          {
		            $show = true;
		          }

		          $totalUnits += $goal_topic->units->count();
		        ?>

		        @if($show)
		        <tr style="text-align: center;">
		          <td colspan="9"> <strong> Level {{ $goal_topic->graph_level }} </strong></td>
		        </tr>
		        @endif
		        <?php $topic = $goal_topic; ?>
				<tr id="topic_{{$topic->id}}">
					<td>
						<input type="hidden" name="create[{{$topic->id}}]" value="{{$topic->id}}">
						<span style="cursor: pointer;" onclick="removeRow('{{ $topic->id }}')"><i class="icon-cross2"></i></span>
					</td>

					<td>
						{{ $topic->name }}
					</td>

					<td>
						{!! 
							BootForm::select('Location *', "group[$topic->id][location_id]")
									->options($locations->toArray())
									->attribute('class','form-control location_id')
									->hideLabel()
									->attribute('data-placeholder','Select a Location')
						!!}
					</td>

					<td>
						<input type="checkbox" class="free" name="group[{{$topic->id}}][free]" value="yes">
					</td>

					<td>
						<input type="checkbox" class="no_tutor_comp" name="group[{{$topic->id}}][no_tutor_comp]" value="yes">
					</td>

					<td>
		        	{!! BootForm::select('Grade', "group[$topic->id][grades][]")
                      ->options([
                            '5-7' => 'Middle School(Grade 5-8)',
                            '8-10' => 'High School(Grade 9-12)',
                            'Under_Grad' => 'Under Grad',
                            'Grad_or_Higher' => 'Grad or Higher',
                            'Working_Professional' => 'Working Professional',
                            'other' => 'Other',
                        ])
                      ->hideLabel()
                      ->attribute('multiple','true') 
                      ->attribute('class','form-control multiselect grade')
                      ->placeholder('Grade') !!}
					</td>

					<td>
						
						{!! BootForm::text('Maximum Days *', "group[$topic->id][maximum_days]")
							->hideLabel()
							->attribute('class','maximum_days form-control')
              				->placeholder('Maximum Days') !!}
					</td>

					<td>
				  		{!! BootForm::select('Scheduling Rule *', "group[$topic->id][scheduling_rule_id]")
									->options($schedulingRules[$topic->units->count()]['options'])
									->attribute('class','form-control scheduling_rule_id')
									->attribute('placeholder','Scheduling Rule')
									->select($schedulingRules[$topic->units->count()]['selected'])
									->hideLabel()
						!!}
					</td>

					<td>
						<div class="form-group">
							<input type="text" name="group[{{$topic->id}}][minimum_enrollment]" value="{{ $topic->minimum_enrollment }}" class="form-control">
							<input type="text" name="group[{{$topic->id}}][maximum_enrollment]" value="{{ $topic->maximum_enrollment }}" class="form-control">
						</div>
					</td>

				</tr>

				@endforeach

			</tbody>

		</table>

		<hr>

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Create <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
		@endif

	</div>

</div>
@stop

@section('styles')
@parent
<style type="text/css">
.form-group{
	margin-bottom: 0px;
}
</style>
@stop


@section('scripts')
@parent

<script type="text/javascript">

function removeRow(id)
{
	$('#topic_'+id).remove();
}

$(document).ready(function(){

	$('#goal_id').select2();

	// $(".scheduling_rule_id").prop("selectedIndex", -1);
	$(".location_id").prop("selectedIndex", -1);

	$('#prefill').on('click',function()
	{
		$('.location_id').val($('#fill_location_id').val());
		$('.free').prop('checked',$('#fill_free').prop('checked'));
		$('.no_tutor_comp').prop('checked',$('#fill_no_tutor_comp').prop('checked'));
		$('.maximum_days').val($('#fill_maximum_days').val());
	});

	// $('.location_id').select2({
 //        minimumResultsForSearch: Infinity
 //    });

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });

	$('.grade').multiselect({
		nonSelectedText: "Select grades",
		buttonClass: 'btn btn-default btn-xs btn-block text-left',
		buttonContainer: '<div class="btn-group" style="width:100%;background:white"></div>',
	    onChange: function() {
	        $.uniform.update();
	    }
	});

    $('.create').change(function() {

    	console.log($(this).val());

        if($(this).is(":checked")) {
            console.log('checked');
            $('input[name="group['+$(this).val()+'][minimum_enrollment]"]').prop('required', true);
            $('input[name="group['+$(this).val()+'][maximum_enrollment]"]').prop('required', true);
            $('input[name="group['+$(this).val()+'][maximum_days]"]').prop('required', true);
            $('select[name="group['+$(this).val()+'][scheduling_rule_id]"]').prop('required', true);
            $('select[name="group['+$(this).val()+'][location_id]"]').prop('required', true);
        }
        else
        {
        	console.log('not checked');
            $('input[name="group['+$(this).val()+'][minimum_enrollment]"]').prop('required', false);
            $('input[name="group['+$(this).val()+'][maximum_enrollment]"]').prop('required', false);
            $('input[name="group['+$(this).val()+'][maximum_days]"]').prop('required', false);
            $('select[name="group['+$(this).val()+'][scheduling_rule_id]"]').prop('required', false);
            $('select[name="group['+$(this).val()+'][location_id]"]').prop('required', false);
        }       
    });

});

</script>

@stop