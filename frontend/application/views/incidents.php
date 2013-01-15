
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsRvkq_6P9NcGuzUTBWNDnuRK3WirwuKM&sensor=false"></script>

<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bootstrap-datepicker.js"></script>

<script type="text/javascript">
var incidentArray = [];
var marker = [];
var infoWindow = [];
var map;
var mid = 0;

$(document).ready(function() {
    var mapOptions = {
        center: new google.maps.LatLng(43.75,-79.40),
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }; // default map options

    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    setTimeout(function() {
        loadIncidents(true);
    },500);
       
    $('#fromdate').datepicker();
    $('#todate').datepicker();

    $('#btn_filter').click(function(event) {
        loadIncidents(false); 
    });

});

function loadIncidents(firstload) {
    // retrieve all known incidents
    if (firstload) {
        incidents = <?=json_encode($incidents) ?>;
    } else {
        clearMarkers();
        //ajax with filters
        $.ajax({ 
                url: "http://mu-api.philnic.lan/index.php/getinc?callback=?",
                crossDomain: true,
                dataType: 'jsonp',
                data: {muid: "<?=$this->input->get('muid') ?>", fromdate: $('#fromdate').val(), todate: $('#todate').val()}
            }).done(function(data, status) {
                    //var nd = $.parseJSON(response);
                    console.log('loaded?');
                    console.log(status);
                    console.log(data);//response);
                }
            //}
        );
        incidents = {};
    }
   
    for (inc in incidents) {
        incidentArray.push(incidents[inc]);
        var ll = new google.maps.LatLng(incidents[inc].latitude, incidents[inc].longitude);
        var mo = {clickable: 'true', draggable: 'false', map: 'map', position: ll};

        incdata = JSON.parse(incidents[inc].data);

        var content = "Dispatch call time: " + incdata.dispatch_time + ". ID: " + incdata.incident_id + ". Alarm: " +incdata.alarm_level + "\n<br />";
        content += "City: " + incdata.city + ". Call Street: " + incdata.prime_street + "\n<br />";
        content += "Cross street: " + incdata.cross_street + ". Alarm type: " + incdata.incident_type + ".\n<br />";
        content += "Area: " + incdata.area + ". Dispatched units: " + incdata.dispatched_units + ".\n<br />";

        var incid = incidents[inc].id;
        createMarker(ll, incdata, incidents[inc], content);
    } 
    console.log('incidents loaded:');
    console.log(incidentArray);
}

function createMarker(ll, incdata, inc, content) {
    var incid = inc.id;
    marker[incid] = new google.maps.Marker({position: ll, map: map, title: incdata.incident_id + ' - ' + inc.actual_timestamp});
    marker[incid].infowindow = new google.maps.InfoWindow({content: content});
    google.maps.event.addListener(marker[incid], 'click', function() {
        $.each(incidentArray, function(i,v) {  
            marker[v.id].infowindow.close(); 
        });
        marker[incid].infowindow.open(map, marker[incid]);
           
    }); 


}

function clearMarkers() {
    $.each(incidentArray, function(i,v) {
        if (marker[v.id]) {
            marker[v.id].setVisible(false);
            marker[v.id].setMap(null);
            marker[v.id] = [];
        }
    });
    marker = [];
    incidentArray = [];
}

</script>

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" href="/datepicker.css" />

<style type="text/css">
    #map_canvas {
        width:850px;
        height:400px;
    }
    .clear {
        clear: both;
    }
    .datepicker {
        z-index: 9999;
        top: 0;
        left: 0;
        padding: 4px;
        margin-top: 1px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
</style>

<div class="container" id="index_container">
    <h2>Recent GTA road incidents:</h2>

    <hr />
    <div id="map_container">

        <div id="map_canvas">

        </div>

    </div>
    <div id="incidents">
    <!--
            -->
    </div>

    <div class="clear"></div>
    <div id="filters">
        <label for="fromdate">
            From:
            <input type="text" class="span2" id="fromdate" />
        </label>
        <label for="todate">
            To:
            <input type="text" class="span2" id="todate" />
        </label>
        <br />
        <button id="btn_filter" class="btn large">Filter</button>
    </div>

</div>
