@extends('dashboard.teacher.layouts.master')

@section('content')
<?php $topic = $class->topic; ?>

@include('dashboard.teacher.class.header')

<div class="row">

	@foreach($class->enrollments as $enrollment)

		<?php
		  $student = $enrollment->student;
		  $image_url = cloudinary_url($student->user->present()->picture, array("width"=>70, "crop"=>"scale"));
		?>

		<div class="col-lg-3 col-md-6">
			<div class="panel panel-body">
				<div class="media">
					<div class="media-left">
						<a href="assets/images/placeholder.jpg" data-popup="lightbox">
							<img src="{{ $image_url }}" style="width: 70px; height: 70px;" class="img-circle" alt="">
						</a>
					</div>

					<div class="media-body" style="padding-top:10px;">
						<h6 class="media-heading">{{ $student->user->name }}</h6>
						<p class="text-muted">{{ ucwords(str_replace('_', ' ',$student->user->grade)) }}</p>
						<p class="text-muted">
						Enrollment Mode:

							@if($enrollment->ghost)
							Ghost
							@else
							Normal
							@endif
						</p>
					</div>
				</div>
			</div>
		</div>
	@endforeach

</div>

@stop


@section('scripts')
@parent


@stop