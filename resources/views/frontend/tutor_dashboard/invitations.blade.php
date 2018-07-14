@extends('frontend.tutor_dashboard.master')

@section('subcontent')


<div class="panel panel-default">
  <div class="panel-heading">Pending Invitations</div>
  <div class="panel-body">
	  	@if($pendingInvitations->count())
		
		@foreach($pendingInvitations as $invitation)
			@include('frontend.teacher_dashboard.invitations._invite')
		@endforeach

		@else
		<div role="alert" class="alert alert-danger">You have no pending invitations</div>
		@endif
  </div>
</div>


<div class="panel panel-default">
  <div class="panel-heading">Accepted Invitations</div>
  <div class="panel-body">
	@if($acceptedInvitations->count())

	@foreach($acceptedInvitations as $invitation)
		@include('frontend.teacher_dashboard.invitations._invite')
	@endforeach

	@else
	<div role="alert" class="alert alert-danger">You have no accepted invitations</div>
	@endif
  </div>
</div>


@stop