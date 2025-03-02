<?php
define('DS', DIRECTORY_SEPARATOR); 
include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DS ."wp-config.php");
include_once ('phpxmllib.php');

global $wpdb;
$pagename = plugins_url( '', __FILE__ );
$style = $_GET['s'];
$style = urldecode($style);
if(!preg_match('/^[a-z]{5,12}$/i', $style)) {
	$style = 'default';
}
if($style == '') {
	die;
}
$icon = $_GET['i'];
$icon = urldecode($icon);
if(!preg_match('/^[a-z]{3,16}$/i', $icon)) {
	$icon = 'unknown';
}
if($icon == '') {
	die;
}
$pagename = $pagename . "/images/icon-" . $style . ".xml";
$xml = get_url_contents($pagename);
$data = XML_unserialize($xml);
header("Content-Type: image/gif");
echo base64_decode($data['icons'][$style][$icon]);

function get_url_contents($url){
        $crl = curl_init();
        $timeout = 5;
        curl_setopt ($crl, CURLOPT_URL,$url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $ret = curl_exec($crl);
        curl_close($crl);
        return $ret;
}
