@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.courses.header')

<div class="row">
	<div class="col-md-8">
		

		<div class="panel panel-flat">

			<div class="panel-body">

			@foreach($topic->units as $index => $unit)
				<div class="media">
					<div class="media-left">
						<a href="#"><img src="{{ cdn('assets/front_old/images/learn/number_'.($index+1).'_small.png') }}" class="media-preview" style="max-height:60px;"></a>
					</div>

					<div class="media-body">
						<div class="media-heading text-semibold" style="font-size:27px;">{{ $unit->name }}</div>
						{!! $unit->description !!}
					</div>
				</div>
			@endforeach

			</div>
		</div>


		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title"> <strong>Upcoming Classes</strong> </h6>
		    </div>	

		    @if($classes->total())
		    @foreach($classes as $class)
		    	@include('dashboard.teacher.snippets._horizontal_class')
		    @endforeach
		    {!! $classes->render() !!}
		    @else
		    <div class="panel-body text-center">
		    <h4 class="text-center" style="font-style:italic;">
		    No Classes
		    </h4>

		    @if($teacher->certifications()->where('topic_id',$topic->id)->count() == 0)
		    <a href="#" class="btn btn-danger btn-mini-xs" data-url="{{ route('teacher::courses.interested', $topic->id ) }}" onclick='openAjaxModal(this);'>Interested to teach this course</i></a>

		    <br>

			<h6 class="text-semibold margin-top-10">You are not certified to take this class</h6>		    
		    @endif
		    
		    </div>
		    @endif
		</div>



	</div>

	<div class="col-md-4">

		<?php
			$image_url = cloudinary_url($topic->present()->picture, array("height"=>550, "width"=>550, "crop"=>"thumb"));
		?>

		<div class="panel panel-body">
			<div class="content-group">
				<a href="#"><img src="{{ $image_url }}" class="img-responsive img-rounded" alt=""></a>
			</div>

			<h5 class="text-semibold" style="font-size: 20px;">{{ $topic->name }} <small class="display-block"></small></h5>
			<p class="content-group">
				{{ $topic->description }}			
			<p>

			<ul class="list content-group">
				<li><span class="text-bold" style="font-size:16px;">Units:</span> <span style="font-style:italic;">{{$topic->units->count() }}</span></li>
				<li><span class="text-bold" style="font-size:16px;">Sub Category:</span> <span style="font-style:italic;"> {{ $topic->lookup->subCategory->name }}</span></li>
				<li><span class="text-bold" style="font-size:16px;">Subject:</span> <span style="font-style:italic;"> {{ $topic->lookup->subject->name }}</span></li>
				<li><span class="text-bold" style="font-size:16px;">Tags:</span> <span style="font-style:italic;"> {{ $topic->tagList }}</span></li>
			</ul>

		</div>

	</div>
</div>


@stop

@section('styles')
@parent
<style type="text/css">
	.table-responsive + .table-responsive > .table:not(.table-bordered):not(.table-framed), .table:not(.table-bordered):not(.table-framed) + .table:not(.table-bordered):not(.table-framed) {
	    border-top: 3px solid #ddd;
	}

  	li#interest > a:hover, li#interest > a:focus {
	   background-color: #f44336;
 	}

</style>
@stop