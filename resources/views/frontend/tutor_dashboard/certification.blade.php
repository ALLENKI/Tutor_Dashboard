@extends('frontend.tutor_dashboard.master')

@section('subcontent')

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

</style>

<div id="d3assessment" class="hidden-xs hidden-sm">
</div>

@stop

@section('scripts')
@parent

<script>
$(function(){

      treeCollapsible('#d3assessment', 500, "{{ route('tutor::graph',$teacher->user->username) }}");
      
})
</script>

@stop