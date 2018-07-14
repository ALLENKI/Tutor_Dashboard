@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.classes._tabs')

<div class="row">

	<div class="col-md-12">
	
		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong>On Going Classes</strong></h6>
			</div>

			<div class="panel-body">
                
				@if($ongoingClassesTimings->count())

					@foreach($ongoingClassesTimings as $classTiming)
						@include('dashboard.teacher.snippets._common_class_unit_snippet')
					@endforeach	

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no on-going classes.</h4>
                @endif

			</div>
			
		</div>

	</div>

</div>

@stop
