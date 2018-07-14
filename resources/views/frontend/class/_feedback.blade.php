<h3>Feedback</h3>

{!! BootForm::open()->action(route('class::feedback',$ahamClass->code)) !!}


<table class="table">
	<thead>
		<th>Learner</th>
		<th>Feedback</th>
	</thead>

	<tbody>
		@foreach($ahamClass->enrollments as $enrollment)
		<tr>
			<td>
				{{ $enrollment->student->user->name }}
				{!! BootForm::hidden("feedback[$enrollment->id][enrollment_id]")->value($enrollment->id) !!}
			</td>
			<td>
                    {!! BootForm::select('Feedback', "feedback[$enrollment->id][feedback]")
                          ->options([
                                      'excellent' => 'Excellent', 
                                      'good' => 'Good',
                                      'needs_practice' => 'Needs Practice',
                                    ])
                          ->select($enrollment->feedback)
                          ->attribute('required','true')
                          ->hideLabel() 
                    !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<button type="submit">Submit</button>

{!! BootForm::close() !!}


