var margin = {top: 20, right: 50, bottom: 30, left: 50},
    width = $("#views").width() - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;

var parseDate = d3.time.format("%b-%y").parse;

var x = d3.time.scale()
    .range([0, width]);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .ticks(Math.round(width * 0.00877192982))
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");

var line = d3.svg.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.views); });

var svg = d3.select("#views")
    .append("svg")
    .attr("width", "100%")
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

d3.json('get_views.php?user=' + uid, function (error, data) {
   data.forEach(function(d) {
        d.date = parseDate(d.date);
        d.views = +d.views;
   });

x.domain(d3.extent(data, function(d) { return d.date; }));
y.domain([0, d3.max(data, function(d) { return d.views; })]);

svg.append("path")
    .datum(data)
    .attr("class", "line")
    .attr("d", line);

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);

svg.append("g")
    .attr("class", "y axis")
    .call(yAxis)
    .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end")
    .text("Views");
});