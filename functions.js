// Scalable Vector Graphics object
var svg;
// Nodes list
var nodes = [];

$(document).ready(function() {
    $("#search_btn").prop('disabled', true);
});

$('#discover_btn').click(function() {
    discover();
});

$('#search_btn').click(function() {
	display_node(document.getElementById('search_input').value);
});

// Run algorythm to discover nodes/links and create map
function discover() {
    jQuery.ajax({
        url: 'data_mining.php',
        beforeSend: function(jqXHR, settings) {
            $("#notif").html("");
            $("svg").remove();
            $("#discover_btn").button('loading');
            $("#discover_btn").prop('disabled', true);
            $("#search_btn").prop('disabled', true);
        },
        success: function(data, textStatus, jqXHR) {
            $("#notif").html(data);
            $("#discover_btn").button('reset');
            $("#search_btn").prop('disabled', false);
            rendering();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Error (" + errorThrown + ": " + textStatus + ")");
        }
    });
}

// From twitter typeahead.js examples
// Return matching nodes
var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;

		// Array that will be populated with substring matches
        matches = [];

        // Regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

        // Iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });

        cb(matches);
    };
};

// Typeahead init
var typeahead = $('#search_input').typeahead({
    hint: true,
    highlight: true,
}, {
    name: 'nodes',
    limit: 8,
    source: substringMatcher(nodes)
});

// Display the selected node
typeahead.on('typeahead:selected', function(evt, data) {
    display_node(data);
});

// Create D3JS network map
function rendering() {
	// Layout scale
    var width = 1400,
        height = 1600;

    // Set colors
    var color = d3.scale.category20();

    // Set force layout
    var force = d3.layout.force()
        .charge(-400)
        .linkDistance(150)
        .size([width, height]);

    // Add svg to the page
    svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height);

    // Read json data
    $.getJSON('./data/snmp_data.json', function(data) {
        graph = data;

        // Create nodes and links
        force.nodes(graph.nodes)
            .links(graph.links)
            .start();

        // Create lines
        var link = svg.selectAll(".link")
            .data(graph.links)
            .enter().append("line")
            .attr("class", "link")
            .style("stroke-width", function(d) {
                return Math.sqrt(d.value);
            });

        // Create circles
        var node = svg.selectAll(".node")
            .data(graph.nodes)
            .enter().append("g")
            .attr("class", "node")
            .call(force.drag);

        node.append("circle")
            .attr("r", 8)
            .style("fill", function(d) {
                return color(d.group);
            })

        node.append("text")
            .attr("dx", 10)
            .attr("dy", ".35em")
            .text(function(d) {
                return d.name
            });

        // Generating coordinates
        force.on("tick", function() {
            link.attr("x1", function(d) {
                    return d.source.x;
                })
                .attr("y1", function(d) {
                    return d.source.y;
                })
                .attr("x2", function(d) {
                    return d.target.x;
                })
                .attr("y2", function(d) {
                    return d.target.y;
                });

            d3.selectAll("circle").attr("cx", function(d) {
                    return d.x;
                })
                .attr("cy", function(d) {
                    return d.y;
                });

            d3.selectAll("text").attr("x", function(d) {
                    return d.x;
                })
                .attr("y", function(d) {
                    return d.y;
                });
        });

		// Get nodes name
        for (var i = 0; i < graph.nodes.length - 1; i++) {
            nodes.push(graph.nodes[i].name);
        }

        nodes = nodes.sort();
    });
}

// Display the node 'node_mame'
function display_node(node_name) {
    if($.inArray(node_name, nodes) == -1) {
        return false;
    }

    var node = svg.selectAll(".node");

    if (node_name == "none") {
        node.style("stroke", "white").style("stroke-width", "1");
    } else {
        var selected = node.filter(function(d, i) {
            return d.name != node_name;
        });
        selected.style("opacity", "0");
        var link = svg.selectAll(".link")
        link.style("opacity", "0");
        d3.selectAll(".node, .link").transition()
            .duration(5000)
            .style("opacity", 1);
    }
}
