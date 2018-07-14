@extends('dashboard.student.layouts.master')

@section('content')

@include('dashboard.student.class.header')


<div class="row">
	<div class="col-md-10 col-md-offset-1" id="network">
		

	</div>
</div>


@stop

@section('styles')
@parent
<style type="text/css">
	#network{
		height: 600px;
	}
</style>

@stop

@section('scripts')
@parent
<script type="text/javascript">
	// create an array with nodes
	var nodes = new vis.DataSet({!! json_encode($nodes) !!})
	// create an array with edges
	var edges = new vis.DataSet({!! json_encode($edges) !!});

	// create a network
	var container = document.getElementById('network');
	var data = {
		nodes: nodes,
		edges: edges
	};

	var options = {
		interaction:{hover:true},
		nodes: {
		    shadow: true,
		    font:{
		    	size: 18,
		    }
		},
		edges: {
		    shadow:true,
		    length: 100
		},
		width: '100%',
		layout:{randomSeed:{{$topic->id}}}
	};

	var network = new vis.Network(container, data, options);

	network.on("showPopup", function (params) {
        console.log('showPopup');
    });
</script>
@stop