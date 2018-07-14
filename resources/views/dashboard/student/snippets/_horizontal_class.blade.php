
<div class="table-responsive" id="{{ $class->code }}">
<table class="table table-lg">
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
							<span class="text-muted">Teacher</span>
						</li>
					</ul>

					
					<h6 class="text-semibold no-margin">
					@if($class->teacher)
						{{ $class->teacher->user->name }}
					@else
						Not Assigned
					@endif
					</h6>


					<ul class="list-inline list-inline-condensed no-margin">
						<li>
							<span class="text-muted">Location</span>
						</li>
					</ul>
					<h6 class="text-semibold no-margin">
						{{ $class->location->name }}, {{ $class->location->city->name }}
					</h6>
					<hr>
					@if(!in_array($class->id, $enrollments))
					<a href="#" class="btn bg-green btn-block legitRipple"  data-url="{{ route('student::enrollment.show', [$class->id]) }}" onclick='openAjaxModal(this);'><i class="icon-statistics position-left"></i> Enroll</a>
					@else
					<button class="btn bg-blue btn-block legitRipple"><i class="icon-statistics position-left"></i> You are Enrolled</button>
					@endif

				</div>
			</td>

		</tr>
	</tbody>
</table>	
</div>