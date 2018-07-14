
<div class="table-responsive" id="{{ $class->code }}">
<table class="table table-lg text-nowrap">
	<tbody>
		<tr>

			<td class="col-md-6">
		
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr class="text-center active">
							<td colspan=2>Class Code: {{ $class->code }}</td>
						</tr>
						@foreach($class->topic->units as $index => $unit)
						<tr>
							<td>{{ $unit->name }}</td>
							<td>{{ schedule($unit->id,$class->id) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</td>


			<td class="col-md-6">
				<div class="">
					@if($class->free)
					<span class="label label-info pull-right">Free</span>
					@else
					<span class="label label-primary pull-right">{{ $class->topic->units->count() }} Credits</span>
					@endif
					<div class="clearfix"></div>

					<ul class="list-inline list-inline-condensed no-margin">
						<li>
							<span class="text-muted">Tutor</span>
						</li>
					</ul>
					<h5 class="text-semibold no-margin">
						@if(!is_null($class->teacher))
						{{ $class->teacher->user->name }}
						@endif
					</h5>

					<ul class="list-inline list-inline-condensed no-margin">
						<li>
							<span class="text-muted">Location</span>
						</li>
					</ul>
					<h5 class="text-semibold no-margin">
						{{ $class->location->name }}, {{ $class->location->city->name }}
					</h5>
					

				</div>
			</td>

		</tr>
	</tbody>
</table>	
</div>