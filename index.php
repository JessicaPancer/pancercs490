<html>
 <link rel="stylesheet" type="text/css" href="abroad.css" />
 <head>
  <title>PHP Test</title>
 </head>
<center>
	<h1 id="title"> Expanding Our Studies Globally </h1>
	<p> This chart shows where students with different majors attend study abroad programs  during a semester. <br>
	The left part of the circle contains the different majors and the right side lists the different countries. <br>
	The thickness of a section represents the number of students. Hover over a section to see this relationship clearer.</p>
</center>
<svg width="500" height="500"></svg>
<div id="flickr-images"></div>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <?php 
        $db = pg_connect('host=ec2-54-235-182-120.compute-1.amazonaws.com dbname=d1hbm7ist1cc38 user=oqqwodgjyuhqyv password=xqQSoIO5gbPfUOVGPTp05Orni2'); 

        $query1 = "SELECT DISTINCT country, region FROM semester3 ORDER BY region ASC, country ASC"; 
        $query2 = "SELECT DISTINCT major FROM semester3 ORDER BY major DESC";

        $cresult = pg_query($query1);
        $mresult = pg_query($query2);
	
 $carr = pg_fetch_all($cresult);
 $marr = pg_fetch_all($mresult);
 
 $crows = pg_num_rows($cresult);
 $mrows = pg_num_rows($mresult);
 ?>
<script type="text/javascript">
	var countries = <?php echo json_encode($carr) ?>;
	var majors = <?php echo json_encode($marr) ?>;
	var Names = [];
	var crows = <?php echo $crows; ?>;
	var mrows = <?php echo $mrows; ?>;
	for (i = 0; i < crows; i++) {
		Names.push(countries[i]['country']);
	}
	Names.push("");
	for (j = 0; j < mrows; j++) {
		Names.push(majors[j]['major']);
	}
	Names.push("");
	Names.push("");
	 
 <?php 
 if ($cresult && $mresult) {
  $atotal=array();
  while ($row = pg_fetch_row($cresult)) {
   $c = $row[0];
   $apart=array();
   for ($j=0; $j<$mrows+$crows+1; $j++) {
    if ($j<$crows+1) {
    	$apart[]=0;
    } else {
	$m = pg_fetch_array($mresult, $j-$crows-1);
        $query3 = "SELECT COUNT(*) FROM semester3 WHERE country = '$c' AND major = '$m[0]'";
        $num = pg_fetch_array(pg_query($query3));
        $apart[]=intval($num[0]);
    }
   }   	  
   $apart[]=0;
   $atotal[]=$apart;
  }
 }
 ?>
 var a = <?php echo json_encode($atotal) ?>;
 console.log(a);
	console.log("welp");
  
  var respondents = 63, //Total number of respondents (i.e. the number that makes up the group)
  emptyPerc = 0.4, //What % of the circle should become empty in comparison to the visible arcs
  emptyStroke = Math.round(respondents*emptyPerc); //How many "units" would define this empty percentage
  
  var bpart = [];
  for (i=0;i<crows+1+mrows;i++) {
    bpart.push(0);
  }
  bpart.push(emptyStroke);
  var b = [bpart];
  
 <?php 
 if ($cresult && $mresult) {
  $ctotal=array();
  for ($i=0; $i<$mrows; $i++) {
   $row = pg_fetch_array($mresult, $i);
   $m = $row[0];
   $cpart=array();
   for ($j=0; $j<$crows; $j++) {
    $c = pg_fetch_array($cresult, $j);
    $query3 = "SELECT COUNT(*) FROM semester3 WHERE country = '$c[0]' AND major = '$m'";
    $num = pg_fetch_array(pg_query($query3));
    $cpart[]=intval($num[0]);
   }
   for ($j=0; $j<$mrows+2; $j++) {
  	 $cpart[]=0;
   }
   $ctotal[]=$cpart;
  }
 }
 ?>
var c = <?php echo json_encode($ctotal) ?>;
var test1 = <?php echo json_encode($crows) ?>;
var test2 = <?php echo json_encode($mrows) ?>;
 console.log(test1);
