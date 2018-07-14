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
				<li class="active">Show Topic - {{ $topic->id }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<div class="panel panel-default">
	<div class="panel-heading">
		<h5 class="panel-title">Topic - {{ $topic->name }}</h5>

		<div class="heading-elements">
			@if($topicClasses->count())

			@else
			<a href="{{ route('admin::topic_tree::topics.edit',$topic->id) }}" class="btn btn-primary heading-btn">Edit</a>
			@endif
			{{--
			<a href="{{ route('admin::topic_tree::topics.delete',$topic->id) }}" data-method="DELETE" class="btn btn-danger heading-btn rest">Delete</a>
			--}}
		</div>

	</div>

	<div class="panel-body">

		<div class="row">

			<div class="col-md-4">
				<table class="table table-bordered">
					<tr>
						<td>Name:</td>
						<td>
						<a href="{{ url('classes-in-'.$topic->slug) }}">
							{{ $topic->name }}
						</a>
						
						</td>
					</tr>

					<tr>
						<td>Code:</td>
						<td>{{ $topic->code }}</td>
					</tr>

					<tr>
						<td>Type:</td>
						<td>{!! $topic->present()->typeStyled !!}</td>
					</tr>

					@if($topic->type == 'topic')

					<tr>
						<td>Level:</td>
						<td>{!! $topic->graph_level !!}</td>
					</tr>

					<tr>
						<td>Status:</td>
						<td>{!! $topic->present()->statusStyled !!}</td>
					</tr>

					<tr>
						<td>Google Slide:</td>
						<td>
							<a href="{{ $topic->google_slide }}" target="_blank"> {!! $topic->google_slide !!} </a></td>
					</tr>

					<tr>
						<td>Show on Homepage:</td>
						<td>
							{{ $topic->show_on_homepage }} </td>
					</tr>

					@endif

					<?php $up = $topic; ?>
					@while($up->parent)
					<?php $up = $up->parent; ?>
					<tr>
						<td>{!! $up->present()->typeStyled !!}</td>
						<td>			
							<a href="{{ route('admin::topic_tree::topics.show',$up->id) }}"> 
							{{ $up->name }} 
							</a>
						</td>
					</tr>
					@endwhile
				</table>
			</div>
			
			@if($topic->type == 'topic')


			<div class="col-md-5">
				
				{!! 
					BootForm::open()
					->action(route('admin::topic_tree::topics.upload_cover_picture',$topic->id))
					->attribute('enctype',"multipart/form-data")
				!!}
				
				{!! cl_image_tag($topic->present()->coverPicture,array('class' => 'img-responsive')) !!}

				@if(is_null($topic->coverPicture))
				<div class="alert alert-warning no-border">
				This topic has no cover image set.
				</div>
				@endif

				<hr>

				<div class="form-group {!! $errors->has('picture') ? 'has-error' : '' !!}">
					<input type="file" class="file-styled" name="cover_picture">
	            	{!! $errors->first('picture', '<p class="help-block">:message</p>') !!}
				</div>

				<hr>

				<div>
					<button type="submit" class="btn btn-primary btn-block">Upload Cover Picture <i class="icon-arrow-right14 position-right"></i></button>
				</div>

				{!! BootForm::close() !!}

			</div>


			<div class="col-md-3 pull-right">
				
				{!! 
					BootForm::open()
					->action(route('admin::topic_tree::topics.upload_picture',$topic->id))
					->attribute('enctype',"multipart/form-data")
				!!}
				

				{!! cl_image_tag($topic->present()->picture,array('class' => 'img-responsive')) !!}
				
				 

				@if($topic->picture == '')
				<div class="alert alert-warning no-border">
				This topic has no image set.
				</div>
				@endif

				<hr>

				<div class="form-group {!! $errors->has('picture') ? 'has-error' : '' !!}">
					<input type="file" class="file-styled" name="picture">
	            	{!! $errors->first('picture', '<p class="help-block">:message</p>') !!}
				</div>

				<hr>

				<div>
					<button type="submit" class="btn btn-primary btn-block">Upload Picture <i class="icon-arrow-right14 position-right"></i></button>
				</div>

				{!! BootForm::close() !!}

			</div>
			@endif

		</div>

	</div>

</div>

@if($topic->type == 'topic')
	@include('backend.topic_tree.topics.part_certified')
@endif


<div class="row">
	<div class="col-md-8 col-md-offset-2" id="network">
		

	</div>
</div>

@if(!is_null($topic->successor()))
	@include('backend.topic_tree.topics.part_children')
@endif

@if($topic->type == 'topic')
	@include('backend.topic_tree.topics.part_goals')
	@include('backend.topic_tree.topics.part_units')
	@include('backend.topic_tree.topics.part_prerequisites')
@endif

@include('backend.topic_tree.topics._students')


@stop


@section('styles')
@parent
<style type="text/css">
.select2-container {
    background: #fbf9f9;
}
</style>


<style type="text/css">
	#network{
		height: 600px;
	}
</style>
@stop

@section('scripts')
@parent

<script type="text/javascript">


$(document).ready(function(){

    $('.multiselect').multiselect({
    	enableFiltering: true,
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});



	$(".file-styled").uniform({
		fileButtonClass: 'action btn btn-default'
	});

    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

	$('#choose_prerequisite').select2();

    $("#children").sortable({
        items: ".child", 
        opacity: 0.8,
        update: function(b, c) {

            var data = $(this).sortable('serialize');   
            console.log(data);   
	        $.ajax({
	            data: data,
	            type: 'POST',
	            url: "{{ route('admin::topic_tree::topics.update_positions') }}"
	        });          
        }
    }).disableSelection();

    $("#units").sortable({
        items: ".unit", 
        opacity: 0.8,
        update: function(b, c) {

            var data = $(this).sortable('serialize');   
            console.log(data);   
	        $.ajax({
	            data: data,
	            type: 'POST',
	            url: "{{ route('admin::topic_tree::units.update_positions') }}"
	        });          
        }
    }).disableSelection();
});
</script>


<script type="text/javascript">

  function loadGraph(data)
  {
  	  // create an array with nodes
	  var nodes = new vis.DataSet(data.nodes);
	  // create an array with edges
	  var edges = new vis.DataSet(data.edges);

	  // create a network
	  var container = document.getElementById('network');
	  var data = {
	    nodes: nodes,
	    edges: edges
	  };

		var options = {
			interaction: {
			      navigationButtons: true,
					keyboard: {
				      enabled: false,
				      speed: {x: 10, y: 10, zoom: 0.3},
				      bindToWindow: true
				    },
			      zoomView: true
			},
		    layout: {
		      improvedLayout:true,
		      hierarchical: {
		        direction: 'DU',
		        sortMethod: "directed",
		        nodeSpacing: 500,
		      }
		    },
		    physics: {
		    	enabled: false
		    },
		    edges: {
		      smooth: true,
		      arrows: {to : true }
		    }
		  };	

		var network = new vis.Network(container, data, options);

  }

  $.get('{{ env('NODE_JS') }}'+'/rest/appdata/topicflow?id='+'{{ $topic->id }}',function(data){

  	if(data.data)
  	{
  		loadGraph(data.data);
  	}
  	

  });

</script>

@stop