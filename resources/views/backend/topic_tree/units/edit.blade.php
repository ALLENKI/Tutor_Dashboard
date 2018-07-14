@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Topic Tree</span> - Units - {{ $unit->name }}</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::topic_tree::topics.index') }}">All Topics</a></li>
				<li><a href="{{ route('admin::topic_tree::topics.show',$unit->topic->id) }}">{{ $unit->topic->name }}</a></li>
				<li class="active">Edit Unit - {{ $unit->name }}</li>
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
		<h5 class="panel-title">Edit Unit - {{ $unit->name }}</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::units.update',$unit->id)) !!}

		{!! BootForm::bind($unit) !!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

        {!! BootForm::textarea('Description', 'description')
        			->attribute('rows',3)
        			->attribute('id','editor')  !!}

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

var editor;

$(document).ready(function(){

	editor = $('#editor').summernote({
		defaultFontName: 'Open Sans',

		toolbar: [
		    ['style', ['style']],
		    ['font', ['bold', 'italic', 'underline', 'clear']],
		    ['fontname', ['fontname']],
		    ['fontsize', ['fontsize']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['height', ['height']],
		    ['table', ['table']],
		    ['insert', ['link', 'picture', 'hr']],
		    ['view', ['fullscreen', 'codeview']],
		    ['help', ['help']]
		  ],

		fontNames: [
			'Open Sans'
		],

		fontNamesIgnoreCheck: [
			'Open Sans'
		]

	});

});
</script>

@stop