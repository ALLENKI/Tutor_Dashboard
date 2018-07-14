<section class="section p-top-0">
	<div class="row multi-columns-row col-margin-bottom-50">
	@foreach($courseList as $course)
		@include('frontend.search._course_item')
	@endforeach
	</div>
</section>