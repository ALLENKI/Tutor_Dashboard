	<div class="panel panel-primary">

		<div class="panel-heading">
			<h6 class="panel-title"><i class="icon-office position-left"></i> Classrooms</h6>
			<div class="heading-elements">
				<a href="{{ route('admin::locations_mgmt::classrooms.create',$location->id) }}" class="btn btn-default heading-btn">Create a Classroom</a>
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            	</ul>
        	</div>
		</div>

		<div class="panel-body">

		@if($location->classrooms->count())
		<table class="table table-framed">

			<thead>
				<tr>
					<th>Name</th>
					<th>Code</th>
					<th>Size</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				@foreach($location->classrooms as $classroom)
				<tr>
					<td>{{ $classroom->name }}</td>
					<td>{{ $classroom->code }}</td>
					<td>{{ $classroom->size }}</td>
					<td>
						<ul class='icons-list'>
							<li class='dropdown'>
								<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
									<i class='icon-menu9'></i>
								</a>

								<ul class='dropdown-menu dropdown-menu-right'>
									<li>
										<a href='{{ route('admin::locations_mgmt::classrooms.edit',$classroom->id) }}'><i class='icon-pencil'></i> Edit</a>
										<a href='{{ route('admin::locations_mgmt::classrooms.delete',$classroom->id) }}' data-method='DELETE' class="rest"><i class='icon-cross'></i> Delete</a>
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
				<h3 class="text-center">This location has no classrooms. </h3>		
		@endif

		</div>

	</div>