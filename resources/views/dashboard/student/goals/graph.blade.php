@section('scripts')
@parent

<script type="text/javascript">

var goal_page_info = "{{ url('/dashboard/student/courses/for_goal_page/') }}";

function highlightConnectedNodes()
{
  var selectedNodeID = network.getSelection().nodes[0];
  // Get all node objects
  var selectedNode = nodes.get(selectedNodeID);

  var connectedNodes = network.getConnectedNodes(selectedNodeID);
  var connectedEdges = network.getConnectedEdges(selectedNodeID);
  connectedNodes.push(selectedNodeID);

  // console.log(edges_data);

  network.selectNodes(connectedNodes);

  var filtered_nodes_data = nodes_data.filter(function(el){
    return contains.call(connectedNodes, el.id);
  });

  var filtered_edges_data = edges_data.filter(function(el){
    return contains.call(connectedNodes, el.from) || contains.call(connectedNodes, el.to);
  });

  var params = {nodes: filtered_nodes_data,edges:filtered_edges_data};

  // console.log(jQuery.param( params ));

  if(selectedNodeID == 0)
  {
    return false;
  }

  openAjaxModalViaUrl(goal_page_info+'/'+selectedNodeID, params);

}

  var network;
  var nodes;
  var edges;

  var nodes_data;
  var edges_data;


  function loadGraph(data)
  {

    nodes_data = data.nodes;
    edges_data = data.edges;
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


  $.get('{{ env('NODE_JS') }}'+'/rest/appdata/studentassessmentgoal?goalId='+'{{ $goal->id }}'+'&studentId='+'{{ $student->id }}',function(data){

    loadGraph(data.data);

  });
</script>

@stop