@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Topic Tree</span> - Topics</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::topic_tree::topics.index') }}">Topics</a></li>
				<li class="active">Create New Topic</li>
			</ul>
		</div>

		<div class="heading-elements">
			<div class="heading-btn-group">

				<div class="btn-group">
                	<button type="button" class="btn bg-teal-400 btn-labeled dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><b><i class="icon-plus2"></i></b> Add New <span class="caret"></span></button>
                	<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'topic']) }}"><i class="icon-menu7"></i> Topic</a></li>
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'sub-category']) }}"><i class="icon-menu7"></i> Sub Category</a></li>
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'subject']) }}"><i class="icon-menu7"></i> Subject</a></li>
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'category']) }}"><i class="icon-menu7"></i> Category</a></li>
					</ul>
				</div>

				<a href="{{ route('admin::topic_tree::topics.index_d3') }}" class="btn btn-link btn-float has-text"><i class="icon-tree5 text-primary"></i><span>Graph Visualization</span></a>

			</div>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Create a New {{ $topic->typeOptions[$topic->type] }}</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>



		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::topics.store')) !!}

		

		@if($topic->predecessor())
		{!! BootForm::select("Parent * (".$topic->typeOptions[$topic->predecessor()].")", 'parent_id')
					->options($parents)
					->attribute('required','false')
		!!}
		@else
		{!! BootForm::hidden('parent_id')->attribute('value',0) !!}
		@endif

		{!! BootForm::hidden('type')->attribute('value',Input::get('type')) !!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}



		@if($topic->type == 'topic')

		{!! 
			BootForm::select('Graph Level *', 'graph_level')
					->options(['1' => '1', '2' => '2','3' => '3', '4' => '4', '5' => '5'])
					->select(Input::old('graph_level',$topic->graph_level)) 
					->attribute('required','true')
		!!}

		{!! BootForm::select('Status *', 'status')
					->options(['active' => 'Active', 'in_progress' => 'In Progress','in_future' => 'In Future', 'obsolete' => 'Obsolete'])
					->select(Input::old('status',$topic->status)) 
					->attribute('required','true')
		!!}
		
      	{!! BootForm::text('Minimum Enrollment *', 'minimum_enrollment')
              ->placeholder('Minimum Enrollment') !!}

  		{!! BootForm::text('Maximum Enrollment *', 'maximum_enrollment')
              ->placeholder('Maximum Enrollment') !!}

      	{!! BootForm::text('Google Slide', 'google_slide')
              ->placeholder('Google Slide') !!}

		{!! BootForm::textarea('Notes', 'notes')
        			->attribute('rows',3) !!}

        @else
        {!! BootForm::hidden('level')->attribute('value','1') !!}
        {!! BootForm::hidden('status')->attribute('value','1') !!}
        @endif

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

	$('#parent_id').select2();

});
</script>

@stop