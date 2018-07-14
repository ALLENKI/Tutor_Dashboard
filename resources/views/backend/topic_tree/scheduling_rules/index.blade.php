@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Scheduling Rules Mgmt</span> - Rules</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Scheduling Rules</li>
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
		<h5 class="panel-title">List of Scheduling Rules</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		This Table Shows all the Aham Rules. 
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>#</th>
				<th>No of Units</th>
				<th>Division</th>
				<th>Description</th>
				<th class="text-center" width="10%">Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($scheduling_rules as $scheduling_rule)
			<tr>
				<td>{{ $scheduling_rule->code }}</td>
				<td>{{ $scheduling_rule->no_of_units }}</td>
				<td>{{ $scheduling_rule->division }}</td>
				<td>{{ $scheduling_rule->description }}</td>
				<td>
					<ul class='icons-list'>
							<li class='dropdown'>
								<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
									<i class='icon-menu9'></i>
								</a>

								<ul class='dropdown-menu dropdown-menu-right'>
									<li><a href='{{ route("admin::topic_tree::scheduling_rules.edit",$scheduling_rule->id) }}'><i class='icon-pencil'></i> Edit</a></li>
								</ul>
							</li>
						</ul>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<!-- /basic datatable -->

@stop