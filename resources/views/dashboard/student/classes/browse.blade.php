@extends('dashboard.student.layouts.master')

@section('content')

<div class="panel">
	<div class="panel-body">
		<h4 class="text-center content-group-lg">
			Search Topics to Enroll
			<small class="display-block">Explore Aham, Unlock Learning</small>
		</h4>
        <form action={{route('student::classes.browse')}} class="main-search" method="GET">

			<div class="input-group content-group">
				<div class="has-feedback has-feedback-left">
					<input type="text" class="form-control input-xlg" placeholder="Search our knowledgebase" name="q" value="{{ Input::get('q','') }}">
					<div class="form-control-feedback">
						<i class="icon-search4 text-muted text-size-base"></i>
					</div>
				</div>

               <select class="form-control" name="sortBy">
                    <option value="ASC">Sort By ASC</option> 
                    <option value="DESC">Sort By DESC</option>
               </select>

				<div class="input-group-btn">
					<button type="submit" class="btn btn-primary btn-xlg legitRipple">Search</button>
				</div>
			</div>
		</form>
	</div>
</div>

@if($classes->total())
<div class="panel">
	<div class="panel-body">
		<h6 class="text-center">
			These classes are currently open for enrollment and are scheduled to run only if Minimum enrollment is met.
		</h6>
	</div>
</div>
@endif


<div class="row">
	@if($classes->total())

		@foreach($classes as $index => $class)
		<div class="col-md-6">
			@include('dashboard.student.snippets._normal_class')
		</div>

		@if(($index+1)%2 == 0)
			<div class="clearfix"></div>	
		@endif
		@endforeach

	@else
	<h3 class="text-center">No Classes</h3>
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

@section('script')
@stop
