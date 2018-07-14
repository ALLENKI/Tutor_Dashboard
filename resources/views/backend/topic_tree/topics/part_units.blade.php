<div class="panel panel-default">
	<div class="panel-heading">
	<h5 class="panel-title">Units for {{ $topic->name }}</h5>
	<small>Use Move Icon to order the units under this topic: {{ $topic->name }}</small>
	</div>

	<?php 
		$canEdit = true;
		if($topicClasses->count())
		{
			$canEdit = false;
		}
	?>

	<div class="panel-body">

		@if($topic->units->count())
		<table class="table table-framed">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Description</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>

			<tbody id="units">	
				@foreach($topic->units as $unit)
				<tr class="unit" id="unit_{{ $unit->id }}">
					<td><i class='icon-move'></i></td>
					<td>{{ $unit->name }}</td>
					<td>{!! $unit->description !!}</td>
					<td class="text-center">
						<ul class='icons-list'>
							<li class='dropdown'>
								<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
									<i class='icon-menu9'></i>
								</a>

								<ul class='dropdown-menu dropdown-menu-right'>
									<li>
										@if($canEdit)
										<a href='{{ route('admin::topic_tree::units.edit',$unit->id) }}'><i class='icon-pencil'></i> Edit</a>
										
										<a href='{{ route('admin::topic_tree::units.delete',$unit->id) }}' data-method='DELETE' class="rest"><i class='icon-cross'></i> Delete</a>
										@endif
									</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		@else
			<h3 class="text-center">This topic has no units, Start adding. </h3>
		@endif

		<hr>
		
		@if(!$canEdit)
			<h3 class="text-center">There are classes scheduled under this topic. You can't edit this topic.</h3>
		@else

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::units.store')) !!}

		{!! BootForm::hidden('topic_id')->attribute('value',$topic->id) !!}

		{!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

        {!! BootForm::textarea('Description', 'description')
        			->attribute('rows',3) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}

		@endif
	</div>
</div>