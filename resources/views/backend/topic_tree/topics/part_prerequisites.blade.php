<div class="panel panel-default">
	<div class="panel-heading">
	<h5 class="panel-title">Prequisites for {{ $topic->name }}</h5>
	</div>

	<div class="panel-body">

		@if($topic->prerequisites->count())
		<table class="table table-framed">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Type</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>

			<tbody>	
				@foreach($topic->prerequisites as $prerequisite)
				<tr>
					<td>{{ $prerequisite->id }}</td>
					<td>
						<a href="{{ route('admin::topic_tree::topics.show',$prerequisite->id) }}">
							{{ $prerequisite->name }}
						</a>
					</td>
					<td>{!! $prerequisite->present()->typeStyled !!}</td>
					<td class="text-center">
						<a href="{{ route('admin::topic_tree::topics.remove_prerequisite', [$topic->id, $prerequisite->id]) }}" data-method="POST" class="rest">
							<i class='icon-cross'></i>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		@else
			<h3 class="text-center">This topic has no prerequisites, Start adding. </h3>
		@endif

		<hr>

		

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::topics.add_prerequisite',$topic->id)) !!}

		{!! BootForm::select('Topic *', 'topic_id')
					->options($prerequisite_options)
					->attribute('required','false')
					->attribute('id','choose_prerequisite')
		!!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>
</div>