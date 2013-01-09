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
    // @TODO make these URLs nicer!
    $api_url = 'http://mu-api.seepies.net/index.php/set';
    $api_url = 'http://mu-api.philnic.lan/index.php/set'; // hitting itself, needs internal URL

    $fields = array('epoch_timestamp'=>date('U',strtotime($inc->dispatch_time)), 'actual_timestamp'=>$inc->dispatch_time, 'latitude'=>$latlng['lat'], 'longitude'=>$latlng['lng'], 'mashupid'=>$mashupid, 'data'=>json_encode($inc));

    return sendCurlPost($api_url, $fields);
}

function updateJob($mashupid, $url,$incidentCount, $pages, $perPage, $currentPage, $done, $lastid) {
    $apiurl = 'http://mu-api.philnic.lan/index.php/updatejob';
    $data = array('mashupid'=>$mashupid, 'url'=>$url, 'total'=>$incidentCount, 'done'=>$done, 'lastid'=>$lastid);
    echo "\nposting: ";
    print_r($data);
    $response = sendCurlPost($apiurl, $data);
}

function jobStatus($url,$data) {
    return file_get_contents('http://mu-api.philnic.lan/index.php/jobstatus?url='.urlencode($url).'&data='.urlencode($data));
}
