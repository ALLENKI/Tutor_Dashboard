@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.invitations._tabs')

<div class="row">
	@if($pendingInvitations->count())
	@foreach($pendingInvitations as $pendingInvite)
	<div class="col-md-6">
		@include('dashboard.teacher.snippets._pending_invite')
	</div>
	@endforeach
	@else
	<div class="alert bg-info text-center col-md-6 col-md-offset-3" style="margin-top:100px">
		<span class="text-semibold" style="font-size: 16px;">
			You have no pending invitations
		</span>
    </div>
	@endif
</div>


@stop