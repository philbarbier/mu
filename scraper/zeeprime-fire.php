<?

$baseurl = 'http://fire.zeepri.me/json'; // http://zeepri.me/fire/json';

$pagevar = '&p=';
$perpagevar = '&pp=';

$url = $baseurl.'?'.$pagevar.'1'.$perpagevar.'1';
$contents = file_get_contents($url);
if(!$contents) die;
$data = json_decode($contents); //, true);

$direct = true;
$pi = pathinfo($_SERVER['SCRIPT_FILENAME']);
$p = explode('/',$pi['dirname']);
$mydir = '';
for($i=0;$i<(count($p)-1);$i++) {
    $mydir .= $p[$i].'/';
}
require_once($mydir.'functions.php');
$jd = jobStatus($baseurl, $contents);
print_r($jd);
echo "\n";
$jobdata = json_decode($jd);
if ((@$jobdata->status=='error') || !$jobdata) die;

$page = $jobdata->page;
$perpage = $jobdata->perPage;

$mashupid = $jobdata->job->mashup_id;

$url = $baseurl.'?'.$pagevar.$page.$perpagevar.$perpage;
error_log('using URL: ' . $url);
$contents = file_get_contents($url);

if (!$contents) {
    error_log('Error with datasource ('.$url.'). Exiting.');
    die;
}

$data = json_decode($contents); //, true);

echo "<pre>";

//print_r($data);

echo "\nIncidents: " . $data->incidentCount . " -- Pages: " . $data->pages . " -- perPage: " . $data->perPage . " -- currentPage: " . $data->currentPage; 

// do we need to keep track of the total completed here?
$done = 0; //($jobdata->job->done > 0) ? $jobdata->job->done : 0;
$batchComplete = false;

$inccount = 0;
foreach($data->incidents as $inc) {
    $inccount++;
}

if ($inccount != $data->perPage) {
    error_log('ci: ' . $inccount . ' -- pp: ' . $data->perPage . ' -- exiting insuff');
    die;
}

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
        $lastid = $incident->incident_id;
        $totaldone = $jobdata->job->done + $done;
        updateJob($mashupid, $baseurl, $data->incidentCount, $data->pages, $data->perPage, $data->currentPage, $totaldone, $lastid); 
    }
    
}

if ($done == $perpage) {
    $batchComplete = true;
}

if ($batchComplete) {
    updateJob($mashupid, $baseurl, $data->incidentCount, $data->pages, $data->perPage, $data->currentPage, $totaldone, $lastid); 
}
