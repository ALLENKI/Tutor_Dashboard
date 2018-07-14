<div class="modal-dialog modal-lg">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">{{ $topic->name }}</h5>
	</div>

	<div class="modal-body">

		<div class="row">
			<div class="col-md-10 col-md-offset-1" id="goal_network" style="height: 600px;">
				

			</div>
		</div>

	</div>
</div>
</div>


<script type="text/javascript">
	$(function(){


	});
</script>

<script type="text/javascript">


  function takeToCoursePage()
  {
    var selectedNodeID = goal_network.getSelection().nodes[0];
    // Get all node objects
    var selectedNode = goals_nodes.get(selectedNodeID);

    console.log(selectedNode,all_topics[selectedNodeID]);

    window.location.href = '{{ url('/dashboard/student/courses/') }}' + '/' + all_topics[selectedNodeID] + '/prerequisites';

  }

	// create an array with nodes
	var goals_nodes = new vis.DataSet({!! json_encode($nodes) !!})
	// create an array with edges
	var goals_edges = new vis.DataSet({!! json_encode($edges) !!});

	// create a network
	var goal_container = document.getElementById('goal_network');
	var goal_data = {
		nodes: goals_nodes,
		edges: goals_edges
	};

  var goal_options = {
    autoResize: true,
    height: '100%',
    width: '100%',

    layout: {
      // randomSeed:248881,
      improvedLayout:true,
      hierarchical: {
        enabled:false,
        levelSeparation: 200,
        nodeSpacing: 250,
        treeSpacing: 200,
        direction: 'UD',        // UD, DU, LR, RL
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
      // size: 50
    },
    physics:{
      enabled: false
    }
  }; 

	var goal_network = new vis.Network(goal_container, goal_data, goal_options);
	goal_network.fit()
  goal_network.on("click",takeToCoursePage);
</script>