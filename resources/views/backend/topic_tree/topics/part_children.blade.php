<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ str_plural($topic->typeOptions[$topic->successor()]) }} In - {{ $topic->name }}</h5>
		<small>Use Move Icon to order the topics under this {{ $topic->typeOptions[$topic->successor()] }}</small>
	</div>

	<div class="panel-body">

	@if($topic->children->count())
		<table class="table table-framed">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Type</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody id="children">
				@foreach($topic->children as $child)
				<tr class="child" id="child_{{ $child->id }}">
					<td><i class='icon-move'></i></td>
					<td><a href="{{ route('admin::topic_tree::topics.show',$child->id) }}"> {{ $child->name }} </a></td>
					<td>{!! $child->present()->typeStyled !!}</td>
					<td>{!! $child->status !!}</td>
					<td>
						<ul class='icons-list'>
							<li class='dropdown'>
								<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
									<i class='icon-menu9'></i>
								</a>

								<ul class='dropdown-menu dropdown-menu-right'>
									<li><a href='{{ route('admin::topic_tree::topics.edit',$child->id) }}'><i class='icon-pencil'></i> Edit</a></li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	@else
		<h3 class="text-center">This topic has no {{ str_plural($topic->typeOptions[$topic->successor()]) }} </h3>		
	@endif

		<hr>

		<h4>Add {{ $topic->typeOptions[$topic->successor()] }}</h4>

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::topics.store',['parent_id' => $topic->id])) !!}

		{!! BootForm::hidden('type')->attribute('value',$topic->successor()) !!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

        @if($topic->successor() == 'topic')
		{!! 
			BootForm::select('Graph Level *', 'graph_level')
					->options(['1' => '1', '2' => '2','3' => '3', '4' => '4', '5' => '5'])
					->select(Input::old('graph_level',$topic->graph_level)) 
					->attribute('required','true')
		!!}

		{!! BootForm::select('Status *', 'status')
					->options(['active' => 'Active', 'in_progress' => 'In Progress','in_future' => 'In Future', 'obsolete' => 'Obsolete'])
					->select(Input::old('status',$topic->status)) 
					->attribute('required','true')
		!!}
        @else
        {!! BootForm::hidden('level')->attribute('value','1') !!}
        @endif

		<div class="text-right">
			<h6>Note: You are going to create a new {{ $topic->typeOptions[$topic->successor()] }}</h6>
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->