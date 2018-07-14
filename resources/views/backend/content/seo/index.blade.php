@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Content</span> - URLs</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">URLs</li>
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
		<h5 class="panel-title">List of URLs</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>#</th>
				<th>URL</th>
				<th class="text-center" width="10%">Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($seos as $seo)
			<tr>
				<td>{{ $seo->id }}</td>
				<td><a href="{{ url($seo->url) }}" target="_blank">{{ $seo->url }}</a> </td>
				<td class="text-center">
					<ul class='icons-list'>
						<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
								<i class='icon-menu9'></i>
							</a>

							<ul class='dropdown-menu dropdown-menu-right'>
								<li><a href='{{ route('admin::content::seo.edit',$seo->id) }}'><i class='icon-pencil'></i> Edit</a></li>
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

@section('scripts')
@parent

<script type="text/javascript">
$(document).ready(function(){



});
</script>

@stop