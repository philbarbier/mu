<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsRvkq_6P9NcGuzUTBWNDnuRK3WirwuKM&sensor=false"></script>

<script type="text/javascript">
    var incidents = [];
    var marker = [];
    var infoWindow = [];
    $(document).ready(function() {
        console.log('loading');

        var mapOptions = {
            center: new google.maps.LatLng(43.42,-79.20),
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }; // default map options

        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

       
        // all markers initially have the same options set
        // retrieve all known incidents
        <?
            foreach($incidents as $kv) {
                 if (is_array($kv)) {
                    if ($kv['location_lat'] && $kv['location_long']) {
        ?>

        var ll = new google.maps.LatLng(<?=$kv['location_lat'] ?>, <?=$kv['location_long'] ?>);
        var mo = {clickable: 'true', draggable: 'false', map: 'map', position: ll};

        incidents[<?=$kv['id'] ?>] = <?=json_encode($kv) ?>;
        
        var content = '<?=$kv['title'] ?> - <?=$kv['incidentdate'] ?>';

        marker[<?=$kv['id'] ?>] = new google.maps.Marker({position: ll, map: map, title: '<?=$kv['title'] ?>'});
        infoWindow[<?=$kv['id'] ?>] = new google.maps.InfoWindow({content: content});
        google.maps.event.addListener(marker[<?=$kv['id'] ?>], 'click', function() {
            infoWindow[<?=$kv['id'] ?>].open(map, marker[<?=$kv['id'] ?>]); 
        });
        <?
                    }
                 }
            }
        ?>


        console.log('finished');
    });
</script>

<style type="text/css">
    #map_canvas {
        width:650px;
        height:400px;
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

</div>
