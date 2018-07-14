@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Aham Dashboard</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home Home</a></li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')


<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Welcome to Aham Admin</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

	<div class="panel-body">
		<h6 class="text-semibold">Add Topics!</h6>
		<p class="content-group">
			Add some help text
		</p>

		<h6 class="text-semibold">Add Locations</h6>
		<p class="content-group">Add some help text</p>

		<h6 class="text-semibold">Create Classes</h6>
		<p>Add some help text</p>
	</div>
</div>

@stop