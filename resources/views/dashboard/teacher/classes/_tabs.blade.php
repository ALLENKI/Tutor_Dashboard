<div class="tabbable">
	<ul class="nav nav-pills nav-pills-toolbar nav-justified">

		<li class="{{ Request::segment(4) == 'on-going' ? 'active' : '' }}">
			<a href="{{  route('teacher::classes.on-going') }}" class="legitRipple">
			<i class="icon-ticket position-left"></i> On Going
			</a>
		</li>

		<li class="{{ Request::segment(4) == 'upcoming' ? 'active' : '' }}">
			<a href="{{  route('teacher::classes.upcoming') }}" class="legitRipple">
			<i class="icon-airplane2 position-left"></i> Upcoming
			</a>
		</li>


		<!--<li class="{{ Request::segment(4) == 'scheduled' ? 'active' : '' }}">
			<a href="{{  route('teacher::classes.scheduled') }}" class="legitRipple">
			<i class="icon-calendar position-left"></i> Scheduled
			</a>
		</li>-->

		<li class="{{ Request::segment(4) == 'completed' ? 'active' : '' }}">
			<a href="{{  route('teacher::classes.completed') }}" class="legitRipple">
			<i class="icon-basket position-left"></i> Completed
			</a>
		</li>

	</ul>

</div>

@section('scripts')
@parent
<style type="text/css">
.panel-white > .panel-heading {
    border-bottom-color: #f3f3f3;
}

.panel-footer {
    background-color: #fff;
    border-top-color: #f3f3f3;
}
</style>

@stop
