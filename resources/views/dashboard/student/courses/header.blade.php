<div class="page-header page-header-inverse page-header-xs">
<div class="page-header-content">
    <div class="page-title">
        <h4><span class="text-bold" style="font-size:26px;">{{ $topic->name }} </span></h4>

        {{-- We will think about this after 3 months Mon 26 Sep 2016 --}}
		<div class="heading-elements" style="top:25%;display: none;">
			<div class="media-left">
				<h5 class="text-semibold no-margin" style="font-size: 29px;">15 <span class="text-success text-size-base" style="color:#ffffff !important;font-size: 15px;">Learners</span></h5>
				<ul class="list-inline list-inline-condensed no-margin">
					<li>
						<span class="text-muted">took this course since 12th May 2015</span>
					</li>
				</ul>
			</div>
		</div>
    </div>
</div>
</div>

<ul class="nav nav-lg nav-tabs nav-tabs-bottom search-results-tabs">
<li class="{{ Request::segment(5) == '' ? 'active' : '' }}"><a href="{{ route('student::courses.show',$topic->slug) }}" class="legitRipple"><i class="icon-display4 position-left"></i>Course Details</a></li>


@if($classes->count())

	@if($enrolledClass['status'] == 'enroll')

	<li style="float:right;"><a href="{{ route('student::courses.show',$topic->slug.'#upcoming-classes') }}" class="btn btn-default btn-mini-xs">Enroll</i></a>
	</li>

	@elseif($enrolledClass['status'] == 'enrolled')

	<li style="float:right;"><a href="{{ route('student::courses.show',$topic->slug.'#upcoming-classes') }}" class="btn btn-default btn-mini-xs">Enrolled</i></a>
	</li>

	@elseif($enrolledClass['status'] == 'class_page')

	<li style="float:right;"><a href="{{ route('student::class.show',$enrolledClass['class']->code) }}" class="btn btn-default btn-mini-xs">Go to Class Page</i></a>
	</li>

	@endif

@else
<li style="float:right;"><a href="#" class="btn btn-default btn-mini-xs" data-url="{{ route('student::courses.request', $topic->id ) }}" onclick='openAjaxModal(this);'>Request a Class</i></a>
</li>
@endif

</ul>


