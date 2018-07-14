@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Users</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li class="active">{{ $user->name }} ({{$user->email}})</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-white">
	<div class="panel-heading">
		<h5 class="panel-title">{{ $user->name }}</h5>
		<div class="heading-elements">

        @if($loggedInUser->can('users.edit'))
		<a href="{{ route('admin::users::users.edit',$user->id) }}" class="btn btn-primary heading-btn">Edit</a>
		@endif

		@if($loggedInUser->can('users.impersonate'))
			@if(!$active)
			<a href="{{ route('admin::users::users.activate',$user->id) }}" class="btn btn-success heading-btn">Activate</a>
			@else
			<a href="{{ route('admin::users::users.impersonate',$user->id) }}" class="btn btn-danger heading-btn">Impersonate</a>
			@endif
		@endif

    	</div>
	</div>

	<div class="panel-body">

	<div class="row">
		<div class="col-md-3">
          <?php
              $image_url = cloudinary_url($user->present()->picture, array('secure' => true));
          ?>

          <img src="{{ $image_url }}" class="img-responsive" style="border: 1px #f5f1f1 solid;">
		</div>	

		<div class="col-md-9">
			
			<ul class="list-unstyled">
					<li>Interested In: {{ strtoupper($user->interested_in) }}</li>
					<li>Mobile: {{ $user->mobile }}</li>

                  @if($user->Aadhar_card != null)
                    <a href="{{$user->Aadhar_card}}" download>
                    <button type="button" class="btn btn-success btn-lg">
                      Aadhar<span class="glyphicon glyphicon-download"></span>
                    </button>
                     </a>
                  @endif

                  @if($user->pan_card != null)
                    <a href="{{$user->pan_card}}" download>
                    <button type="button" class="btn btn-success btn-lg">
                      Pan Card<span class="glyphicon glyphicon-download"></span>
                    </button>
                     </a>
                  @endif

                  @if($user->form_16 != null)
                    <a href="{{$user->form_16}}" download>
                    <button type="button" class="btn btn-success btn-lg">
                      Form 16<span class="glyphicon glyphicon-download"></span>
                    </button>
                     </a>
                  @endif

                  @if($user->cheque != null)
                    <a href="{{$user->cheque}}" download>
                    <button type="button" class="btn btn-success btn-lg">
                      Cheque<span class="glyphicon glyphicon-download"></span>
                    </button>
                    </a>
                  @endif


					@if(strtolower($user->interested_in) == 'teacher')
					<li>Why do you want to teach?: {{ $user->why_teacher }}</li>
					<li>LinkedIn: {{ $user->linkedin }}</li>
					<li>Current Profession: {{ $user->current_profession }}</li>
					<li>Interested Subjects: {{ $user->interested_subjects }}</li>
					<li>Resume: 
					@if($user->resume_file != '')
					<a href="{{ $user->resume_file }}" target="_blank">Download</a>
					@else
					NA
					@endif
					@endif


					@if(strtolower($user->interested_in) == 'student')
					<li>Grade?: {{ $user->grade }}</li>
					@endif

				</li>
			</ul>

		</div>
	</div>

	</div>
</div>
<!-- /basic datatable -->

@if($loggedInUser->can('users.student_profile'))
<div class="panel panel-white">
	<div class="panel-heading">
		<h5 class="panel-title">Student Profile</h5>

		<div class="heading-elements">
			@if(!is_null($user->student))

			@if($user->student->active)
			<a href="{{ route('admin::users::students.status',[$user->student->id, 'inactive' => true]) }}" data-method='POST' class="rest btn btn-warning heading-btn">Deactivate</a>
			@else
			<a href="{{ route('admin::users::students.status',[$user->student->id, 'active' => true]) }}" data-method='POST' class="rest btn btn-success heading-btn">Activate</a>
			@endif

			@endif
		</div>
	</div>

	<div class="panel-body">

		@if(is_null($user->student))
		<div class="text-center">

		<h4>This user doesn't have a student profile at Aham</h4>

		<a href='{{ route('admin::users::students.store',['user_id' => $user->id]) }}' data-method='POST' class="rest btn btn-primary"><i class="icon-menu7"></i> Create Student</a>

		</div>
		@endif
		
		@if(!is_null($user->student))

		<?php
			$student = $user->student;
		?>

		<div class="row">

			<div class="col-md-3">
				<div class="panel">
					<div class="panel-body">

						<ul class="list-unstyled">
							<li>
								Code: {{ $student->code }}
							</li>

							<li>
								Joined On: {{ $student->created_at->format('jS M Y') }}
							</li>
						</ul>

					</div>
				</div>
			</div>

			@if($loggedInUser->can('users.student_credits'))
			<div class="col-md-3">
				<div class="panel text-center">
					<div class="panel-body">

						<h2 class="mt-15 mb-5">
							{{ $student->credits }}
						</h2>
						<p>Credits available</p>
						<p>Add credits from the new Admin-db: <a href="https://ahamlearning.com/admin-db#/learners" target="_blank">Click Here</a></p>
						

						<a href="{{ route('admin::users::students.credits',$user->student->id) }}" class="btn btn-primary">Credits History</a>

					</div>
				</div>
			</div>
			@endif

			@if($loggedInUser->can('users.student_goals'))
			<div class="col-md-3">
				<div class="panel text-center">
					<div class="panel-body">

						<h2 class="mt-15 mb-5">
							{{ $student->goals->count() }}
						</h2>
						<p>Subscribed Goals</p>
						

						<a href="{{ route('admin::users::students.goals.show',$user->student->id) }}" class="btn btn-primary">Add Goals</a>

					</div>
				</div>
			</div>
			@endif

			@if($loggedInUser->can('users.student_assessment'))
			<div class="col-md-3">
				<div class="panel text-center">
					<div class="panel-body">

						<h2 class="mt-15 mb-5">
							{{ $student->assessments->count() }}
						</h2>

						<p>Assessed</p>
						
						<a href="{{ route('admin::users::students.show_assessment',$user->student->id) }}" class="btn btn-primary">Check Assessment</a>

					</div>
				</div>
			</div>
			@endif

		</div>

		@endif

	</div>

