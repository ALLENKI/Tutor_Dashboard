@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Topic Tree</span> - Topics</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin') }}">Home</a></li>
				<li><a href="{{ route('topic_tree::topics.index') }}">Topics</a></li>
				<li class="active">Create New Topic</li>
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
		<h5 class="panel-title">Create a New Topic</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('topic_tree::topics.store')) !!}

		{!! BootForm::select('Type *', 'type')
					->options(['' => 'Please Select','subject' => 'Subject', 'category' => 'Category',
								'sub-category' => 'Sub Category','topic' => 'Topic'])
					->select('') 
					->attribute('required','false')
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



});
</script>

@stop