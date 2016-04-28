
d3.json('get_friends.php', function(error, graph) {
// some colour variables
var tcBlack = "#130C0E";

// rest of vars
var w = $("#map").width(),
    h = 400,
    maxNodeSize = 50,
    x_browser = 20,
    y_browser = 25,
    root;

var vis;
var force = d3.layout.force();

vis = d3.select("#map")
    .append("svg")
    .attr("width", w)
    .attr("height", h);

  root = graph;
  root.fixed = true;
  root.x = w / 2;
  root.y = h / 4;


// Build the path
var defs = vis
    .insert("svg:defs")
    .append("clipPath")
    .attr("id", "circle-clip")
    .append("circle")
    .attr("r", 25);

vis.select("defs")
    .append("clipPath")
    .attr("id", "temp-circle-clip")
    .append("circle")
    .attr("r", 25);

var linkedByIndex = {};
graph.links.forEach(function(d) {
    linkedByIndex[d.source.index + "," + d.target.index] = 1;
});

function isConnected(a, b) {
    return linkedByIndex[a.index + "," + b.index] || linkedByIndex[b.index + "," + a.index];
}

update();


/**
 *
 */
function update() {
    var nodes = root.nodes,
        links = root.links;

    // Restart the force layout.
    force.nodes(nodes)
      .links(links)
      .gravity(0.05)
      .charge(-1500)
      .linkDistance(100)
      .friction(0.5)
      .linkStrength(1)
      .size([w, h])
      .on("tick", tick)
          .start();

     var path = vis
        .selectAll("path.link")
        .data(links);

      path
        .enter()
        .insert("svg:path")
        .attr("class", "link")
        // .attr("marker-end", "url(#end)")




    // Update the nodes…
    var node = vis.selectAll("g.node")
        .data(nodes, function(d) { return d.id; });

    // Enter any new nodes.
    var nodeEnter = node.enter().append("svg:g")
        .attr("class", "node")
        .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
        .on("click", click)
        .call(force.drag);

    // Append a circle
    var circles = nodeEnter.append("svg:circle")
        .attr("r", 25)
        .attr("stroke", "black")
        .attr("stroke-width", "2px")
        .style("fill", "grey");

    // Append images
    var images = nodeEnter.append("svg:image")
        .attr("class", "node-image")
        .attr("clip-path", "url(#circle-clip)")
          .attr("xlink:href",  function(d) { return d.avatar;})
          .attr("x", -25)
          .attr("y", -25)
          .attr("height", 50)
          .attr("width", 50);

    // make the image grow a little on mouse over and add the text details on click
    images
        .on('dblclick', function(d) {
            window.location.href = 'profile.php?user=' + d.id;
        })
        .on( 'mouseenter', function(d) {
          // select element in current context
          d3.select( this )
            .attr("clip-path", "url(#temp-circle-clip)")
            .transition()
            .attr("x", -50)
            .attr("y", -50)
            .attr("height", 100)
            .attr("width", 100);

            d3.select("#temp-circle-clip circle")
                .transition()
                .attr("r", 50);

            d3.select( this.previousSibling )
                .transition()
                .attr("r", 52);

            path
                .classed("link-active", function(o) {
                    return o.source === d || o.target === d ? true : false;
                });
        })
        // set back
        .on( 'mouseleave', function() {
          d3.select( this )
            .transition()
            .attr("x", -25)
            .attr("y", -25)
            .attr("height", 50)
            .attr("width", 50);

            d3.select("#temp-circle-clip circle")
                .transition()
                .attr("r", 25)
                .each("end", function() {
                    d3.selectAll( $("[clip-path='url(#temp-circle-clip)']") )
                        .attr("clip-path", "url(#circle-clip)");
                });

            d3.select( this.previousSibling )
                .transition()
                .attr("r", 25);

            path.classed("link-active", false);
        });

    nodeEnter.append("text")
        .attr("class", "nodetext")
        .attr("x", 45)
        .attr("y", 45)
        .attr("fill", tcBlack)
        .text(function(d) { return d.name; });
    nodeEnter.append("text")
        .attr("class", "nodetext")
        .attr("x", 45)
        .attr("y", 60)
        .attr("fill", tcBlack)
        .text(function(d) { return d.company; });


    // Exit any old nodes.
    node.exit().remove();


    // Re-select for update.
    path = vis.selectAll("path.link");
    node = vis.selectAll("g.node");

    function tick() {

        path.attr("d", function(d) {

        var dx = d.target.x - d.source.x,
            dy = d.target.y - d.source.y,
               dr = Math.sqrt(dx * dx + dy * dy);
               return   "M" + d.source.x + ","
                + d.source.y
                + "A" + dr + ","
                + 0 + " 0 0,1 "
                + d.target.x + ","
                + d.target.y;
      });
        node.attr("transform", nodeTransform);
    }
}


/**
 * Gives the coordinates of the border for keeping the nodes inside a frame
 * http://bl.ocks.org/mbostock/1129492
 */
function nodeTransform(d) {
    d.x =  Math.max(maxNodeSize, Math.min(w - (d.imgwidth/2 || 16), d.x));
    d.y =  Math.max(maxNodeSize, Math.min(h - (d.imgheight/2 || 16), d.y));
    return "translate(" + d.x + "," + d.y + ")";
}

/**
 * Toggle children on click.
 */
function click(d) {
    if (d.children) {
        d._children = d.children;
        d.children = null;
    }
    else {
        d.children = d._children;
        d._children = null;
    }

    update();
}

});