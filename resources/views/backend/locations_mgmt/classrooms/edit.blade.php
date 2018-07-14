@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Locations Mgmt</span> - Classrooms</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::locations_mgmt::locations.show',$location->id) }}">{{ $location->name }}</a></li>
				<li class="active">Edit Classroom - {{ $classroom->name }}</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Edit Classroom {{ $classroom->name }}</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::locations_mgmt::classrooms.update',$classroom->id)) !!}

		{!! BootForm::bind($classroom) !!}

        <div class="form-group">
        <label class="col-sm-4 col-lg-2 control-label" for="active">
        </label>
        <div class="col-sm-8 col-lg-10">
        
          <label class="checkbox-inline">
            <input type="checkbox" class="styled" name="active" value="yes" {{ $classroom->active ? 'checked': '' }}>
            Active
          </label>

        </div>
        </div>

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','true') !!}

        {!! BootForm::text('Size *', 'size')
              ->placeholder('Size')
              ->attribute('required','true') !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->


<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Timing Slots</h5>
	</div>

	<div class="panel-body">

		<div class="row">

			@foreach($day_types as $day_type_id => $day_type_name)

			<div class="col-md-4">

				<div class="panel panel-white">


				<div class="panel-heading">
					<h6 class="panel-title"><i class="icon-files-empty position-left"></i> {{ $day_type_name }}</h6>
				</div>

				<div class="panel-body">

				<?php $classroom_slots = $classroom->classroomSlots()->where('day_type_id',$day_type_id)->get() ?>

				@if($classroom_slots->count())

				<table class="table">
					<thead>
						<th>Timing</th>
						<th>Action</th>
					</thead>

					<tbody>

					@foreach($classroom_slots as $classroom_slot)
					<tr>
						<td>{{ $classroom_slot->start_time }} - {{ $classroom_slot->end_time }}</td>
						<td>
							<a href='{{ route('admin::locations_mgmt::classrooms.remove_slot',$classroom_slot->id) }}' data-method='DELETE' class="rest"><i class='icon-cross'></i></a>
						</td>
					</tr>
					@endforeach
						
					</tbody>
				</table>

				@else

					<h5 class="text-center"> No Slots For This Day Type</h5>

				@endif

				</div>

			</div>

			</div>

			@endforeach

			

		</div>

		<hr>

		<h5 class="panel-title content-group">Add a Slot</h5>

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::locations_mgmt::classrooms.add_slot',$classroom->id)) !!}

		{!! BootForm::select('Day Type *', 'day_type_id')
					->options($day_types)
					->attribute('required','true')
					->attribute('id','day_type_id')
		!!}

		{!! BootForm::select('Slot *', 'slot_id')
					->options([])
					->attribute('required','true')
					->attribute('id','slot_id')
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

var day_type_slots = {!! $day_type_slots !!};

$(function(){

	$('#day_type_id').on("change",function(){

		console.log('Day Type ' + this.value, day_type_slots[this.value]);

		var $slots = $("#slot_id");

		$slots.empty();

		$.each(day_type_slots[this.value],function(index,value){
			$slots.append('<option value="'+value.id+'">' + value.text + "</option>");
		});

	});

	$('#day_type_id').trigger('change');

});

</script>

@stop