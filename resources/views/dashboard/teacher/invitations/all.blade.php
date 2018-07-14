@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.invitations._tabs')

<div class="row">
	@if($allInvitations->count())
	<div class="col-md-12">
		<div class="panel panel-flat">
		<table class="table">
			<thead>
				<th>#</th>
				<th>Class</th>
				<th>Status</th>
			</thead>

			<tbody>
				@foreach($allInvitations as $invitation)
				<tr>
					<td>{{ $invitation->ahamClass->code }}</td>
					<td>{{ $invitation->ahamClass->topic->name }}</td>
					<td>{{ strtoupper($invitation->status) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		</div>
	</div>

	@else
	<div class="alert bg-info text-center col-md-6 col-md-offset-3" style="margin-top:100px">
		<span class="text-semibold" style="font-size: 16px;">
			No Invitations to show
		</span>
    </div>
	@endif

</div>


@stop