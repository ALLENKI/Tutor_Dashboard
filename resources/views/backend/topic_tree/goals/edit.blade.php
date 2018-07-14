@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Goals Management</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::topic_tree::goals.index') }}">All Goals</a></li>
				<li class="active">Edit Goal</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop


@section('content')

<!-- Basic datatable -->
<div class="panel panel-white">
	<div class="panel-heading">
		<h5 class="panel-title">Edit Goal - {{ $goal->name }}</h5>

		<div class="heading-elements">
			<a href="{{ route('admin::topic_tree::goals.delete',$goal->id) }}" data-method="DELETE" class="btn btn-danger heading-btn rest">Delete</a>
		</div>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::goals.update',$goal->id)) !!}

		{!! BootForm::bind($goal) !!}

		{!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

		<div class="form-group">

		<label class="col-sm-4 col-lg-2 control-label" for="active">Active</label>

		<div class="col-sm-8 col-lg-10">
		<input type="checkbox" name="active" value="1" id="active" class="form-control" placeholder="Active" {{ $goal->active ? 'checked' : '' }}></div>

		</div>

        {!! BootForm::textarea('Description *', 'description')
              ->placeholder('Name')
              ->attribute('rows',3) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">
			Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->

@stop


@section('scripts')
@parent

<script type="text/javascript">

</script>

@stop