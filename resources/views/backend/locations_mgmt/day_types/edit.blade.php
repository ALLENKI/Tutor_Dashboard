@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Locations Mgmt</span> - Day Types</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::locations_mgmt::day_types.index') }}">Day Types</a></li>
				<li class="active">Day Types - {{ $day_type->name }}</li>
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
		<h5 class="panel-title">Edit Day Type - {{ $day_type->name }}</h5>

		<div class="heading-elements">
			<a href="{{ route('admin::locations_mgmt::day_types.delete',$day_type->id) }}" data-method="DELETE" class="btn btn-danger heading-btn rest">Delete</a>
		</div>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::locations_mgmt::day_types.update',$day_type->id)) !!}

		{!! BootForm::bind($day_type) !!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

      	{!! BootForm::text('Identifier *', 'slug')
              ->placeholder('Identifier')
              ->attribute('required','false') !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Manage Time Slots ({{ $day_type->slots->count() }})</h5>
	</div>

	<div class="panel-body">

	<div class="row">
		<div class="col-md-6">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::locations_mgmt::day_types.add_slot')) !!}

		{!! BootForm::hidden('day_type_id')->attribute('value',$day_type->id) !!}

        
		{!! BootForm::text('Start Time *', 'start_time')
		              ->placeholder('Start Time')
		              ->attribute('id','start_time')
		              ->attribute('required','true') !!}

        {!! BootForm::text('End Time *', 'end_time')
		              ->placeholder('End Time')
		              ->attribute('id','end_time')
		              ->attribute('readonly','true')
		              ->attribute('required','true') !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
		</div>

		<div class="col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th>Start Time</th>
						<th>End Time</th>
						<th class="text-center" width="10%">Actions</th>
					</tr>
				</thead>

				<tbody>
					@foreach($day_type->slots as $slot)
					<tr>
						<td>{{ $slot->start_time }}</td>
						<td>{{ $slot->end_time }}</td>
						<td class="text-center">
							<a href='{{ route('admin::locations_mgmt::day_types.delete_slot',$slot->id) }}' data-method='DELETE' class="rest"><i class='icon-cross' style="color:red"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	</div>
</div>
@stop

@section('scripts')
@parent

<script type="text/javascript">


$(document).ready(function(){

    $("#start_time").AnyTime_picker({
        format: "%H:%i"
    });

    $("#start_time").on('change',function(){
    	var end_time = moment(this.value,'HH:mm').add(2,'h').format('HH:mm');
    	$('#end_time').val(end_time);
    });

});
</script>

@stop