@extends('dashboard.teacher.layouts.master')

@section('content')
	
	<div class="panel panel-default">

		<div class="panel-heading">
			<h6 class="panel-title" style="font-size: 18px;"><strong>My Payments</strong></h6>
		</div>

	</div>



<div class="page-header page-header-inverse has-cover">
	<div class="page-header-content">
		<div class="page-title" style="padding: 22px 36px 22px 0;">
<!-- Quick stats boxes -->
		<div class="row">
			<div class="col-lg-4">

				<!-- Members online -->
				<div class="panel bg-teal-400">
					<div class="panel-body">
						<h3 class="no-margin">Rs. {{ $teacher->allEarnings->sum('actual_amount') }}/-</h3>
						<span style="font-size:14px;">Total amount received so far</span>
					</div>

					<div class="container-fluid">
						<div id="members-online"></div>
					</div>
				</div>
				<!-- /members online -->

			</div>

			<div class="col-lg-4">

				<!-- Current server load -->
				<div class="panel bg-pink-400">
					<div class="panel-body">
						
						<h3 class="no-margin">Rs. {{ inrFormat($teacher->earnings-$teacher->allEarnings->sum('actual_amount')) }}/-</h3>

						<span style="font-size:14px;">Pending payout amount</span>
					</div>

					<div id="server-load"></div>
				</div>
				<!-- /current server load -->

			</div>

			<div class="col-lg-4">

				<!-- Today's revenue -->
				<div class="panel bg-blue-400">
					<div class="panel-body">
						<h3 class="no-margin">Rs. {{ inrFormat($projectedAmount) }}/-</h3>
						<span style="font-size:14px;">Projected amount</span>
					</div>

					<div id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->

			</div>
		</div>
		<!-- /quick stats boxes -->
		</div>
	</div>
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
		<h5 class="panel-title">Payment History</h5>
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
				<td><a href="{{ route('teacher::class.show',$class->code) }}">{{ $class->code }}</a></td>
				<td>Rs.{{ inrFormat($class->teacher_amount) }}/-</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>


@stop


@section('styles')
@parent

<style type="text/css">

</style>

@stop

@section('scripts')
@parent

@stop