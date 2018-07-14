@extends('backend.layouts.master')

@section('styles')
@parent
<style type="text/css">
  .node {
    cursor: pointer;
  }

  .overlay{
      background-color:#EEE;
  }
   
  .node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 1.5px;
  }
   
  .node text {
    font-size:10px; 
    font-family:sans-serif;
  }
   
  .link {
    fill: none;
    stroke: #ccc;
    stroke-width: 1.5px;
  }

  .templink {
    fill: none;
    stroke: red;
    stroke-width: 3px;
  }

  .ghostCircle.show{
      display:block;
  }

  .ghostCircle, .activeDrag .ghostCircle{
       display: none;
  }
</style>
@stop

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Topic Tree</span> - Topics</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
        <li><a href="{{ route('admin::topic_tree::topics.index') }}">All Topics</a></li>
				<li class="active">Topics Tree Graph Visualization</li>
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


			</div>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

	<div class="panel panel-flat">
		<div class="panel-heading">
			<h6 class="panel-title">Topics Tree Graph Visualization</h6>

      <div class="heading-elements">
        <button class="btn btn-primary heading-btn" id="collapse">Collapse</button>
        <button class="btn btn-primary heading-btn" id="expand">Expand</button>
      </div>
		</div>

		<div class="panel-body">
      
      {!! BootForm::open()->attribute('method','GET') !!}


      {!! BootForm::text('Search *', 'q')
              ->placeholder('Search')
              ->attribute('value',Input::get('q',''))
              ->hideLabel() !!}

      {!! BootForm::close() !!}

      <div class="chart-container has-scroll">
          <div class="chart has-minimum-width" id="d3assessment"></div>
      </div>
		</div>

	</div>

@stop


@section('scripts')
@parent

<script type="text/javascript">
$(document).ready(function(){

  treeCollapsible('#d3assessment', 600, "{{ route('admin::topic_tree::topics.table_d3',['q' => $query]) }}");

});
</script>

@stop