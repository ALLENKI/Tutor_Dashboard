<div class="page-header page-header-inverse page-header-xs">
<div class="page-header-content">
    <div class="page-title">
        <h4><span class="text-bold" style="font-size:26px;">{{ $topic->name }} </span></h4>

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
<li class="{{ Request::segment(5) == '' ? 'active' : '' }}"><a href="{{ route('teacher::courses.show',$topic->slug) }}" class="legitRipple"><i class="icon-display4 position-left"></i> Course Details</a></li>

<li class="{{ Request::segment(5) == 'goals' ? 'active' : '' }}"><a href="{{ route('teacher::courses.goals',$topic->slug) }}" class="legitRipple"><i class="icon-stats-growth position-left"></i> Course Goals</a></li>


@if($teacher->certifications()->where('topic_id',$topic->id)->count() == 0)
<li id="interest" style="float:right;"><a href="#" class="btn btn-danger btn-mini-xs" data-url="{{ route('teacher::courses.interested', $topic->id ) }}" onclick='openAjaxModal(this);' style="color: #fff;">Interested to teach this course</i></a>
@endif
</li>

</ul>
