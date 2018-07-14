@extends('dashboard.student.layouts.master')

@section('content')

{{-- <div class="page-header page-header-inverse has-cover" style="    background: url(http://res.cloudinary.com/ahamlearning/image/upload/c_scale,h_860,l_black_opacity_60_sgz5cd,w_1841/v1466663558/Collaboration-Technology_zncjmr.jpg);background-size: cover;background-position-y: -135px;">
	<div class="page-header-content">
		<div class="page-title" style="padding: 52px 36px 52px 0;">
			<h2 style="font-size: 36px;">
				<span class="text-bold">
				Hi, {{ $student->user->name }} !
				</span>
			</h2>
		</div>
	</div>
</div> --}}


<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title">Your Assessment in a Graph</h6>

	</div>

	<div class="panel-body">
      <div class="chart-container has-scroll">
          <div class="chart has-minimum-width" id="d3assessment"></div>
      </div>
	</div>

</div>

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

@section('scripts')
@parent

<script type="text/javascript">
$(document).ready(function(){

  treeCollapsible('#d3assessment', 500, "{{ route('student::graph') }}");

});
</script>

@stop