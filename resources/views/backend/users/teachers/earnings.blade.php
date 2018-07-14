@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Teachers</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.show',$teacher->user->id) }}">{{$teacher->user->name}}</a></li>
				<li class="active">Teacher</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop


@section('content')

<div class="panel panel-success">
	<div class="panel-heading">
		<h5 class="panel-title">Add Earning</h5>
	</div>

	<?php 

		$columnSizes = [
		  'sm' => [4, 8],
		  'lg' => [2, 10]
		];

	?>

	{!! BootForm::openHorizontal($columnSizes)->action(route('admin::users::teachers.add_payout',$teacher->id)) !!}


	<div class="panel-body">

		{!! 

			BootForm::text('Paid On *', 'paid_on')
		              ->placeholder('Date')
		              ->attribute('class','form-control paid_on')
		              ->attribute('id','unit_date')
		              ->attribute('required','true') 
		!!}

		{!! 

			BootForm::text('Mode *', 'mode')
		              ->attribute('required','true') 
		              ->attribute('value',Input::old('mode','cheque'))
		!!}

		{!! 

			BootForm::text('Cheque No *', 'cheque_no')
		              ->attribute('required','true') 
		!!}

		{!! 

			BootForm::text('Actual Amount *', 'actual_amount')
		              ->attribute('required','true') 
		!!}

		{!! 

			BootForm::text('Tax *', 'tax')
		              ->attribute('required','true') 
		!!}

		{!! 

			BootForm::text('Final Amount *', 'amount')
		              ->attribute('required','true') 
		!!}

		{!! 

			BootForm::textarea('Remarks *', 'remarks')
		              ->attribute('rows',3) 
		!!}
		
	</div>

	<div class="panel-footer">
		
		<div class="text-right">
			<button type="submit" class="btn btn-primary">Add <i class="icon-arrow-right14 position-right"></i></button>
		</div>

	</div>

	{!! BootForm::close() !!}

</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h5 class="panel-title">Earnings</h5>
	</div>

	<table class="table">
		<thead>
			<tr>
				<th>Date</th>
				<th>Actual Amount</th>
				<th>Tax</th>
				<th>Final Amount</th>
				<th>Mode</th>
				<th>Cheque No</th>
				<th>Remarks</th>
			</tr>
		</thead>

		<tbody>
			@foreach($teacher->allEarnings as $earning)
			<tr>
				<td>{{ $earning->paid_on }}</td>
				<td>Rs.{{ inrFormat($earning->actual_amount) }}/-</td>
				<td>Rs.{{ inrFormat($earning->tax) }}/-</td>
				<td>Rs.{{ inrFormat($earning->amount) }}/-</td>
				<td>{{ $earning->mode }}</td>
				<td>{{ $earning->cheque_no }}</td>
				<td>{{ $earning->remarks }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h5 class="panel-title">Completed Classes</h5>
	</div>

	<table class="table">
		<thead>
			<tr>
				<th>Class Code</th>
				<th>Amount</th>
			</tr>
		</thead>

		<tbody>
			@foreach($teacher->completedClasses as $class)
			<tr>
				<td>{{ $class->code }}</td>
				<td>Rs.{{ inrFormat($class->teacher_amount) }}/-</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@stop

@section('scripts')
@parent
<script type="text/javascript">
	$(function(){


	    $('#unit_date').daterangepicker({ 
	    	drops: 'up',
	        singleDatePicker: true,
			locale: {
		      format: 'DD-MM-YYYY'
		    },
	    }).trigger('change');

	});
</script>
@stop