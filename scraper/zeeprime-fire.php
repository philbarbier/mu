<?

$baseurl = 'http://zeepri.me/fire/json?';

$pagevar = '&p=';
$perpagevar = '&pp=';

$direct = true;
$pi = pathinfo($_SERVER['SCRIPT_FILENAME']);
$p = explode('/',$pi['dirname']);
$mydir = '';
for($i=0;$i<(count($p)-1);$i++) {
    $mydir .= $p[$i].'/';
}
require_once($mydir.'functions.php');
$mashupid = 1;

/** 

ask API what it should get for this ID (base off URL supplied)

*/

//getJob();

$page = 1;
$perpage = 10;

$url = $baseurl.$pagevar.$page.$perpagevar.$perpage;

$contents = file_get_contents($url);

$data = json_decode($contents); //, true);

echo "<pre>";

//print_r($data);

echo "\nIncidents: " . $data->incidentCount . " -- Pages: " . $data->pages . " -- perPage: " . $data->perPage . " -- currentPage: " . $data->currentPage; 

$done = 0;
$batchComplete = false;

foreach($data->incidents as $incident) {

    echo "\nIncident ID: " . $incident->incident_id;

    $searchaddress = '';
    $address = explode(',',$incident->prime_street);
    if ($address[0]==''||empty($address[0])) {
        $searchaddress = $incident->cross_street;
    } else {
        $searchaddress = $address[0];
    }
    if ($address[1]!=''||!empty($address[1])) {
        switch($address[1]) {
            case "NY":  $city = "North York";   break;
            case "SC":  $city = "Scarborough";  break;
            case "TT":  $city = "Toronto";      break;
            case "YK":  $city = "Toronto";      break;
            case "MK":  $city = "Markham";      break;
            case "ET":  $city = "Etobicoke";    break;
            case "EY":  $city = "East York";    break;

            default:    $city = 'Toronto';
        }
    }
    $searchaddress .= ','.$city;
    if (!isset($incident->city)) $incident->city = $city;
    if (saveIncident($mashupid, $incident, getLatLong($searchaddress))) {
        $done++;   
    }
    
}


if ($done == $perpage) {
    $batchComplete = true;
}

if ($batchComplete) {
    updateJob($baseurl, $data->incidentCount, $data->pages, $data->perPage, $data->currentPage); 
}
