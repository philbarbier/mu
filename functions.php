<?
if (!$direct) die;

require_once('dbconfig.php');

function getLatLong($address) {
    if (strlen($address)==0||empty($address)||!isset($address)) return false;

    $baseurl = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

    $url = $baseurl . urlencode($address);
    $response = json_decode(file_get_contents($url));

    $latlong = array();

    $latlong['lat'] = $response->results[0]->geometry->location->lat;
    $latlong['lng'] = $response->results[0]->geometry->location->lng;

    return $latlong;

}

function sendCurlPost($url, $data) {
    if (!is_array($data) || $data=='') return false;

    $ch = curl_init();
    
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data); 

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;

}

function saveIncident($mashupid, $inc, $latlng) {
    //$sql = "INSERT INTO `incidents` (`epoch_timestamp`, `actual_timestamp`, `latitude`, `longitude`,`mashup_id`, `data`) VALUES ";
    //$sql .= "('" . date('U',strtotime($inc->dispatch_time)) . "','" . $inc->dispatch_time . "','" . $latlng['lat'] . "','" . $latlng['lng'] . "',".$mashupid.",'".json_encode($inc)."')";
    //$res = mysql_query($sql);

    // @TODO make these URLs nicer!
    $api_url = 'http://mu-api.seepies.net/index.php/set';
    $api_url = 'http://mu-api.philnic.lan/index.php/set'; // hitting itself, needs internal URL

    $fields = array('epoch_timestamp'=>date('U',strtotime($inc->dispatch_time)), 'actual_timestamp'=>$inc->dispatch_time, 'latitude'=>$latlng['lat'], 'longitude'=>$latlng['lng'], 'mashupid'=>$mashupid, 'data'=>json_encode($inc));

    return sendCurlPost($api_url, $fields);
}

function updateJob($url,$incidentCount, $pages, $perPage, $currentPage) {
    $url = 'http://mu-api.philnic.lan/index.php/updatejob';
    $data = array('url'=>$url, 'total'=>$incidentCount, 'pages'=>$pages, 'perpage'=>$perPage, 'currentPage'=>$currentPage);
    $response = sendCurlPost($url, $data);
    echo "\nUpdate response: " . $response;
}
