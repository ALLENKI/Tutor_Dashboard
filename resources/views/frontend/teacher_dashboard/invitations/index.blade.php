@extends('frontend.teacher_dashboard.master')

@section('subcontent')

<h2>Invitations</h2>

<hr>


<div class="events">
  <div class="event-table">

  	<h3>Pending Invitations</h3>

  	@if($pendingInvitations->count())
	
	@foreach($pendingInvitations as $invitation)
		@include('frontend.teacher_dashboard.invitations._invite')
	@endforeach

	@else
	<div role="alert" class="alert alert-danger">You have no pending invitations</div>
	@endif

	@if($acceptedInvitations->count())

	@foreach($acceptedInvitations as $invitation)
		@include('frontend.teacher_dashboard.invitations._invite')
	@endforeach

	@endif

  </div>
</div>


@stop


@section('scripts')
@parent
<script>
$(document).ready(function(){
// Target your .container, .wrapper, .post, etc.
	$(".google-maps-container").fitVids();
});
</script>
@stop