console.log(test2);
console.log("blaht");
  
	
var fpart = [];
  for (i=0;i<crows;i++) {
    fpart.push(0);
  }
  fpart.push(emptyStroke);
  for (i=0;i<mrows+1;i++) {
    fpart.push(0);
  }
  var f = [fpart];
  
  var matrix = a.concat(b).concat(c).concat(f);
  console.log(matrix);
 
//Calculate how far the Chord Diagram needs to be rotated clockwise
//to make the dummy invisible chord center vertically
var offset = Math.PI * (emptyStroke/(respondents + emptyStroke+30)) / 2;
var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height"),
    outerRadius = Math.min(width, height) * 0.5 - 40,
    innerRadius = outerRadius - 30,
    opacityDefault = 0.7, //default opacity of chords
    opacityLow = 0.02; //hover opacity of those chords not hovered over
var formatValue = d3.formatPrefix(",.0", 1e3);
var chord = d3.chord()
    .padAngle(0.05)
//Include the offset in de start and end angle to rotate the Chord diagram clockwise
function startAngle(d) { return d.startAngle + offset; }
function endAngle(d) { return d.endAngle + offset; }
var arc = d3.arc()
    .innerRadius(innerRadius)
    .outerRadius(outerRadius)
    .startAngle(startAngle)
    .endAngle(endAngle);
var ribbon = d3.ribbon()
    .radius(innerRadius)
    .startAngle(startAngle)
    .endAngle(endAngle);
var color = d3.scaleLinear()
    .domain([0, 31, 35, 40, 45, 50, 55, 61])
    .range(["black", "red", "orange", "yellow", "green", "aqua", "blue", "purple"]);
var g = svg.append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")")
    .attr("id", "circle")
    .datum(chord(matrix));
var group = g.append("g")
    .attr("class", "groups")
  .selectAll("g")
  .data(function(chords) { return chords.groups; })
  .enter().append("g")
  .on("mouseover", fade(opacityLow))
  .on("mouseout", fade(opacityDefault));
var pullOutSize = 50;
group.append("path")
    .style("fill", function(d,i) { 
	if (Names[i] === "") {
		return "none";
	} else if (d.index < 31) {
		return d3.rgb(0,0,0);
	} else {
		return d3.rgb(color(d.index)).darker();
	}
    })
    .attr("d", arc);
group.append("text")
//Slightly altered function where the offset is added to the angle
        .each(function(d) { d.angle = ((d.startAngle + d.endAngle) / 2) + offset;})
  .attr("dy", ".35em")
  .attr("class", "titles")
  .attr("text-anchor", function(d) { return d.angle > Math.PI ? "end" : null; })
  .attr("transform", function(d,i) { 
    var c = arc.centroid(d);
    return "rotate(" + (d.angle * 180 / Math.PI - 90) + ")"
    + "translate(" + (innerRadius + 55) + ")"
    + (d.angle > Math.PI ? "rotate(180)" : "")
  })
  .text(function(d,i) { return Names[i]; });
g.append("g")
    .attr("class", "ribbons")
  .selectAll("path")
  .data(function(chords) { return chords; })
  .enter().append("path")
    .attr("class", "chord")
    .attr("d", ribbon)
    .style("fill", function(d) { return (Names[d.target.index] === "" ? "none" : color(d.target.index)); })
    .style("stroke", function(d) { return (Names[d.target.index] === "" ? "none" : d3.rgb(color(d.target.index)).darker()); })
	.style("opacity", function(d) { return (Names[d.source.index] === "" ? 0 : opacityDefault); }) //Make the dummy strokes have a zero opacity (invisible)
	.style("pointer-events", function(d,i) { return (Names[d.source.index] === "" ? "none" : "auto"); }); //Remove pointer events from dummy strokes
function fade(opacity) {
  return function(d, i) {
	svg.selectAll("path.chord")
		.filter(function(d) { return d.source.index !== i && d.target.index !== i && Names[d.source.index] !== ""; })
		.transition("fadeOnArc")
		.style("opacity", opacity);
  };
}
</script>
<hr width="33%">
<center>
	<p id="Searchheader">View other people's study abroad photos:</p>
	<form action="photos.php">
		<input type="text" name="place" maxlength=100 placeholder="Search a location">
		<br>
		<input type="submit" value="Search">
	</form>
</center>
</html>
