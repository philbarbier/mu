<?
if (!$direct) die;

$dbhost = "127.0.0.1";
$dbuser = "mashedup";
$dbpass = "turds";
$dbname = "mashedup";

$dbc = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$dbc||mysql_error()) die('DB Server: '.mysql_error());
mysql_select_db($dbname);
if (mysql_error()) die('DB ('.$dbhost.' -- '.$dbuser.' -- '.$dbname.'): '.mysql_error());

?>
