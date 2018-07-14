@if($classes->count())
<section class="section p-top-0 p-bottom-0">
	<div class="row multi-columns-row col-margin-bottom-50">
	@foreach($classes as $ahamClass)
		@include('frontend.search._class_item_date')
	@endforeach
	</div>
</section>
<hr>
@endif