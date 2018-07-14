@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Content</span> - URLs</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::content::seo.index') }}">SEO</a></li>
				<li class="active">SEO - {{ $seo->name }}</li>
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
		<h5 class="panel-title">Edit SEO - {{ $seo->name }}</h5>

		<div class="heading-elements">
			<a href="{{ route('admin::content::seo.delete',$seo->id) }}" data-method="DELETE" class="btn btn-danger heading-btn rest">Delete</a>
		</div>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::content::seo.update',$seo->id)) !!}

		{!! BootForm::bind($seo) !!}

        {!! BootForm::text('URL *', 'url')
              ->placeholder('URL')
              ->attribute('required','false') !!}

		
        {!! BootForm::textarea('Title *', 'title')
              ->placeholder('Title')
              ->attribute('rows',3)
              ->attribute('required','true') 
        !!}
	
	       {!! BootForm::textarea('Keywords *', 'keywords')
              ->placeholder('Keywords')
              ->attribute('rows',3)
              ->attribute('required','true') 
        !!}

       {!! BootForm::textarea('Description *', 'description')
              ->placeholder('Description')
              ->attribute('rows',3)
              ->attribute('required','true') 
        !!}
	

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


});
</script>

@stop