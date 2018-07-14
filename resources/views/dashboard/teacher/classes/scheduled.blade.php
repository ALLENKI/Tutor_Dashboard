<!-- @extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.classes._tabs')

<div class="row">
	

	<div class="col-md-12">

		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong>Scheduled Classes</strong></h6>
			</div>

			<div class="panel-body">
			
				@if($scheduledClasses->count())

					@foreach($scheduledClasses as $scheduledClass)
						@include('dashboard.teacher.snippets._scheduled_class')
					@endforeach	

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no upcoming classes.</h4>
				@endif

			</div>
			
		</div>

	</div>

</div>

@stop -->