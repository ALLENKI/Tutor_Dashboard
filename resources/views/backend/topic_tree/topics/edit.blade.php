@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Topic Tree</span> - Topics - {{ $topic->name }}</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::topic_tree::topics.index') }}">All Topics</a></li>
				<li><a href="{{ route('admin::topic_tree::topics.show',$topic->id) }}">{{ $topic->name }}</a></li>
				<li class="active">Edit Topic - {{ $topic->name }}</li>
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
		<h5 class="panel-title">Edit Topic - {{ $topic->name }}</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::topics.update',$topic->id)) !!}

		{!! BootForm::bind($topic) !!}

		@if($topic->type != 'category')
		{!! BootForm::select('Parent *', 'parent_id')
					->options($parents)
					->attribute('required','false')
					->attribute('id','parent_id')
		!!}
		@else
		{!! BootForm::hidden('parent_id')->attribute('value',0) !!}
		@endif

		{!! BootForm::select('Type *', 'type')
					->options(['subject' => 'Subject', 'category' => 'Category',
								'sub-category' => 'Sub Category','topic' => 'Topic'])
					->select(Input::old('type',$topic->type)) 
					->attribute('required','false')
					->attribute('readonly','false')
		!!}

		{!! 
			BootForm::select('Level *', 'graph_level')
					->options(['1' => '1', '2' => '2','3' => '3', '4' => '4', '5' => '5'])
					->select(Input::old('graph_level',$topic->graph_level)) 
					->attribute('required','true')
		!!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

        @if($topic->type == 'topic')

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

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="show_on_homepage">
		</label>
		<div class="col-sm-8 col-lg-10">
		
			<label class="checkbox-inline">
				<input type="checkbox" class="styled" name="show_on_homepage" value="yes" {{ $topic->show_on_homepage ? 'checked' : '' }}>
				Show on homepage
			</label>

		</div>
		</div>


  		{!! BootForm::text('Tags', 'tags')
              ->placeholder('Tags') 
              ->attribute('id','tags')
              ->attribute('value',$topic->tagList)
        !!}


		{!! BootForm::textarea('Notes', 'notes')
        			->attribute('rows',3) !!}

        @endif

        {!! BootForm::textarea('Description', 'description')
        			->attribute('rows',3) !!}

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

	$('#tags').tagsInput({
		width: 'auto',
		autocomplete_url:'/api/tags'
	});

});
</script>

@stop