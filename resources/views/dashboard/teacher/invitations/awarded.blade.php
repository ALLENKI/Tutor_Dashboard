@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.invitations._tabs')

<div class="row">
	@if($awardedInvitations->count())
	@foreach($awardedInvitations as $awardedInvite)
	<div class="col-md-6">
		@include('dashboard.teacher.snippets._awarded_invite')
	</div>
	@endforeach
	@else
	<div class="alert bg-info text-center col-md-6 col-md-offset-3" style="margin-top:100px">
		<span class="text-semibold" style="font-size: 16px;">
			No classes are awarded to you
		</span>
    </div>
	@endif
</div>


@stop