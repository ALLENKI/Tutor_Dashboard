@if($ahamClass->status == 'initiated')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<p>
			Please choose date and timings for units.  
		</p>

	</div>
</div>
@endif


@if($ahamClass->status == 'created')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<p>
			You have created the class. Now, you can invite teachers, Please note that once you invite teachers, you cannot or change class timings.  
		</p>

	</div>
</div>
@endif


@if($ahamClass->status == 'invited')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<p>
			You have sent out invitiations - One or more teachers will accept the invitiation and then you pick a teacher for the class. Let's wait! 
		</p>

	</div>
</div>
@endif

@if($ahamClass->status == 'accepted')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<p>
		{{ $ahamClass->invitations()->where('status','accepted')->count() }} teacher(s) have accepted the invitation. Now you can pick a teacher by clicking 'Award'.
		</p>

	</div>
</div>
@endif

@if($ahamClass->status == 'ready_for_enrollment')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<p>
		Awesome! You have reached the 1st milestone. Your class is ready for enrollment, but you need to do one more thing before that! Open the class for enrollment.
		</p>

		<hr>

		<a href="{{ route('admin::classes_mgmt::classes.open_for_enrollment',$ahamClass->id) }}" data-method='POST' class="rest btn btn-success btn-block legitRipple">
			OPEN FOR ENROLLMENT	
		</a>

	</div>
</div>
@endif

@if($ahamClass->status == 'got_feedback')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<p>
			Economics:

			<ul>
				<li>Total Student Enrolled: {{ $ahamClass->enrollments->count() }}</li>
				<li>Total Worth: {{ $totalWorth = $ahamClass->enrollments->count()*$ahamClass->topic->units->count()*1000 }}</li>
				<li>Teacher Commission: Rs.{{ (2/100)*$totalWorth }}/- </li>
			</ul>
		</p>

		<hr>

		<a href="{{ route('admin::classes_mgmt::classes.complete',$ahamClass->id) }}" data-method='POST' class="rest btn btn-success btn-block legitRipple">
			MARK AS COMPLETE
		</a>

	</div>
</div>
@endif

@if($ahamClass->status == 'open_for_enrollment')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<h3 class="no-margin">{{ $ahamClass->enrollments->count() }}</h3>
		Students
		<div class="text-muted text-size-small">enrolled to this class</div>

		<hr>

		<p>
		Ok! The class is open for enrollment. Sit back and relax while students enroll. We have already given a list of students who are eligible to take this class, if you want, you can help them enroll in the class.
		</p>
		
		@if($ahamClass->enrollments->count())
		<hr>

		<p>
			If a class is scheduled it means - there is no going back!
		</p>

		<a href="{{ route('admin::classes_mgmt::classes.schedule',$ahamClass->id) }}" data-method='POST' class="rest btn btn-success btn-block legitRipple">
			SCHEDULE	
		</a>
		@endif

	</div>
</div>
@endif

@if($ahamClass->status == 'scheduled')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<h3 class="no-margin">{{ $ahamClass->enrollments->count() }}</h3>
		Students
		<div class="text-muted text-size-small">enrolled to this class</div>

		<hr>

		<p>
			This class is scheduled, Here is the class page!
		</p>

		<a href="{{ route('admin::classes_mgmt::classes.session',$ahamClass->id) }}" data-method='POST' class="rest btn btn-success btn-block legitRipple">
			IN SESSION	
		</a>

	</div>
</div>
@endif


@if($ahamClass->status == 'in_session')

<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Next Step</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">

		<h3 class="no-margin">{{ $ahamClass->enrollments->count() }}</h3>
		Students
		<div class="text-muted text-size-small">enrolled to this class</div>

		<hr>

		<p>
			The class is in session! 
		</p>

	</div>
</div>
@endif

<div class="panel bg-teal-400">
<div class="panel-body">

	<h3 class="no-margin">{{ $certifiedTeachers->count() }}</h3>
	Teachers
	<div class="text-muted text-size-small">are certified to teach this class</div>

	<hr>

	<table class="table table-responsive">
		<thead>
			<th>Email</th>
			<th>Available</th>
			<th>Ignore Calendar</th>
		</thead>	
		@foreach($certifiedTeachers as $certifiedTeacher)
		<tr>
		<td>{{ $certifiedTeacher->teacher->user->email }}</td>
		<?php
			$available = false;

			$available = Aham\Helpers\TeacherHelper::isAvailable($ahamClass, $certifiedTeacher);
		?>
		<td>
			@if($available)
			Yes
			@else
			No
			@endif
		</td>
		<td>
			@if($certifiedTeacher->teacher->ignore_calendar)
			Yes
			@else
			No
			@endif
		</td>
		</tr>
		@endforeach
	</table>	

</div>
</div>