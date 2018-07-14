@extends('dashboard.student.layouts.master')

@section('content')

@include('dashboard.student.courses.header')


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

var all_topics = {!! json_encode($all_topics) !!};

function takeToCoursePage()
{
  var selectedNodeID = network.getSelection().nodes[0];
  // Get all node objects
  var selectedNode = nodes.get(selectedNodeID);

  console.log(selectedNode,all_topics[selectedNodeID]);

  if(all_topics[selectedNodeID] !== undefined)
  {
  	  window.location.href = '{{ url('/dashboard/student/courses/') }}' + '/' + all_topics[selectedNodeID] + '/prerequisites';
  }

}

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

    network.on("click",takeToCoursePage);
</script>


@stop