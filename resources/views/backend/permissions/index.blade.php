@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Permissions</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Permissions</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">List of Permissions in the system</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a href="{{ route('admin::users::permissions.index',['load' => true]) }}" class="btn btn-default btn-xs">Refresh</a></li>
        	</ul>
    	</div>
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
			  <th>Permission</th>
			  <th>Group</th>
			  <th>Type</th>
			  <th>Description</th>
			</tr>
		</thead>
		<tbody>

			@foreach($permissions as $permission)
			<tr>
				<td>{{ $permission['permission'] }}</td>
				<td>{{ $permission['group'] }}</td>
				<td>{{ $permission['type'] }}</td>
				<td>{{ $permission['description'] }}</td>
			</tr>
			@endforeach

		</tbody>
	</table>
</div>
<!-- /basic datatable -->

@stop