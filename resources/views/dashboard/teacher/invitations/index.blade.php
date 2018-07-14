@extends('dashboard.teacher.layouts.master')

@section('content')


<div class="row">
	@if($pendingInvitations->count())
	@foreach($pendingInvitations as $pendingInvite)
	<div class="col-md-6">
		@include('dashboard.teacher.snippets._pending_invite')
	</div>
	@endforeach
	@else
	<h3 class="text-center">No Invitations</h3>
	@endif
</div>


@stop