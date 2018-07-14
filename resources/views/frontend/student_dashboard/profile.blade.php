@extends('frontend.student_dashboard.master')

@section('subcontent')

<div class="panel panel-default">
  <div class="panel-heading">
  Classes in Session
  <span class="badge">{{ $inSessionClasses->count() }}</span>
  </div>
  <div class="panel-body pad-20">
   	
   	@foreach($inSessionClasses as $ahamClass)
		@include('frontend.student_dashboard._class_item')
   	@endforeach

  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
  Upcoming Classes 
  <span class="badge">{{ $upcomingClasses->count() }}</span>
  
  </div>
  <div class="panel-body pad-20">
   	
   	@foreach($upcomingClasses as $ahamClass)
		@include('frontend.student_dashboard._class_item')
   	@endforeach

  </div>
</div>

@stop