<ul>
	<li><strong>Status:</strong> {{ strtoupper($guestSeries->status) }}</li>

	<li>
	<strong>Series Code:</strong> 
	{{ $guestSeries->code }}
	</li>

	<li>
	<strong>Series Type:</strong> 
	{{ $guestSeries->series_type }}
	</li>

	<li>
	<strong>Enrollment Restriction:</strong> 
	{{ $guestSeries->enrollment_restriction }}
	</li>

	<li>
	<strong>Enrollment User:</strong> 
	{{ $guestSeries->enrollment_user }}
	</li>

	<li>
	<strong>Enrollment Type:</strong> 
	{{ $guestSeries->enrollment_type }}
	</li>

	@if($guestSeries->enrollment_restriction == 'restrict_by_level')

	@if(!$guestSeries->levels()->whereNull('enrollment_cutoff')->count())
	<li>
	<strong>Public Page:</strong> 
	<a href="{{ route('series::show',$guestSeries->slug) }}">{{ $guestSeries->code }}</a>
	</li>
	@endif

	@endif

	@if($guestSeries->enrollment_restriction  == 'restrict_by_episode')

	@if(!$guestSeries->episodes()->whereNull('enrollment_cutoff')->count())
	<li>
	<strong>Public Page:</strong> 
	<a href="{{ route('series::show',$guestSeries->slug) }}">{{ $guestSeries->code }}</a>
	</li>
	@endif

	@endif

	<li><strong>Location:</strong><a href="{{ route('admin::locations_mgmt::locations.show',$guestSeries->location->id) }}" target="_blank"> {{ $guestSeries->location->name }} </a> </li>

	<li>
	<strong>Free:</strong> 
	@if($guestSeries->cost_per_episode == 0)
	<span class="label label-success">Yes</span>
	@else
	<span class="label label-danger">No</span>
	@endif
	</li>

	<li>
		<a href="{{ route('admin::users::users.export-enrolled-series',$guestSeries->id) }}">Download Enrolled Users</a>
	</li>

	<li>
		<a href="{{ route('admin::users::users.export-non-enrolled-series',$guestSeries->id) }}">Download Non Enrolled Users</a>
	</li>

	<li>
	<strong>Send Enrollment Emails:</strong> 
	<a href="{{ route('admin::classes_mgmt::guest_series.send_emails',$guestSeries->id) }}" class="btn btn-primary btn-xs">Send</a>
	</li>
</ul>