<?php
define('DS', DIRECTORY_SEPARATOR);
include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DS . "wp-config.php");

$options = get_option('findmespot_options');
$mapkey = $options['googleapi'];
$kml = $_GET['k'];
$kml = urldecode($kml);

$kml = filter_var($kml, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
if (strlen($kml) > 50) 
	$kml = '';
if(!preg_match('/\.km[lz]$/i', $kml))
	$kml = '';
if ($kml == '')
	die;
$kmlpage = site_url($kml);
/*
$height = $_GET['h'];
$height = filter_var((int)$height,FILTER_SANITIZE_NUMBER_INT);
if ($height > 3000 || $height < 1) 
	die;
$width = $_GET['w'];
$width = filter_var((int)$width,FILTER_SANITIZE_NUMBER_INT);
if ($width > 3000 || $width < 1) 
	die;
*/

//override default height?
$hpassed = 'default';
if (isset($_GET['h'])) {
	$hpassed = $_GET['h'];
	$hpassed = substr($hpassed,0,4);
	$hpassed = urldecode($hpassed);
	if ($hpassed == null || $hpassed == '')
		$hpassed = 'default';
	if (!is_numeric($hpassed)) {
		$hpassed = 'default';
	}
	else {
		if ((int)$hpassed < 1 || (int)$hpassed > 3000)
			$hpassed = 'default';
	}
}

//override default width?
$wpassed = 'default';
if (isset($_GET['w'])) {
	$wpassed = $_GET['w'];
	$wpassed = substr($wpassed,0,4);
	$wpassed = urldecode($wpassed);
	if ($wpassed == null || $wpassed == '')
		$wpassed = 'default';
	if (!is_numeric($wpassed)) {
		$wpassed = 'default';
	}
	else {
		if ((int)$wpassed < 1 || (int)$wpassed > 3000)
			$wpassed = 'default';
	}
}
if ($wpassed == 'default') {
	$width = $options['width'];
}
else {
	$width = $wpassed;
}
if (! is_numeric($width)) 
	$width = '500';
$width = $width - 20;
if ($hpassed == 'default') {
	$height = $options['height'];
}
else {
	$height = $hpassed;
}
if (! is_numeric($height)) 
	$height = '500';
$height = $height - 20;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" >
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <style type="text/css">v\:* {behavior:url(#default#VML);}</style>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $mapkey ?>&amp;sensor=false"
            type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[
	var map;
	
	function zoomToGeoXML(geoXml) {
		var center = geoXml.getDefaultCenter();
		var span = geoXml.getDefaultSpan();
		var sw = new GLatLng(center.lat() - span.lat() / 2,
						center.lng() - span.lng() / 2);
		var ne = new GLatLng(center.lat() + span.lat() / 2,
						center.lng() + span.lng() / 2);
		var bounds = new GLatLngBounds(sw, ne);
		map = new GMap2(document.getElementById("map_canvas"),{ size: new GSize(<?php echo $width . ',' . $height ?>) });
		map.setCenter(center);
		map.setUIToDefault();
		map.setMapType(G_HYBRID_MAP);
		map.enableScrollWheelZoom();
		map.enableContinuousZoom();
		map.setZoom(map.getBoundsZoomLevel(bounds));
		map.addOverlay(geoXml);
	}
	
	function initialize() {
		if (GBrowserIsCompatible()) {
			// create the map
			var kml = new GGeoXml("<?php echo $kmlpage; ?>",function(){ zoomToGeoXML(kml); });
		} else {
		  alert("Sorry, the Google Maps API is not compatible with this browser");
		}
	}
	// ]]>
	</script>
  </head>
  <body onload="initialize()" onunload="GUnload()">
      <noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b> 
      However, it seems JavaScript is either disabled or not supported by your browser. 
      To view Google Maps, enable JavaScript by changing your browser options, and then 
      try again.
    </noscript>
      <div id="map_wrap" style="width:<?php echo $width ?>px;height:<?php echo $height ?>px;">
        <div id="map_canvas" style="width:<?php echo $w ?>px;height:<?php echo $height ?>px;"></div>
      </div>
  </body>
</html>

