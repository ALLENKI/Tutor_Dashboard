@if($class->status == 'get_feedback')
{!! BootForm::open()->action(route('teacher::class.feedback',$class->code)) !!}

@if($class->enrollments()->count()>0)
<table class="table table-bordered">
	<thead>
		<th>Learner</th>
		<th>Feedback</th>
	</thead>

	<tbody>
		@foreach($class->enrollments()->get() as $enrollment)
		<?php
			$options = [
          			  'absent' => 'Absent',
                      'excellent' => 'Excellent', 
                      'good' => 'Good',
                      'needs_practice' => 'Needs Practice',
                    ];

            if($enrollment->ghost)
            {
            	$options = ['ghost' => "Ghost"] + $options;
            }
		?>
		<tr>
			<td>
				{{ $enrollment->student->user->name }}
				{!! BootForm::hidden("feedback[$enrollment->id][enrollment_id]")->value($enrollment->id) !!}
			</td>

			<td>
                {!! 
                	BootForm::select('Feedback', "feedback[$enrollment->id][feedback]")
                      ->options($options)
                      ->select($enrollment->feedback)
                      ->attribute('required','true')
                      ->hideLabel() 
                !!}
                <textarea name="feedback_text" class="form-control" placeholder="Remarks" rows="4" cols="50"></textarea>
			</td>

		</tr>
		@endforeach
	</tbody>
</table>

<div class="panel-footer">
	<button type="submit" class="btn btn-xs btn-success pull-right">Submit</button>
</div>
@else
<h3 class="sub-title"> No Students Enrolled To class</h3>
@endif


{!! BootForm::close() !!}


@else
<table class="table table-bordered" style="width: 90%; height: 32px;">
	<thead>
		<th>Learner</th>
		<th>Ghost</th>
		<th>Feedback</th>
        <th>Remarks</th>
	</thead>

	<tbody>
		@foreach($class->enrollments()->get() as $enrollment)
		<tr>
			<td>
				{{ $enrollment->student->user->name }}
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
                {{$enrollment->remarks}}
            </td>
            </td>
		</tr>
		@endforeach
	</tbody>
</table>

@endif


