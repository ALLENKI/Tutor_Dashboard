@extends('dashboard.teacher.layouts.master')

@section('content')

@include('dashboard.teacher.class.header')

<div class="row">

	<div class="col-md-2"></div>


	<div class="col-md-8" style="float:center">

		<?php
			$image_url = cloudinary_url($topic->present()->coverPicture,['secure' => true]);
		?>

		<div class="panel panel-body">
			<div class="content-group">
				<a href="#"><img src="{{ $image_url }}" class="img-responsive img-rounded" alt=""></a>
			</div>

		</div>

	</div>

	<div class="col-md-2"></div>


</div>


@stop

