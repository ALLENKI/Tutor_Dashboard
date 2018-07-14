@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Teacher Certification</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li><a href="{{ route('admin::users::users.show',$user->id) }}">{{ $user->name }} ({{$user->email}})</a></li>
				<li class="active">Teacher: {{ $teacher->code }}</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop


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

	  <div class="chart-container has-scroll">
	      <div class="chart has-minimum-width" id="d3assessment"></div>
	  </div>
	</div>

</div>

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Certification</h5>
	</div>

	<div class="panel-body">

	@if($teacher->certifications->count())
		<table class="table">
			<thead>
				<tr>
					<th>Topic</th>
					<th>Type</th>
					<th>Assessed On</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				@foreach($teacher->certifications as $certification)
				<tr>
					<td>{{ $certification->topic->name }}</td>
					<td>{!! $certification->topic->present()->typeStyled !!}</td>
					<td>{{ $certification->created_at->format('jS M Y') }}</td>
					<td>
						<a href='{{ route('admin::users::teachers.remove_certification',$certification->id) }}' data-method='DELETE' class="rest">
							<i class='icon-cross'></i>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<h3 class="text-center">
			This teacher has no certification
		</h3>
	@endif

	</div>

</div>
<!-- /basic datatable -->

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Add Certification</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::users::teachers.add_certification',$teacher->id)) !!}

		{!! BootForm::select('Topic *', 'topic_id')
					->options($topics)
					->select('') 
					->attribute('required','true')
					->attribute('id','topic_id')
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

$(function(){

	$('#topic_id').select2();

    treeCollapsible('#d3assessment', 500, "{{ route('admin::users::teachers.graph',$teacher->id) }}");



});

</script>
@stop