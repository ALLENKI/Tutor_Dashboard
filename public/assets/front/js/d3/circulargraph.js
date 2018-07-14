radialtree("/assets/front/js/d3/topictree.json");

  function radialtree(json){

      var diameter = 500, i=0, duration=750;

      var tree = d3.layout.tree()
          .size([360, diameter / 2 -180 ])
          .separation(  function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

      var diagonal = d3.svg.diagonal.radial()
          .projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

      var svg = d3.select("#assessment").append("svg")
          .attr("width", diameter)
          .attr("height", diameter)
          .append("g")
          .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

  

      d3.json(json, function(error, jsondata) {
        if (error) throw error;
        root = jsondata;
        root.x0 = 180;
        root.y0 = 0;
        function collapse(d) {
          if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
          }
        }
        root.children.forEach(collapse);
        update(root);
      });

      function update(source) {

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse(),
            links = tree.links(nodes);

        // Normalize for fixed-depth.
        nodes.forEach(function(d) { d.y = d.depth * 180; });

        // Update the nodes
        var node = svg.selectAll("g.node")
            .data(nodes, function(d) { return d.id || (d.id = ++i); });
       
        var nodeEnter = node.enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) { return "rotate(" + (d.x -90) + ")translate(" + d.y + ")"; })
            .on("click", click)
             .append("a")
                  .attr("xlink:href", function(d){ if (d.url && d.depth==4){return d.url;} else if(!d.url && d.depth==4){  return deflink;}});

        nodeEnter.append("circle")
            .attr("r", 1e-6)
            .style("fill", function(d) { return d._children ? "blue" : "#fff"; });


        nodeEnter.append("text")
            .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
            .text(function(d) { return d.name; })
            .style("fill-opacity", 1e-6);


        var nodeUpdate = node.transition()
            .duration(duration)
            .attr("transform", function(d) { return "rotate(" + (d.x -90) + ")translate(" + d.y + ")"; });

        nodeUpdate.select("circle")
            .attr("r", function(d){return (10-1.2*d.depth)+"px";})
            .style("fill", function(d) { return d._children ? "blue" : "#fff"; });

        nodeUpdate.select("text")
            .style("fill-opacity", 1);


        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
            .duration(duration)
            .remove();

        nodeExit.select("circle")
            .attr("r", 1e-6);

        nodeExit.select("text")
            .style("fill-opacity", 1e-6);


        // Update the links
        var link = svg.selectAll("path.link")
            .data(links, function(d) { return d.target.id; });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("d", function(d) {
              var o = {x: source.x0, y: source.y0};
              return diagonal({source: o, target: o});
            });

        // Transition links to their new position.
        link.transition()
            .duration(duration)
            .attr("d", diagonal)
            .style("stroke-width", function(d) { return (10-2*d.target.depth) + "px"; });
            
        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
            .duration(duration)
            .attr("d", function(d) {
              var o = {x: source.x, y: source.y};
              return diagonal({source: o, target: o});
            })
            .remove();

        // Stash the old positions for transition.
        nodes.forEach(function(d) {
          d.x0 = d.x;
          d.y0 = d.y;
        });
      }
     
      // Toggle children on click.
      function click(d) {
        if (d.children) {
          d._children = d.children;
          d.children = null;
        } else {
          d.children = d._children;
          d._children = null;
        }
        update(d);
      }

      d3.select(self.frameElement).style("height", diameter - 150 + "px");
}
