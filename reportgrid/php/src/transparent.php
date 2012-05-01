<?php
require_once("ReportGrid.php");

$token      = isset($_GET['tokenId']) ? urldecode($_GET['tokenId']) : null;
$path       = isset($_GET['path'])    ? urldecode($_GET['path'])    : null;;
$event      = isset($_GET['event'])   ? urldecode($_GET['event'])   : null;;

$service    = isset($_GET['service']) ? urldecode($_GET['service']) : null;;
$rollup     = isset($_GET['rollup'])  ? urldecode($_GET['rollup'])  : null;;


function error($message)
{
	header('HTTP/1.0 400 Bad Request', true, 400);
	header('X-Error: ' . $message);
}

if(!$token || !$path || !$event) 
{
	if(!$token)
		error("request is missing the mandatory tokenId");
	else if(!$path)
		error("request is missing the mandatory path");
	else
		error("request is missing the mandatory event");
} else {
	$options = array();
	if($rollup)
	    $options['rollup'] = $rollup;

	$events = json_decode($event, true);

	$api = $service ? new ReportGridAPI($token, $service) : new ReportGridAPI($token);
	if(!$api->track($path, $events, $options))
		error("unable to track data");
}

header("Content-type: image/gif");
header("Content-length: 43");

$fp=fopen("php://output","wb");
fwrite($fp,"GIF89a\x01\x00\x01\x00\x80\x00\x00\xFF\xFF\xFF\x00\x00\x00\x21\xF9\x04\x01\x00\x00\x00\x00\x2C\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x44\x01\x00\x3B",43);
fclose($fp);