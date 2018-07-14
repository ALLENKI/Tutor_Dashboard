@if($classes->count())
<section class="section p-top-0 p-bottom-0">
	<div class="row multi-columns-row col-margin-bottom-50">

	<h4 class="text-center">
	{{ $classes->count() }} Results Found
	</h4>

	<div class="clearfix"></div>

	<hr>

	@foreach($classes as $item)
	
		@if(get_class($item) == 'Aham\Models\SQL\Topic')
			<?php $course = $item ?>
			@if(!$classes->contains('topic_id',$course->id))
				@include('frontend.search._course_item')
			@endif
		@endif

	@endforeach
	</div>
</section>
@else

<h4 class="text-center">
No Results Found
</h4>

@endif