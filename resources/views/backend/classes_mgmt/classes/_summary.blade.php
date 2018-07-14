<?php

	$progress = ($ahamClass->timings()->where('status','done')->count()/$ahamClass->topic->units()->count())*100;

?>

<div class="content-group-sm">

	<p class="text-semibold">Class Progress</p>

	<div class="progress">
		<div class="progress-bar bg-success-400" style="width: {{$progress}}%">
			<span>{{$progress}}% Complete</span>
		</div>

		<div class="progress-bar progress-bar-danger" style="width: {{100-$progress}}%">
			<span>{{100-$progress}}% Pending</span>
		</div>
	</div>

</div>

<ul>
	<li><strong>Status:</strong> {{ strtoupper($ahamClass->status) }}</li>

	<li>
	<strong>Class:</strong> 
	{{ $ahamClass->code }}
	</li>

	<li>
	<strong>Topic:</strong> 
	{{ $ahamClass->topic->name }} - 
	<a href="{{ url('classes-in-'.$ahamClass->topic->slug) }}">Course Page</a>
	</li>
	<li><strong>Location:</strong><a href="{{ route('admin::locations_mgmt::locations.show',$ahamClass->location->id) }}" target="_blank"> {{ $ahamClass->location->name }} </a> </li>
	
	@if($ahamClass->start_date)
	<li>
		<strong>Start Date:</strong>
		{{ $ahamClass->start_date->format('jS M Y H:i A') }}
	</li>
	@endif

	@if($ahamClass->enrollment_cutoff)
	<li>
		<strong>Enrollment Cutoff:</strong>
		{{ $ahamClass->enrollment_cutoff->format('jS M Y H:i A') }}
		<span class="label label-primary" data-url="{{ route('admin::classes_mgmt::classes.enrollment_cutoff_modal', [$ahamClass->id]) }}" onclick='openAjaxModal(this);' style="cursor:pointer;">
		Change
		</span>
	</li>
	@endif


	@if($ahamClass->schedule_cutoff)
	<li>
		<strong>Schedule Cutoff:</strong>
		{{ $ahamClass->schedule_cutoff->format('jS M Y H:i A') }}
		<span class="label label-primary" data-url="{{ route('admin::classes_mgmt::classes.schedule_cutoff_modal', [$ahamClass->id]) }}" onclick='openAjaxModal(this);' style="cursor:pointer;">
		Change
		</span>
	</li>
	@endif

	<li>
	<strong>Free:</strong> 
	@if($ahamClass->free)
	<span class="label label-success">Yes</span>
	@else
	<span class="label label-danger">No</span>
	@endif
	</li>


	<li>
	<strong>No Compensation for Tutor :</strong> 
	@if($ahamClass->no_tutor_comp)
	<span class="label label-success">Yes</span>
	@else
	<span class="label label-danger">No</span>
	@endif
	</li>

	<li>
	<strong>Timings Chosen:</strong> 
	@if($ahamClass->isScheduled())
	<span class="label label-success">Yes</span>
	@else
	<span class="label label-danger">No</span>
	@endif
	</li>

	<li>
	<strong>Minimum Enrollment:</strong>
	{{ $ahamClass->minimum_enrollment }}
	</li>

	<li>
	<strong>Maximum Enrollment:</strong>
	{{ $ahamClass->maximum_enrollment }}
	</li>

	<li>
	<strong>Enrolled Students:</strong>
	{{ $ahamClass->enrollments->count() }}
	</li>
	<li>
	<strong>Teacher:</strong> 
	@if($ahamClass->teacher)
	{{ $ahamClass->teacher->user->name }} ({{ $ahamClass->teacher->code }})
	<br>Commission: {{  $ahamClass->commission }}%
	<br>Amount: Rs.{{  $ahamClass->teacher_amount }}/-
	<br>
	<small>(<a href="{{ route('admin::users::users.impersonate',$ahamClass->teacher->user->id) }}" target="_blank">Impersonate</a>)</small>
	
	@else
	NA
	@endif
	</li>
	<li><strong>Maximum Days:</strong> {{ $ahamClass->maximum_days }}</li>

	@if($ahamClass->schedulingRule)
	<li><strong>Scheduling Rule:</strong> {{ $ahamClass->schedulingRule->description }}</li>
	@endif
	
</ul>