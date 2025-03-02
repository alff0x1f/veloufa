<?php

include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR."wp-config.php");
define('DS', DIRECTORY_SEPARATOR);

$options = get_option('findmespot_options');
$mapkey = $options['googleapi'];

$pagename = plugins_url( '', __FILE__ );
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

$xmlpage = $pagename . "/xml.php?s=" . urlencode($start) . "&e=" . urlencode($end) . "&id=" . urlencode($esn) ;

$options = get_option('findmespot_options');
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
  	<meta http-equiv="refresh" content="900" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <style type="text/css">v\:* {behavior:url(#default#VML);}</style>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $mapkey ?>&amp;sensor=false"
            type="text/javascript"></script>
    <script src="<?php echo $pagename; ?>/js/dragzoom.js" type="text/javascript"></script>
 	<link rel="stylesheet" href="<?php echo plugins_url( '/css/map.css', __FILE__ ) ?>" type="text/css" media="screen" /> 
    <script type="text/javascript">
    //<![CDATA[
	var targetpoint;
	var gmarkers = [];
	function iconImage(iconUrl) {
		if (iconUrl.indexOf('dot') > -1 ) {
			var icon = new GIcon();
			icon.image = '<?php echo $pagename; ?>' + iconUrl;
			icon.iconSize = new GSize(6,6);
			icon.iconAnchor = new GPoint(3, 3);
			icon.infoWindowAnchor = new GPoint(1, 5);
			icon.shadowSize = new GSize(0, 0);
		} else {
			var icon = new GIcon(G_DEFAULT_ICON);
			icon.image = '<?php echo $pagename; ?>' + iconUrl;
		}
		return icon;
	}	
	function myclick(i,img) {
		GEvent.trigger(gmarkers[i], "click");
	}
	function mymouseover(i,img) {
		//GEvent.trigger(gmarkers[i], "mouseover");
		gmarkers[i].setImage(img);
	}
	function mymouseout(i,img) {
		//GEvent.trigger(gmarkers[i], "mouseout");
		gmarkers[i].setImage(img);
	}
	
	<?php if ($compatible) {  ?>
	function importanceOrder (marker,b) {
		return parseInt(GOverlay.getZIndex(marker.getPoint().lat()) + marker.importance * 100000);
	}
	<?php } ?>

	function initialize() {
		if (GBrowserIsCompatible()) {
		  // this variable will collect the html which will eventually be placed in the side_bar
		  var side_bar_html = "";
		  // Create the marker and set up the marker.showMapBlowup() window
			function createMarker(point,name,html,imp,idx,opts) {
				var impclass = ' class="';
				var impclassfiller = '';
				var iconimg = '';
				if (imp == 2) {
					iconimg = "<?php echo $pagename; ?>\/images\/markers\/numbered_marker.php?image=pushpins\/webhues\/120.png&text="+idx;
					impclassfiller = 'ok_msg';
				} else if (imp == 0) {
					iconimg = "<?php echo $pagename; ?>\/images\/markers\/numbered_marker.php?image=pushpins\/webhues\/094.png&text="+idx;
					impclassfiller = 'track_start';
					if (name.indexOf('OK') > -1) {
						impclassfiller = 'ok_msg';
					} else if (name.indexOf('MSG') > -1) {
						impclassfiller = 'custom_msg';
					}
				} else if (imp == 3) {					
			        iconimg = "<?php echo $pagename; ?>\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/110.png&text="+idx;
					impclassfiller = 'custom_msg';
				} else if (imp == 4) {					
			        iconimg = "<?php echo $pagename; ?>\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/012.png&text="+idx;
					impclassfiller = 'track_end';
					if (name.indexOf('OK') > -1) {
						impclassfiller = 'ok_msg';
					} else if (name.indexOf('MSG') > -1) {
						impclassfiller = 'custom_msg';
					}
				} else {
					iconimg = "<?php echo $pagename; ?>\/images\/markers\/numbered_marker.php?image=pushpins\/webhues\/reddot.png";
					impclassfiller = 'track_msg';
				}
				impclass = impclass + impclassfiller + '" ';
				iconimg = iconimg + "&type=.png";
				var marker = new GMarker(point,opts);
				GEvent.addListener(marker, "click", function() {
				  marker.openInfoWindowHtml('<h4 class="infowindowtitle">We are here</h4>' + html);
				});
				if (imp != 1) {
					iconhighlight = "<?php echo $pagename; ?>\/images\/markers\/numbered_marker.php?image=pushpins\/webhues\/023.png&text="+idx;
				} else {
					iconhighlight = "<?php echo $pagename; ?>\/images\/markers\/numbered_marker.php?image=pushpins\/webhues\/yellowdot.png";
				}
				iconhighlight = iconhighlight + "&type=.png";

				// save the info we need to use later for the side_bar
				gmarkers.push(marker);

				side_bar_html += '<p class="mapspacer"><a href="#" ' + impclass + 'onclick="myclick(' + (gmarkers.length-1) + ',\'' + iconimg + '\')"  onmouseover="mymouseover(' + (gmarkers.length-1) + ',\''+iconhighlight+'\')" onmouseout="mymouseout(' + (gmarkers.length-1) + ',\''+iconimg+'\')">' + name + '<\/a></p>';
				return marker;
			}

			function setupDragZoom() {
				/* first set of options is for the visual overlay.*/
				var boxStyleOpts = {
				  opacity: .2,
				  border: "2px solid red"
				};
				
				/* second set of options is for everything else */
				var otherOpts = {
					buttonStartingStyle: {background: '#FFF', paddingTop: '4px', paddingLeft: '4px', border:'1px solid black'},
					buttonHTML: '<img title="Drag Zoom In" src="<?php echo $pagename ?>/images/zoomin.gif">',
					buttonStyle: {width:'25px', height:'23px'},
					buttonZoomingHTML: '<p style="font-size:.6em">Drag a region on the map (click here to reset)</p>',
					buttonZoomingStyle: {background:'yellow',width:'65px', height:'100%'},
					backButtonHTML: '<img title="Zoom Back Out" src="<?php echo $pagename ?>/images/zoomout.gif">',  
					backButtonStyle: {display:'none',marginTop:'5px',width:'25px', height:'23px'},
					backButtonEnabled: true, 
					overlayRemoveTime: 1500
				};
				
				map.addControl(new DragZoomControl(boxStyleOpts, otherOpts, {}), 
												 new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(13, 15)));
			}

			// create the map
			var map = new GMap2(document.getElementById("map_canvas"),{ size: new GSize(<?php echo $width - 120 . ',' . $height ?>) });
			map.setCenter(new GLatLng( 0,0 ), 1);
			map.setUIToDefault();
			map.enableScrollWheelZoom();
			map.enableContinuousZoom();
			setupDragZoom();
			map.streetViewControl = true;
			var messg = '';
			var bounds = new GLatLngBounds();
			//php populates the name of the xml page, and the gdownloadurl collects the xml
			GDownloadUrl("<?php echo $xmlpage; ?>", function(doc) {
				var xmlDoc = GXml.parse(doc);
				var markers = xmlDoc.documentElement.getElementsByTagName("marker");
				for (var i = 0; i < markers.length; i++) {
					// obtain the attribues of each marker
					var lat = parseFloat(markers[i].getAttribute("lat"));
					var lng = parseFloat(markers[i].getAttribute("lng"));
					var point = new GLatLng(lat,lng);
					var html = GXml.value(markers[i].getElementsByTagName("infowindow")[0]);
					var label = markers[i].getAttribute("label");
					var idx = markers[i].getAttribute("index");
					//add the index to the label.
					label = '<span class="indexnum">' + idx + '.</span> ' + label;
					var imp;

					if (label.indexOf('OK') > -1) {
						imp = 2;
				        icoimage = "\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/120.png&text="+idx;					
					} else if (label.indexOf('MSG') > -1) {
						imp = 3;
				        icoimage = "\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/110.png&text="+idx;										
					} else {
						imp = 1;
				        icoimage = "\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/reddot.png";										
					}
					if (idx == 1) { //start of route
						imp = 0;
				        icoimage = "\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/094.png&text="+idx;					
					}
					if (i == markers.length - 1) {  //end of route
						imp = 4;
				        icoimage = "\/images/markers\/numbered_marker.php?image=pushpins\/webhues\/012.png&text="+idx;				
					}
					icoimage = icoimage + "&type=.png";
					var newIcon = iconImage(icoimage);
					// create the marker. point: lat-long, label: name for the sidebar, html: info box contents, options: array of options
					<?php if ($compatible) { ?>
					var marker = createMarker(point,label,html,imp,idx,{icon: newIcon,zIndexProcess:importanceOrder});
					marker.importance = 1;
					<?php } else { ?>
						var marker = createMarker(point,label,html,imp,idx,{icon: newIcon});
					<?php } ?>
					//put the marker on the map
					map.addOverlay(marker);
					//for setting the extents of the map
					bounds.extend(point);
					//put the assembled side_bar_html contents into the side_bar div
				}
				messg += side_bar_html;
				document.getElementById("map_side_bar").innerHTML = messg + "</p>";
				map.setZoom(map.getBoundsZoomLevel(bounds));
				map.setCenter(bounds.getCenter());
				
				var lines = xmlDoc.documentElement.getElementsByTagName("line");
				// read each line
				for (var a = 0; a < lines.length; a++) {
					// get any line attributes
					var colour = lines[a].getAttribute("color");
					var width  = parseFloat(lines[a].getAttribute("width"));
					// read each point on that line
					var points = lines[a].getElementsByTagName("point");
					var pts = [];
					for (var i = 0; i < points.length; i++) {
					pts[i] = new GLatLng(parseFloat(points[i].getAttribute("lat")),
									   parseFloat(points[i].getAttribute("lng")));
				}
				map.addOverlay(new GPolyline(pts,colour,width));
				}		
			});
		}	
		else {
		  alert("Sorry, the Google Maps API is not compatible with this browser");
		}
	}
	// ]]>
	</script>
  </head>
  <body onload="initialize()" onunload="GUnload()">
      <div id="map_wrap" style="width:<?php echo $width ?>px;height:<?php echo $height ?>px;">
        <div id="map_canvas" style="width:<?php echo $width - 120 ?>px;height:<?php echo $height ?>px;"></div>
        <div id="map_side_bar" style="width:116px;height:<?php echo $height - 4 ?>px;border-width:2px;border-style:solid;overflow:auto;"></div>
      </div>
  </body>
</html>

