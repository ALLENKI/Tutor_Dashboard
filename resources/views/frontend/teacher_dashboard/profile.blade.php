@extends('frontend.teacher_dashboard.master')

@section('subcontent')

<h2>Upcoming Classes</h2>

<hr>

@if($teacher->classes->count())

@foreach($teacher->classes as $ahamClass)
	@include('frontend.search._class_item')
@endforeach

@else
	<div role="alert" class="alert alert-danger">You have no upcoming classes</div>
@endif


@stop