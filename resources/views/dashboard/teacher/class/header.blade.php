<div class="page-header page-header-inverse page-header-xs">
<div class="page-header-content">
    <div class="page-title">
        <h4><span class="text-bold">{{ $class->topic->name }} </span></h4>

		<div class="heading-elements">
			<div class="media-left">
				<h5 class="text-semibold no-margin">{{ $class->code }}</h5>
			</div>
		</div>


    </div>
</div>
</div>

<ul class="nav nav-lg nav-tabs nav-tabs-bottom search-results-tabs">
<li class="{{ Request::segment(5) == 'details' ? 'active' : '' }}"><a href="{{ route('teacher::class.show',$class->code) }}" class="legitRipple"><i class="icon-display4 position-left"></i>Class Details</a></li>
<li class="{{ Request::segment(5) == 'learners' ? 'active' : '' }}"><a href="{{ route('teacher::class.learners',$class->code) }}" class="legitRipple"><i class="icon-user position-left"></i> Learners</a></li>
<li class="{{ Request::segment(5) == 'course-details' ? 'active' : '' }}"><a href="{{ route('teacher::class.course-details',$class->code) }}" class="legitRipple"><i class="icon-display4 position-left"></i>Course Details</a></li>

</ul>
