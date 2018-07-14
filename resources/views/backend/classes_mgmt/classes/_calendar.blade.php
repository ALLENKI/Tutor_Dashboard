@if($ahamClass->status == 'initiated')
<div class="panel panel-warning">

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-calendar position-left"></i> Calendar</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">

	@if($ahamClass->classUnits->count())
	<table class="table table-framed">

		<thead>
			<tr>
				<th>Unit</th>
				<th>Date & Time</th>
				<th>Classroom</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
			@foreach($ahamClass->classUnits as $unit)
			<tr>
				<td>
					{{ $unit->name }}
				</td>
				<td>
					{{ schedule($unit->original_unit_id,$ahamClass->id) }}
				</td>
				<td>
					NA
				</td>

				<td>
					@if(Aham\Interactions\ClassSchedule::eligibleToSchedule($ahamClass, $unit->unit))
					<button data-url='{{ route('admin::classes_mgmt::classes.schedule_modal', [$ahamClass->id, $unit->original_unit_id]) }}' class='btn btn-success btn-xs' onclick='openAjaxModal(this);'>Schedule</button>
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>

	</table>
	@else
		<h3 class="text-center">This topic has no units.</h3>		
	@endif


	</div>

</div>
@endif


@if($ahamClass->status != 'initiated' && $ahamClass->status != 'cancelled')
<div class="panel panel-warning">

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-calendar position-left"></i> Calendar</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">

	@if($ahamClass->classUnits->count())
	<table class="table table-framed">

		<thead>
			<tr>
				<th>Unit</th>
				<th>Date & Time</th>
				<th>Classroom</th>
				<th>Status</th>
				
			</tr>
		</thead>

		<tbody>
			@foreach($ahamClass->classUnits as $unit)
			<?php $timing = classTimingStatus($unit->original_unit_id,$ahamClass->id); ?>
			@if(!is_null($timing))
			<tr>
				<td>
					{{ $unit->name }}
				</td>
				<td>
					{{ schedule($unit->original_unit_id,$ahamClass->id) }}
				</td>
				<td>
					@if($ahamClass->status == 'scheduled')
						@if($timing->classroom)
							{{ $timing->classroom->name }}
							(
							<small>
							<span class="cursor-pointer" data-url='{{ route('admin::classes_mgmt::classes.assign_classroom', [$ahamClass->id, $unit->original_unit_id]) }}' onclick='openAjaxModal(this);'>
							Change
							</span>
							</small>
							)
						@else
						<button data-url='{{ route('admin::classes_mgmt::classes.assign_classroom', [$ahamClass->id, $unit->original_unit_id]) }}' class='btn btn-success btn-xs' onclick='openAjaxModal(this);'>
						Assign Classroom
						</button>
						@endif
					@else
						@if($timing->classroom)
						{{ $timing->classroom->name }}
						@else
						NA
						<button data-url='{{ route('admin::classes_mgmt::classes.assign_classroom', [$ahamClass->id, $unit->original_unit_id]) }}' class='btn btn-success btn-xs' onclick='openAjaxModal(this);'>
						Assign Classroom
						</button>
						@endif
					@endif
				</td>
				<td>
					
					@if($timing->end_time->isPast() && $timing->status != 'done' && $ahamClass->status == 'in_session')
					<button data-url='{{ route('admin::classes_mgmt::classes.unitdone_modal', [$ahamClass->id, $unit->original_unit_id]) }}' class='btn btn-success btn-xs' onclick='openAjaxModal(this);'>Mark as Done</button>
					@else
						@if($timing->status == 'pending')
						<span class="label label-danger">{{ $timing->status }}</span>
						@else
						<span class="label label-success">{{ $timing->status }}</span>
						@endif
					@endif
				</td>

			</tr>
			@endif
			@endforeach
		</tbody>

	</table>
	@else
			<h3 class="text-center">This topic has no units.</h3>		
	@endif


	</div>

</div>
@endif