<?php
define('DS', DIRECTORY_SEPARATOR); 
include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DS ."wp-config.php");

global $wpdb;

//start datetime, end datetime, and device ID from URI query
$start = $_GET['s'];
$start = urldecode($start);
if(!preg_match('/^[0-9]{4}-(?:01|02|03|04|05|06|07|08|09|10|11|12)-[0123][0-9] [012][0-9]:[012345][0-9]:[012345][0-9]$/i', $start)) {
	$start = '';
}
if(false === strtotime($start)) {
	die;
}

$end = $_GET['e'];
$end = urldecode($end);
if(!preg_match('/^[0-9]{4}-(?:01|02|03|04|05|06|07|08|09|10|11|12)-[0123][0-9] [012][0-9]:[012345][0-9]:[012345][0-9]$/i', $end)) {
	$end = '';
}
if(false === strtotime($end)) {
	die;
}

$esn = $_GET['id'];
$esn = urldecode($esn);
if(!preg_match('/^[0-9\-]{5,24}$/i', $esn)) {
	$esn = '';
}
if ($esn == '') {
	die;
}

//date("M j, g:i a",rs->entry_date)
header("Content-type: text/xml");
$xml_output = "<?xml version=\"1.0\"?>\n";
$xml_output .= "<markers>\n";
$i = 0;
$polyline = "\t<line color=\"#FF9900\" width=\"4\">\n";
$query = "SELECT * FROM " . $wpdb->prefix . "findmespot WHERE entry_date >= '" . $start . "' AND entry_date <= '" . $end . "' AND esn_id = '" . $esn . "' ORDER BY entry_date";
$results = $wpdb->get_results($query);
foreach ($results as $rs){
	$polyline .= "\t\t<point lat=\"" . $rs->lat . "\" lng=\"" . $rs->lng . "\" />\n";
	$i++;
	$entrydate = str_replace('m','',date("M j, g:ia",strtotime($rs->entry_date)));
    $xml_output .= "\t<marker lat=\"" . $rs->lat . "\" lng=\"" . $rs->lng . "\" label=\"" . ($rs->entry_type == 'TEST' ? $entrydate . ' &quot;OK&quot;' : ($rs->entry_type == 'TRACK' ? $entrydate : ($rs->entry_type == 'CUSTOM' ? $entrydate . ' &quot;MSG&quot;' : $rs->entry_type))) . "\" index=\"" . $i . "\">\n";
	$xml_output .= "\t\t<infowindow>\n\t\t\t<![CDATA[";
	$xml_output .= "\t\t\t" . $entrydate . "\n";
	$xml_output .= "\t\t\t]]>\n\t\t</infowindow>\n";
    $xml_output .= "\t</marker>\n";
}
$polyline .= "\t</line>\n";
$xml_output .= $polyline . "</markers>";

echo $xml_output;

?> 