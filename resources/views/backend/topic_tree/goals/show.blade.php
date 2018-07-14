@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Goals Management</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::topic_tree::goals.index') }}">All Goals</a></li>
				<li class="active">Edit Goal</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop


@section('content')

<div class="row">
  <div class="col-md-10 col-md-offset-1" id="mynetwork">
    

  </div>

  <div class="col-md-10 col-md-offset-1">
    <h1 id="message"></h1>
  </div>
</div>

@stop

@section('styles')
@parent
<style type="text/css">
    #mynetwork {
      height: 500px;
      border: 1px solid lightgray;
      background:#d1d1d1;
    }
    p {
      max-width:600px;
    }
</style>
@stop

@section('scripts')
@parent

<script type="text/javascript">

var network;
var nodes;
var edges;

function highlightConnectedNodes()
{
  var selectedNodeID = network.getSelection().nodes[0];
  // Get all node objects
  var selectedNode = nodes.get(selectedNodeID);

  var connectedNodes = network.getConnectedNodes(selectedNodeID);
  connectedNodes.push(selectedNodeID);

  // console.log(connectedNodes);

  network.selectNodes(connectedNodes);

  $('#message').html('Selected Node: '+selectedNodeID);
  // Select Nodes
}


  function loadGraph(data)
  {
        // create an array with nodes
        nodes = new vis.DataSet(data.nodes);
        // create an array with edges
        edges = new vis.DataSet(data.edges);

        // create a network
        var container = document.getElementById('mynetwork');
        var data = {
          nodes: nodes,
          edges: edges
        };

        var options = {
          autoResize: true,
          height: '100%',
          width: '100%',

          layout: {
            // randomSeed:248881,
            improvedLayout:true,
            hierarchical: {
              enabled:true,
              levelSeparation: 300,
              nodeSpacing: 150,
              treeSpacing: 200,
              direction: 'DU',        // UD, DU, LR, RL
              sortMethod: 'directed'   // hubsize, directed
            }
          },

          interaction: {
            navigationButtons: true,
            keyboard: {
                enabled: false,
                speed: {x: 10, y: 10, zoom: 0.3},
                bindToWindow: true
              },
            zoomView: true,
            hover: true
          },
          nodes : {
            shape: 'dot',
            size: 50
          },
          physics:{
            enabled: false
          }
        };  

        network = new vis.Network(container, data, options);

        network.on("click",highlightConnectedNodes);

        network.on("hoverNode",function(options){
          var selectedNodeID = options.node;

          var connectedNodes = network.getConnectedNodes(selectedNodeID);
          connectedNodes.push(selectedNodeID);

          // console.log(connectedNodes);

          network.selectNodes(connectedNodes);
        });

  }


  $.get('{{ env('NODE_JS') }}'+'/rest/appdata/goalflow?id='+'{{ $goal->id }}',function(data){

    loadGraph(data.data);

  });
</script>

@stop