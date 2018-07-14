@extends('dashboard.student.layouts.master')

@section('content')

@if($classes->total())
<div class="panel">
	<div class="panel-body">
		<h6 class="text-center">
			These classes are recommended for you by Aham, Happy Learning!
		</h6>
	</div>
</div>
@endif


<div class="row">
	@if($classes->total())

		@foreach($classes as $index => $class)
		<div class="col-md-6">
			@include('dashboard.student.snippets._recommended_class')
		</div>

		@if(!(($index+1)%2))
			<div class="clearfix"></div>	
		@endif

		@endforeach

	@else

	<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title"><strong>Recommended Classes </strong></h6>
	</div>
	<div class="panel-body"

	<h4 class="text-center" style="font-style: italic;font-size: 16px;">You do not have any specific invitations, browse <a href="{{ route('student::classes.browse') }}">upcoming classes</a> to enroll.</h4>
	</div>

	</div>

	@endif
</div>

{!! $classes->render() !!}

@stop


@section('styles')
@parent
<style type="text/css">
.media-heading {
    margin-bottom: 10px;
}
</style>

@stop

@section('scripts')
@parent
<script type="text/javascript">
	$(function(){

		$('.not_interested').on('click',function(){

			if (confirm('Are you sure you want to mark this class as not interested?')) {
			    console.log("Not interested?",$(this).data('url'));
			    window.location.href = $(this).data('url');
			} else {
			    
			}

			
		});

	});
</script>

@stop