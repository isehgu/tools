
function main(){
    var w = 1000;
    var h = 600;
    var margin = 90;
    var y_margin = 10;
    var x_axis_rotation = -35;

    var dataset = null;
    $.when( get_data() ).done(function(data){
        dataset = JSON.parse(data);
    });

    // console.log(d3.select("body"));
    var client_w = d3.select("body")[0]["0"]["clientWidth"];
    var offset = (client_w - w) / 2;

    var max = 0;
    for(var i = 0; i < dataset.length; i++){
        test_count = parseInt(dataset[i]["test_count"]);
        max = (test_count > max)?test_count:max;
    }
    max = Math.ceil(max/50) * 50;

    var xScale = d3.scale.ordinal()
        .domain(d3.range(dataset.length))
        .rangeRoundBands([40, w-60], 0.05);

    var yScale = d3.scale.linear()
        .domain([0, max])
        .range([h-margin, 0]);

    var x_axis = d3.svg.axis().scale(xScale)
        .tickFormat(function(d){
            return dataset[d]["start_date"]+" - "+dataset[d]["end_date"];
        });

    var y_axis = d3.svg.axis().scale(yScale).orient("left");

    //Create SVG element
    var svg = d3.select("#chart-div")
        .append("svg")
        .attr("width", w)
        .attr("height", h)
        .attr("x", "500px")
        .append("g")
        .attr("transform", "translate(40,"+y_margin+")");

    d3.select("svg")
        .append("g")
        .attr("class","x axis")
        .attr("transform","translate(40,"+(h-margin+y_margin)+")")
        .call(x_axis)
        .selectAll("text")
        .style("text-anchor", "end")
        .attr("transform", function(d){return "rotate("+x_axis_rotation+")";});

    d3.select("svg")
        .append("g")
        .attr("class","y axis")
        .attr("transform","translate("+80+","+y_margin+")")
        .call(y_axis);

    var tooltipdiv = d3.select("#chart-div").append("div")
        .attr("class", "tooltip")
        .style("opacity", 0);

    var space_above = $("svg").position().top;

    //Create bars
    svg.selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("x", function(d, i){
            return xScale(i);
        })
        .attr("y", function(d, i){
            return yScale(d["test_count"]);
        })
        .attr("width", xScale.rangeBand())
        .attr("height", function(d, i) {
            return (yScale(0) - yScale(d["test_count"]));
        })
        .attr("fill", function(d, i){
            return "rgb(96, 0, " + (d["test_count"] * 2) + ")";
        })
        //Tooltip
        .on("mouseover", function(d){
            var xPosition = parseFloat(d3.select(this).attr("x")) + xScale.rangeBand() / 2 + offset;
            var yPosition = parseFloat(d3.select(this).attr("y")) + space_above + 9;

            tooltipdiv.transition()
            .duration(300)
            .style("opacity", 1);
            tooltipdiv.html(d["test_count"]+" tests performed<br>between "+d["start_date"]
                +" and "+d["end_date"])
            .style("left", xPosition+60+"px")
            .style("top", yPosition+"px")
            .style("color", "white")
            .style("font-family", "sans-serif")
            .style("font-size", "14px")
            .style("padding", "5px")
            .style("background-color", d3.select(this).attr("fill"))
            .style("border-style", "solid")
            .style("border-color", "white")
            .style("border-width", "1px");
            })
        .on("mouseout", function(d) {
            tooltipdiv.transition()
                .duration(300)
                .style("opacity", 0);
        })
        ;

    //Create labels
    svg.selectAll("text")
        .data(dataset)
        .enter()
        .append("text")
        .text(function(d) {
            return d["test_count"];
        })
        .attr("text-anchor", "middle")
        .attr("x", function(d, i){
            return xScale(i) + xScale.rangeBand() / 2;
        })
        .attr("y", function(d, i){
            return (parseInt(d["test_count"]) < 14)
                ? yScale(d["test_count"]) - 2
                : yScale(d["test_count"]) + 14;
        })
        .attr("font-family", "sans-serif")
        .attr("font-size", "12px")
        .attr("fill", function(d,i){
            return (parseInt(d["test_count"]) < 14)
                ? "black"
                : "white";
        });

    svg.append("text")
        .attr("x", w / 2 )
        .attr("y", 20)
        .style("text-anchor", "middle")
        .text("TAC Usage")
        .attr("font-size", "30px");

    //Create X axis label
    svg.append("text")
        .attr("x", w / 2 )
        .attr("y",  yScale(0) + 70 )
        .text("Date Range (Week)")
        .attr("font-size", "16px")
        .attr("text-anchor", "middle");

    //Create Y axis label
    svg.append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", xScale(0) - 50 )
        .attr("x", 0 - (h / 2))
        .text("Number of Tests")
        .attr("font-size", "16px")
        .attr("text-anchor", "middle");
}

function get_data(){
    return $.ajax({
        type: 'get',
        url:  'http://asg.ise.com/tac/data/data_gui.php',
        async: false,
    });
}

function getPos(el) {
    // yay readability
    for (var lx=0, ly=0;
         el != null;
         lx += el.offsetLeft, ly += el.offsetTop, el = el.offsetParent);
        return {x: lx,y: ly};
}
