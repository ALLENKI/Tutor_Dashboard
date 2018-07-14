@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Settings</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Settings</li>
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
		<h5 class="panel-title">Create a New Setting</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::content::settings.store')) !!}



        {!! BootForm::text('Key *', 'key')
              ->placeholder('Key')
              ->attribute('required','true') !!}

      {!! BootForm::textarea('Value *', 'value')
              ->placeholder('Value')
              ->attribute('required','true') !!}


		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">List of Settings</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		This Table Shows all the Settings in the system
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>#</th>
				<th>Key</th>
				<th>Value</th>
				<th class="text-center" width="10%">Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($settings as $setting)
			<tr>
				<td>{{ $setting->id }}</td>
				<td>{{ $setting->key }}</td>
				<td>{{ $setting->value }}</td>
				<td class="text-center">
					<ul class='icons-list'>
						<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
								<i class='icon-menu9'></i>
							</a>

							<ul class='dropdown-menu dropdown-menu-right'>
								<li><a href='{{ route('admin::content::settings.edit',$setting->id) }}'><i class='icon-pencil'></i> Edit</a></li>
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



@stop