<?
if (!$direct) die;

require_once('dbconfig.php');

function getLatLong($address) {
    if (strlen($address)==0||empty($address)||!isset($address)) return false;
    $baseurl = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';
    $url = $baseurl . urlencode($address);
    $result = file_get_contents($url);
    //error_log('geocoding: ' . $url);
    //error_log('result: ' . json_encode($result));
    $response = json_decode($result);
    if ($response->status=="OVER_QUERY_LIMIT") {
        // die so we don't update the job or save the incident, we'd want to retry this again
        error_log('Exceeded Google limit, exiting');
        die;
    }
    $latlong = array();
    if ($response->results[0]) {
        $latlong['lat'] = $response->results[0]->geometry->location->lat;
        $latlong['lng'] = $response->results[0]->geometry->location->lng;
    }
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
    $response = sendCurlPost($apiurl, $data);
}

function jobStatus($url,$data) {
    return file_get_contents('http://mu-api.philnic.lan/index.php/jobstatus?url='.urlencode($url).'&data='.urlencode($data));
}
