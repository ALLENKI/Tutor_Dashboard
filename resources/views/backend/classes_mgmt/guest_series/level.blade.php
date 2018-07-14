<div class="panel panel-primary">

	<div class="panel-heading">
		<h6 class="panel-title">
		<i class="icon-calendar position-left"></i> 
		{{ $level->name }} 
		@if($level->enrollment_cutoff)
		<small>Enroll Before: <u> {{ $level->enrollment_cutoff->format('jS M Y H:i A') }} </u></small>
		@endif
		</h6>
		<div class="heading-elements">
			<button class="btn btn-info btn-xs" data-url="{{ route('admin::classes_mgmt::guest_series_episodes.create_modal',[$level->id]) }}" onclick='openAjaxModal(this);'>Create Episode</button>
			<button class="btn btn-info btn-xs" data-url="{{ route('admin::classes_mgmt::guest_series_levels.edit_modal',[$level->id]) }}" onclick='openAjaxModal(this);'>Edit Level</button>

			{{--
			<a class="btn btn-danger btn-xs rest" data-method='DELETE' href="{{ route('admin::classes_mgmt::guest_series_levels.delete',$level->id) }}">Delete Level</a>
			--}}

    	</div>
	</div>

	<div class="panel-body">

		@if($guestSeries->enrollment_restriction == 'restrict_by_level')
			Enrolled: {{ $level->enrollments->count() }}
		@endif

		{!! $level->description !!}

		<hr>

		@if($level->episodes->count())
			<table class="table">
				<thead>
					<th>Name</th>
					@if($guestSeries->enrollment_restriction == 'restrict_by_episode')
					<th>Enrolled</th>
					<th>Enrollment Cutoff</th>
					@endif
					<th>Date & Time</th>
					<th>Topic</th>
					<th>Slots</th>
					<th>Re-run</th>
					<th>Actions</th>
				</thead>

				<tbody>
					@foreach($level->episodes as $episode)
						<tr>
							<td>{{ $episode->name }}</td>

							@if($guestSeries->enrollment_restriction == 'restrict_by_episode')
							<td><a href="{{ route('admin::classes_mgmt::guest_series_episodes.enrolled',[$episode->id]) }}" target="_blank">{{ $episode->enrollments->count() }}/{{ $episode->enrollment_limit }}</a></td>
							<td>{{ $episode->enrollment_cutoff }}</td>
							@endif

							<td>{{ $episode->date_summary }} {{ $episode->time_summary }}</td>
							<td>{{ $episode->topic->name }}</td>
							<td>
								@if($episode->timings->count())
								<ul class="list-unstyled">
									@foreach($episode->timings as $timing)
									<li>
									{{ $timing->date->format('d M Y') }} :
									{{ $timing->start_time }} - {{ $timing->end_time }}</li>
									@endforeach
								</ul>
								@else
								<a href="javascript:;" data-url="{{ route('admin::classes_mgmt::guest_series_episodes.schedule_modal',[$episode->id]) }}" onclick='openAjaxModal(this);' class="btn btn-primary btn-xs"> Schedule</a>
								@endif
							</td>

							<td>
								@if($episode->repeat_of)
								Yes
								@else
								No
								@endif
							</td>

							<td>

								<ul class='icons-list'>
		                            <li class='dropdown'>
		                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
		                                    <i class='icon-menu9'></i>
		                                </a>

		                                <ul class='dropdown-menu dropdown-menu-right'>

		                                    <li><a href="javascript:;" data-url='{{ route('admin::classes_mgmt::guest_series_episodes.edit_modal',[$episode->id]) }}' onclick='openAjaxModal(this);'><i class='icon-pencil'></i> Edit</a></li>

		                                    <li><a href="javascript:;" data-url='{{ route('admin::classes_mgmt::guest_series_episodes.schedule_modal',[$episode->id]) }}' onclick='openAjaxModal(this);'><i class='icon-pencil'></i> Schedule</a></li>

		                                    <li><a href="javascript:;" data-url='{{ route('admin::classes_mgmt::guest_series_episodes.rerun_modal',[$episode->id]) }}' onclick='openAjaxModal(this);'><i class='icon-pencil'></i> Re-Run</a></li>

		                                    <li><a href="javascript:;" data-url='{{ route('admin::classes_mgmt::guest_series_episodes.cancel_modal',[$episode->id]) }}' onclick='openAjaxModal(this);'><i class='icon-pencil'></i> Delete</a></li>

		                                </ul>
		                            </li>
		                        </ul>

							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<h3>Has No episodes</h3>
		@endif

	</div>
</div>

