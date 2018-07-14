<div class="panel panel-default">
	<div class="panel-heading">
	<h5 class="panel-title">Certified Tutors</h5>
	</div>

	<div class="panel-body">

		<table class="table">
			<thead>
				<th>Teacher</th>
				<th>Email</th>
			</thead>
			<tbody>
			@foreach($certifiedTeachers as $certifiedTeacher)
			<tr>
				<td>{{ $certifiedTeacher->teacher->user->name }}</td>
				<td>{{ $certifiedTeacher->teacher->user->email }}</td>
			</tr>
			@endforeach
			</tbody>
		</table>		



		<hr>


	<?php 

		$columnSizes = [
		  'sm' => [4, 8],
		  'lg' => [2, 10]
		];

	?>

	{!! 
		BootForm::openHorizontal($columnSizes)
		->action(route('admin::topic_tree::topics.certify',$topic->id)) 
	!!}

		<div class="col-md-8">
			{!! BootForm::select('Teacher *', 'teacher_id[]')
						->options($eligibleTeachers)
						->attribute('required','true')
						->attribute('id','teacher_id')
						->attribute('class','form-control multiselect')
						->attribute('multiple','multiple')
			!!}
		</div>

		<div class="col-md-2">
		<button type="submit" class="btn btn-primary btn-block">Certify <i class="icon-arrow-right14 position-right"></i></button>
		</div>

	{!! BootForm::close() !!}

	</div>
</div>