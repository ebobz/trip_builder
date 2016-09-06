<?php

// header("Location: /browser");
?>

<html>
<meta charset="UTF-8">
<head>
<title>Trip Builder</title>
<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
</head>
<body>
	<h1>Trip Builder</h1>
	<hr>
	<h3>
		<a href="/browser">API browser and documentation</a>
	</h3>
	<br>
	<br>

	<h2>Tests</h2>
	Customer id
	<input type="text" id="customer_id" value="1">
	<a href="#"
		onclick="$('#customer_id').val(Math.round(Math.random()*100))">(randomize)</a>
	<br>
	<br>
	<a href="#" id="list_airports">List airports</a> | 
	<a href="#" id="list_trips">List trips</a> | 
	<a href="#" id="create_trip">Create new trip</a>
	<div id="ajax_result" style="margin-top: 10px; border: 1px solid green"></div>

	<script>
$('#list_airports').click(function(){
	$('#ajax_result').html("Loading...");
	$.getJSON("/airport", function(data){
		var buff="<table border='1'><tr><th>Code</th><th>Name</th><th>Country</th></tr>";
		for(var i=0; i<data.data.length; i++){
			var d = data.data[i];
 			buff+= "<tr><td>"+d.code+"</td><td>"+d.name+"</td><td>"+d.country+"</td></tr>";
		}
		$('#ajax_result').html(buff+"</table>");
	});
});

var FLIGHTS = [];
$('#list_trips').click(function(){
	$('#ajax_result').html("Loading...");
	$.getJSON("/trip?customer_id=" + $('#customer_id').val(), function(data){
		var buff="<table border='1'><tr><th>Trip ID</th><th>Customer</th><th>Flights</th></tr>";
		for(var i=0; i<data.data.length; i++){
			var d = data.data[i];
 			buff+= "<tr><td>"+d.id+"</td><td>"+d.customer_id+"</td><td>"
			buff+= "<table>";
			FLIGHTS = d.flights;
			for(var j=0; j<d.flights.length; j++){
				buff+= "<tr><td>"+d.flights[j].airport_origin+"</td>";
				buff+= "<td>"+d.flights[j].airport_destination+"</td>";
				buff+= "<td>"+d.flights[j].departure_date+"</td>";
				buff+= "<td><a href='#' onclick='removeFlight("+d.id+","+j+")'>remove flight</a></td>"
				buff+= "</tr>";
			}
			buff+= "</table>";
			buff+="<br><a href='#addfl"+d.id+"' onclick='$(\"#addfl"+d.id+"\").slideDown()'>Add Flight</a><br>";		
			buff+="<div id='addfl"+d.id+"' style='display: none'>";		
			buff+="Airport Origin: <input type='text' id='airport_origin"+d.id+"' placeholder='AAA'><br>";
			buff+="Airport Destination: <input type='text' id='airport_destination"+d.id+"' placeholder='BBB'><br>";
			buff+="Departure Date: <input type='text' id='departure_date"+d.id+"' placeholder='YYYY-MM-DD HH:Mi:SS'> (optional)<br>";
			buff+= "<input type='button' onclick='addFlight("+d.id+")' value='Add'>";
 			buff+= "</div>";
 			buff+= "</td></tr>";
		}
		buff+="</table>";
		$('#ajax_result').html(buff);
	});
});


function removeFlight(trip_id, index){
	$.ajax({
	    url: '/flight?trip_id='+trip_id
			+'&airport_origin='+FLIGHTS[index].airport_origin
	    	+'&airport_destination='+FLIGHTS[index].airport_destination
	   		+'&departure_date='+FLIGHTS[index].departure_date,
	    type: 'DELETE',
	    dataType: 'json',
	    success: function(result) {
	    	alert(result.data);
	    	$('#list_trips').click();
	    }
        ,error: function(result) {
            alert($.parseJSON(result.responseText).data);
        }
	});
}


function addFlight(trip_id, index){
	var airport_origin = $('#airport_origin'+trip_id).val();
	var airport_destination = $('#airport_destination'+trip_id).val();
	var departure_date = $('#departure_date'+trip_id).val();

	$.ajax({
	    url: '/flight?trip_id='+trip_id,
	    data: '&airport_origin='+airport_origin
		    +'&airport_destination='+airport_destination
	 	   +'&departure_date='+departure_date,
	    type: 'POST',
	    dataType: 'json',
	    success: function(result) {
	    	alert(result.data);
	    	$('#list_trips').click();
	    }
        ,error: function(result) {
            alert($.parseJSON(result.responseText).data);
        }
	});
}


$('#create_trip').click(function(){
	$('#ajax_result').html("Creating...");
	$.post("/trip", "customer_id=" + $('#customer_id').val(), function(result){
		alert(result.data);
		$('#list_trips').click();
	}, "json");
});


</script>

</body>
</html>