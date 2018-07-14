@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Locations Mgmt</span> - Localities</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::locations_mgmt::localities.index') }}">Localities</a></li>
				<li class="active">Localities - {{ $locality->name }}</li>
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
		<h5 class="panel-title">Edit Locality - {{ $locality->name }}</h5>

		<div class="heading-elements">
			<a href="{{ route('admin::locations_mgmt::localities.delete',$locality->id) }}" data-method="DELETE" class="btn btn-danger heading-btn rest">Delete</a>
		</div>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::locations_mgmt::localities.update',$locality->id)) !!}

		{!! BootForm::bind($locality) !!}

		{!! BootForm::select('City *', 'city_id')
					->options($cities)
					->attribute('required','false')
					->attribute('id','city_id')
		!!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}


		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->
@stop

@section('scripts')
@parent

<script type="text/javascript">


$(document).ready(function(){

	$('#state_id').select2();

});
</script>

@stop