</div>
@endif


@if($loggedInUser->can('users.teacher_profile'))
<div class="panel panel-white">
	<div class="panel-heading">
		<h5 class="panel-title">
			Teacher Profile

			@if(!is_null($user->teacher) && $user->teacher->show_on_homepage)
			<span class="label label-primary">
				Show on Homepage
			</span>
			@endif

		</h5>

		<div class="heading-elements">
			@if(!is_null($user->teacher))

			@if(!$user->teacher->tutorCommission)
				<span class="label label-danger">Cannot activate, commission less than 0</span>
			@else

				@if($user->teacher->active)
				<a href="{{ route('admin::users::teachers.status',[$user->teacher->id, 'inactive' => true]) }}" data-method='POST' class="rest btn btn-warning heading-btn">Deactivate</a>
				@else
				<a href="{{ route('admin::users::teachers.status',[$user->teacher->id, 'active' => true]) }}" data-method='POST' class="rest btn btn-success heading-btn">Activate</a>
				@endif

			@endif

			@endif
		</div>

	</div>

	<div class="panel-body">

		@if(is_null($user->teacher))

		<div class="text-center">

		<h4>This user doesn't have a teacher profile at Aham</h4>

		<a href='{{ route('admin::users::teachers.store',['user_id' => $user->id]) }}' data-method='POST' class="rest btn btn-primary"><i class="icon-menu7"></i> Create Teacher</a>

		</div>
		@endif

		
		@if(!is_null($user->teacher))

		<?php
			$teacher = $user->teacher;
		?>


		<div class="row">

			<div class="col-md-3">
				<div class="panel">
					<div class="panel-body">

						<ul class="list-unstyled">
							<li>
								Code: {{ $teacher->code }}
							</li>

							<li>
								Joined On: {{ $teacher->created_at->format('jS M Y') }}
							</li>

							<li>
								Total Earnings: Rs.{{ inrFormat($teacher->earnings) }}/-
							</li>

							<li>
								Total Payouts: Rs.{{ inrFormat($teacher->payouts) }}/-
							</li>
						</ul>

						<a href="{{ route('admin::users::teachers.public_profile',$user->teacher->id) }}" class="btn btn-info">Manage Public Profile</a>

					</div>
				</div>
			</div>

			@if($loggedInUser->can('users.teacher_earnings'))

			<div class="col-md-3">
				<div class="panel text-center">
					<div class="panel-body">

						<h2 class="mt-15 mb-5">
							Rs.{{ inrFormat($teacher->earnings-$teacher->payouts) }}/-
						</h2>

						<p>Account Balance</p>
						
						<a href="{{ route('admin::users::teachers.show_earnings',$user->teacher->id) }}" class="btn btn-primary">Check Earnings and Payouts</a>

					</div>
				</div>
			</div>

			@endif


			@if($loggedInUser->can('users.teacher_certification'))

			<div class="col-md-3">
				<div class="panel text-center">
					<div class="panel-body">

						<h2 class="mt-15 mb-5">
							{{ $teacher->certifications->count() }}
						</h2>

						<p>Certified</p>
						
						<a href="{{ route('admin::users::teachers.show_certification',$user->teacher->id) }}" class="btn btn-primary">Check Certification</a>

					</div>
				</div>
			</div>

			@endif

			@if($loggedInUser->can('users.teacher_earnings'))

			<div class="col-md-3">
				<div class="panel text-center">
					<div class="panel-body">

					<h6>Commission Profile (per Unit)</h6>

					@if($user->teacher->tutorCommission && $user->teacher->tutorCommission->location)
					<ul class="list-unstyled">
						<li>Location: {{ $user->teacher->tutorCommission->location->name }}  </li>
						<li>Commission: {{ $user->teacher->tutorCommission->value }} - {{ $user->teacher->tutorCommission->value_type }}
							@if($user->teacher->tutorCommission->value_type == 'amount')
							{{ $user->teacher->tutorCommission->apply_value_to }}
							@endif
						</li>
						<li>Min. Enrollment: {{ $user->teacher->tutorCommission->min_enrollment }}</li>
					</ul>
					@else
					Not Set
					@endif
						
					<a href="{{ route('admin::users::teachers.manage_commission',$user->teacher->id) }}" class="btn btn-primary">Manage Commission</a>

					</div>
				</div>
			</div>

			@endif

		</div>

		@endif

	</div>

</div>
@endif

@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){


});
</script>

@stop
