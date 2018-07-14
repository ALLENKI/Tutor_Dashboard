@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Guest Series</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::guest_series.index') }}">Guest Series</a></li>
				<li class="active">Guest Series - {{ $guestSeries->name }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

@if($guestSeries->status == 'cancelled')
<div class="alert alert-danger alert-styled-left alert-arrow-left alert-component">
	<h6 class="alert-heading text-semibold">This series is cancelled because of following reason:</h6>
	{{ $guestSeries->cancellation_reason }}
</div>
@endif


<div class="row">

	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="panel-title">#{{$guestSeries->id}}: {{$guestSeries->name}} ({{$guestSeries->code}})</h5>

				<div class="heading-elements">
				@if($guestSeries->status != 'cancelled' && $guestSeries->status != 'launched')
				
					<a href="{{ route('admin::classes_mgmt::guest_series.edit',$guestSeries->id) }}" class="btn btn-primary heading-btn">Edit</a>
            		
            	@endif

            	<button class="btn btn-info btn-mini-xs" data-url="{{ route('admin::classes_mgmt::guest_series_levels.create_modal',[$guestSeries->id]) }}" onclick='openAjaxModal(this);'>Create Level</button>
            	</div>


			</div>

			<div class="panel-body">

				<h5>Requirement</h5>
				{!! $guestSeries->requirement !!}

				<h5>Optional Info</h5>
				{!! $guestSeries->optional !!}

				<h5>Description</h5>
				{!! $guestSeries->description !!}

				<hr>

				@foreach($guestSeries->levels as $level)	
					@include('backend.classes_mgmt.guest_series.level')
				@endforeach

			</div>

			<div class="panel-body">

			<h5>Upload Image (Image size should be around 900x500)</h5><hr>
				
				{!! 
					BootForm::open()
					->action(route('admin::classes_mgmt::guest_series.upload_picture',$guestSeries->id))
					->attribute('enctype',"multipart/form-data")
				!!}
				

				{!! cl_image_tag($guestSeries->present()->picture,array('class' => 'img-responsive')) !!}
				
				 

				@if($guestSeries->picture == '')
				<div class="alert alert-warning no-border">
				This guest series has no image set.
				</div>
				@endif

				<hr>

				<div class="form-group {!! $errors->has('picture') ? 'has-error' : '' !!}">
					<input type="file" class="file-styled" name="picture">
	            	{!! $errors->first('picture', '<p class="help-block">:message</p>') !!}
				</div>

				<hr>

				<div>
					<button type="submit" class="btn btn-info btn-mini-xs">Upload Picture <i class="icon-arrow-right14 position-right"></i></button>
				</div>

				{!! BootForm::close() !!}

			</div>


		</div>

	</div>

	<div class="col-lg-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">Snapshot</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">

				@include('backend.classes_mgmt.guest_series._summary')

			</div>
		</div>

		{{-- @if($guestSeries->status != 'cancelled')
		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title">Cancel Series</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">


				<button data-url='{{ route('admin::classes_mgmt::guest_series.cancel_modal', [$guestSeries->id]) }}' class='btn btn-danger btn-xs btn-block' onclick='openAjaxModal(this);'>CANCEL SERIES</button>

			</div>

		</div>
		@endif --}}


	</div>

</div>


@stop

@section('styles')
@parent
<style type="text/css">

</style>
@stop

@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

});
</script>

@stop