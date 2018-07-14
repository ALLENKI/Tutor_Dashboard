@if($ahamClass->status == 'open_for_enrollment' ||
$ahamClass->status == 'scheduled' ||
$ahamClass->status == 'in_session' ||
$ahamClass->status == 'get_feedback' ||
$ahamClass->status == 'got_feedback' ||
$ahamClass->status == 'cancelled' ||
$ahamClass->status == 'completed')

	<div class="panel panel-primary">

		<div class="panel-heading">
			<h6 class="panel-title"><i class="icon-calendar position-left"></i> Enrollments</h6>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            	</ul>
        	</div>
		</div>

		<div class="panel-body">

		@if(isset($eligibleStudents))
		<div class="row" style="margin-bottom: 10px;">

			<div class="col-md-8">

			<select name="student_id" id="student_id" class="form-control" required="true">
				@foreach($eligibleStudents as $student_id => $eligibleStudent)
				<option value="{{ $student_id }}">{{ $eligibleStudent }}</option>
				@endforeach
			</select>

			</div>

			<div class="col-md-4">
			<button class="btn btn-primary btn-block" id="enroll_student">Enroll <i class="icon-arrow-right14 position-right"></i></button>
			</div>

		</div>
		@endif


		@if($ahamClass->enrollments->count())
		<table class="table table-framed">

			<thead>
				<tr>
					<th>Student Code</th>
					<th>Name</th>
					<th>Email</th>
					<th>Enrolled On</th>
					<th>Ghost</th>
					<th>Feedback</th>
					<th>UnEnroll</th>
				</tr>
			</thead>

			<tbody>
				@foreach($ahamClass->enrollments as $enrollment)

				<?php $student = $enrollment->student; ?>

				<tr>
					<td>{{ $student->code }}</td>
					<td>{{ $student->user->name }}</td>
					<td>{{ $student->user->email }}</td>
					<td>
						{{ $enrollment->created_at->format('jS M Y H:i A') }}
					</td>
					<td>
						@if($enrollment->ghost)
						Yes
						@else
						No
						@endif
					</td>
					<td>
						{!! $enrollment->present()->typeFeedback !!}
					</td>
					<td>
					@if($ahamClass->status ==  'open_for_enrollment' || $ahamClass->status == 'scheduled')
					<button class="btn btn-xs btn-danger pull-right" data-url="{{ route('admin::classes_mgmt::enrollment.cancel', [$ahamClass->id,$student->id]) }}" onclick='openAjaxModal(this);'>Cancel</button>
					@endif
					</td>

				</tr>
				@endforeach
			</tbody>

		</table>
		@else
				<h3 class="text-center">Sorry! No Student enrolled to the class.</h3>		
		@endif


		</div>

	</div>

@section('scripts')
@parent
<script type="text/javascript">

var _url = "{{ route('admin::admin') }}"+'/classes_mgmt/classes/enroll_student/'+'{{ $ahamClass->id }}';

$(function(){

	$('#teacher_id').select2();
	$('#student_id').select2();

	$('#enroll_student').on('click',function(){
		openAjaxModalViaUrl(_url+'/'+$('#student_id').val());
		// console.log('Student');
	});

});
</script>
@stop

@endif