<div class="tabbable">
	<ul class="nav nav-pills nav-pills-toolbar nav-justified">

		<li class="{{ Request::segment(4) == 'on-going' ? 'active' : '' }}">
			<a href="{{  route('student::classes.on-going') }}" class="legitRipple">
			<i class="icon-ticket position-left"></i> Today
			</a>
		</li>

		<li class="{{ Request::segment(4) == 'upcoming' ? 'active' : '' }}">
			<a href="{{  route('student::classes.upcoming') }}" class="legitRipple">
			<i class="icon-airplane2 position-left"></i> Upcoming
			</a>
		</li>

	<!-- 	<li class="{{ Request::segment(4) == 'enrolled' ? 'active' : '' }}">
			<a href="{{  route('student::classes.enrolled') }}" class="legitRipple">
			<i class="icon-calendar position-left"></i> Enrolled
			</a>
		</li> -->

		<li class="{{ Request::segment(4) == 'completed' ? 'active' : '' }}">
			<a href="{{  route('student::classes.completed') }}" class="legitRipple">
			<i class="icon-basket position-left"></i> Completed
			</a>
		</li>

	</ul>

</div>