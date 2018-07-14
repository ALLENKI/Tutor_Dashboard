@extends('dashboard.teacher.layouts.master')

@section('content')

<div class="row">

	<div class="col-md-6">
	
		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong>On Going Classes</strong></h6>
			</div>

			<div class="panel-body">

				@if($ongoingClasses->count())

					@foreach($ongoingClasses as $ongoingClass)
						@include('dashboard.teacher.snippets._ongoing_class')
					@endforeach	

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no new classes.</h4>
				@endif

			</div>
			
		</div>

	</div>


	<div class="col-md-6">

		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong>Upcoming Classes</strong></h6>
			</div>

			<div class="panel-body">
			
				@if($upcomingClasses->count())

					@foreach($upcomingClasses as $upcomingClass)
						@include('dashboard.teacher.snippets._upcoming_class')
					@endforeach	

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no upcoming classes.</h4>
				@endif

			</div>
			
		</div>

	</div>
</div>

@stop