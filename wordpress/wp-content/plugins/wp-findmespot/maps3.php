<?php

include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR."wp-config.php");
define('DS', DIRECTORY_SEPARATOR);

$options = get_option('findmespot_options');
//If $debug is set to true, then the Google Map Infowindow popup will display errorcodes as part of the content.
$debug = false;

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
$maptype = $_GET['t'];
$maptype = urldecode($maptype);
if(!preg_match('/^[hrst]$/i', $maptype)) {
	$maptype = 'r';
}
$mtype = 'ROADMAP';
switch ($maptype) {
	case 's':
		$mtype = 'SATELLITE';
		break;
	case 'h':
		$mtype = 'HYBRID';
		break;
	case 't':
		$mtype = 'TERRAIN';
		break;
	default:
		$mtype = 'ROADMAP';
}
$weathertype = $_GET['wx'];
$weathertype = urldecode($weathertype);
if(!preg_match('/^(personal|station)$/i', $weathertype)) {
	$weathertype = false;
}
$svenable = $_GET['sv'];
$svenable = urldecode($svenable);
if ($svenable == 'yes') {
	$svenable = true;
}
else {
	$svenable = false;
}
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

$icostyle = $options['iconstyle'];
if (!isset($icostyle)) {
	$icostyle = 'default';
}
$xmlpage = $pagename . "/xml.php?s=" . urlencode($start) . "&e=" . urlencode($end) . "&id=" . urlencode($esn) ;
if (false !== $weathertype) {
	$xmlwxpage = $pagename . "/xmlwx.php?lat=xxx&lng=yyy&t=" . substr($weathertype,0,1);
}
else {
	$xmlwxpage = '';
}
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
<!DOCTYPE html >
<html>
  <head> 
  	<meta http-equiv="refresh" content="900" />
    
    <style type="text/css">v\:* {behavior:url(#default#VML);}</style>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="<?php echo $pagename; ?>/js/keydragzoom_packed2.js"></script>
 	<script type="text/javascript" src="<?php echo $pagename; ?>/js/utils.js"></script>
	<link rel="stylesheet" href="<?php echo plugins_url( '/css/map.css', __FILE__ ) ?>" type="text/css" media="screen" /> 
    <script type="text/javascript">
    //<![CDATA[
	var targetpoint;
	var gmarkers = [];
	var messg = '';
	var geocoder = '';
	<?php if ($svenable) { ?>
	var panorama;
	var sv = new google.maps.StreetViewService();
	var showsv = true;
	function myPano(latlng,panID) {
		var panopts = {
			enableCloseButton: true,
			pano: panID,
			position: new google.maps.LatLng(latlng)
		}
		panorama = new google.maps.StreetViewPanorama(document.getElementById("map_canvas"),panopts);
	  	panorama.setPov({
			heading: 270,
			pitch: 0,
			zoom: 1
	  	});
		panorama.setVisible(true);
	}
	
	<?php } else { ?>
	var showsv = false;
	<?php } ?>
	function myclick(i,img) {
		<?php if ($svenable) { ?>
		if (panorama)
			panorama.setVisible(false);
		<?php } ?>
		google.maps.event.trigger(gmarkers[i], "click");
	}
	function mymouseover(i,img) {
		//GEvent.trigger(gmarkers[i], "mouseover");
		gmarkers[i].setIcon(img);
	}
	function mymouseout(i,img) {
		//GEvent.trigger(gmarkers[i], "mouseout");
		gmarkers[i].setIcon(img);
	}
	<?php if ($compatible) {  ?>
	function importanceOrder (marker,b) {
		return parseInt(GOverlay.getZIndex(marker.getPoint().lat()) + marker.importance * 100000);
	}
	<?php } ?>

	function initialize() {
		  // this variable will collect the html which will eventually be placed in the side_bar
		var side_bar_html = "";
		var svLink = '';
		var mylocation = 'Location: Somewhere in the Wide World';
		var bounds = new google.maps.LatLngBounds();
		var infowindow = new google.maps.InfoWindow(
		  { 
			//size: new google.maps.Size(150,50)
		  });
		geocoder = new google.maps.Geocoder();

		// Create the marker and set up the marker.showMapBlowup() window
		function createMarker(map,latlng,name,html,imp,idx,img,labeltext) {
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
			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				icon: img,
				title: name,
				zIndex: Math.round(latlng.lat()*-100000*imp)<<5
			});
			labeltext = '<h4 class="infowindowtitle">' + labeltext + '</h4>';
			google.maps.event.addListener(marker, 'click', function() {
				//Get weather info for nearest location
				infowindow.setContent('<h4 class="infowindowtitle">' + labeltext + '</h4><p style="margin-left:auto;margin-right:auto;width:6em">Loading...</p><img style="display:block;margin-left:auto;margin-right:auto" src="images/ajax-loader.gif" />');
				var weather = '';
				<?php if (false !== $weathertype) { ?>
				wx = "<?php echo $xmlwxpage; ?>";
				var stationinfo = '';
				var stationlink = '';
				var stationdate = '';
				var cond = '';
				var iconname = '';
				var wuimg = '';
				var wuimgtitle = '';
				var wucred = '';	
				var wucredlink = '';
				str1 = wx.replace(/xxx/,latlng.lat());
				wx = str1.replace(/yyy/,latlng.lng()) + '&rx=' + Math.floor(Math.random()*1000+1).toString();
				downloadUrl(wx, function(wxdoc) {
					var xmlWxDoc = xmlParse(wxdoc);
					var procstatus = xmlWxDoc.getElementsByTagName("weather")[0].getAttribute("procstatus");
					var arridx = 0;
					if ( xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("conditions")[0].childNodes[1] ) {
						arridx = 1;
					}
					weather = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("conditions")[0].childNodes[arridx].nodeValue;
					stationinfo = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("stationinfo")[0].childNodes[arridx].nodeValue;
					stationlink = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("stationlink")[0].childNodes[arridx].nodeValue;
					stationdate = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("stationdate")[0].childNodes[arridx].nodeValue;
					cond = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("wx")[0].childNodes[arridx].nodeValue;
					iconname = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("iconname")[0].childNodes[arridx].nodeValue;
					wuimg = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("imgurl")[0].childNodes[arridx].nodeValue;
					wuimgtitle = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("imgtitle")[0].childNodes[arridx].nodeValue;
					wucred = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("credit")[0].childNodes[arridx].nodeValue;
					wucredlink = xmlWxDoc.getElementsByTagName("weather")[0].getElementsByTagName("creditlink")[0].childNodes[arridx].nodeValue;
					wucred = wucred.replace(/^\s+|\s+$/g,"");
					wucred = wucred.replace('Weather Underground','Weather Underground<br />');
					iconname = iconname.replace(/^\s+|\s+$/g,"");
					cond = cond.replace(/^\s+|\s+$/g,"");
					if (wucredlink.length > 1) {
					wucredlink = '<table class="wunderground"><tbody><tr><td class="wunderpic"><a target="_blank" href="' + wucredlink + '" title="' + wuimgtitle + '" alt="' + wuimgtitle + '"><img class="wuimg" src="' + wuimg + '" alt="' + wuimgtitle + '" /></a></td><td class="weathercred"><p><a target="_blank" href="' + wucredlink + '" title="' + wuimgtitle + '" alt="' + wuimgtitle + '">' + wucred + '</a></p></td></tr></tbody></table>';
					}
					else {
						wucredlink = '<div class="wunderground"><p>' + wucred + '</p></div>';
					}
					
					var weathericon = '<img src="<?php echo $pagename; ?>/weathericon.php?s=<?php echo $icostyle; ?>&i=' + iconname + '" alt="' + cond + '" title="' + cond + '"/>';
					
					if (procstatus == 'OK') {
						weather = '<span class="weathertitle">Current Conditions</span> (' + stationdate + ')<br /><div class="weatherdata"><div class="weathericon">' + weathericon + '</div><div class="weathercond">' + weather + '</div></div>';
						weather = '<span class="weatherstationinfo"><a href="' + stationlink + '" target="_blank">' + stationinfo + '</a></span><br />' + weather + wucredlink;
					}
					<?php if ($debug) { ?>
					weather = weather + "<br />(error code: " + procstatus + ")";
					<?php } ?>
					weather = '<div class="weatherinfo">' + weather + '</div>';
					<?php } ?>

					// getPanoramaByLocation will return the nearest pano when the
					// given radius is 50 meters or less.
					if (showsv) {
						sv.getPanoramaByLocation(latlng, 100, function(data,status) {						
							if (status == google.maps.StreetViewStatus.OK) {
								// Set the Pano to use the passed panoID
								var markerPanoID = data.location.pano;
								svLink = '<div class="streetviewlink"><p>(<a href="#" onclick="myPano(\'' + latlng + '\',\'' + markerPanoID + '\')">See Street View</a>)</p></div>';
							}
							else {
								svLink = '<div class="streetviewlink"><p>(no streetview available)</p></div>';
							}
							codeLatLng(latlng,infowindow,labeltext,svLink + weather,marker,map);
							//infowindow.setContent(labeltext + '<div class="mylocation">' + myloc + '</div>' + svLink + weather); 
						});
					}
					else {
						codeLatLng(latlng,infowindow,labeltext,weather,marker,map);
						//infowindow.setContent(labeltext + '<div class="mylocation">' + myloc + '</div>'); 	
					}
				<?php if (false !== $weathertype) { ?>
					});
				<?php } ?>
				infowindow.open(map,marker);
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
			
			function codeLatLng(ll,infwindow,pretext,posttext,marker,map) {
				var retstr = 'Location: Somewhere in this Wide World';
				if (geocoder) {
					geocoder.geocode({'latLng': ll}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							if (results[1]) {
								infwindow.close();
								infwindow.setContent(pretext + '<div class="mylocation"><p>Location: Near ' + results[1].formatted_address + '</p></div>' + posttext);
								infwindow.open(map,marker);
							}
						}
						else {
							infwindow.close();
							infwindow.setContent(pretext + '<div class="mylocation"><p>' + retstr + '</p></div>' + posttext);
							infwindow.open(map,marker);
						}
					});
				}
				else {
					infwindow.close();
					infwindow.setContent(pretext + '<div class="mylocation"><p>' + retstr + '</p></div>' + posttext);
					infwindow.open(map,marker);
				}
			}
		}

		// create the map
		var latlong = new google.maps.LatLng( 0,0 );
		var opts = {
			zoom: 1,
			center: latlong,
			scrollwheel: true,
			streetViewControl: true,
			mapTypeId: google.maps.MapTypeId.<?php echo $mtype ?>
		}
		var map = new google.maps.Map(document.getElementById("map_canvas"),opts);
		map.enableKeyDragZoom({
			visualEnabled: true,
			visualPosition: google.maps.ControlPosition.LOWER_RIGHT,
			visualPositionOffset: new google.maps.Size(35, 0),
			visualPositionIndex: null,
			visualSprite: "http://maps.gstatic.com/mapfiles/ftr/controls/dragzoom_btn.png",
			visualSize: new google.maps.Size(20, 20)
		});
		google.maps.event.addListener(map, 'click', function() {
			infowindow.close();
		});

		//php populates the name of the xml page, and the gdownloadurl collects the xml
		downloadUrl("<?php echo $xmlpage; ?>", function(doc) {
			var xmlDoc = xmlParse(doc);
			var markers = xmlDoc.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				// obtain the attribues of each marker
				var lat = parseFloat(markers[i].getAttribute("lat"));
				var lng = parseFloat(markers[i].getAttribute("lng"));
				var pnt = new google.maps.LatLng(lat,lng);
				var infwindow = markers[i].getElementsByTagName("infowindow");
				var html = infwindow[0].childNodes[0].nodeValue;
				var label = markers[i].getAttribute("label");
				var idx = markers[i].getAttribute("index");
				//add the index to the label.
				labeltext = '<span class="indexnum">' + idx + '.</span> ' + label;
				label = idx + '. ' + label;
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
				icoimage = "<?php echo $pagename; ?>" + icoimage + "&type=.png";
				// create the marker. point: lat-long, label: name for the sidebar, html: info box contents, options: array of options

				var img = new google.maps.MarkerImage(icoimage);
				var marker = createMarker(map,pnt,label,html,imp,idx,img,labeltext);
				bounds.extend(pnt);
				map.fitBounds(bounds);
			}
			messg += side_bar_html;
			document.getElementById("map_side_bar").innerHTML = messg + "</p>";

			var lines = xmlDoc.documentElement.getElementsByTagName("line");
			// read each line
			for (var a = 0; a < lines.length; a++) {
				// get any line attributes
				var colour = lines[a].getAttribute("color");
				var width  = parseFloat(lines[a].getAttribute("width"));
				var opacity = parseFloat(lines[a].getAttribute("opacity"));
				// read each point on that line
				var points = lines[a].getElementsByTagName("point");
				var pts = [];
				for (var i = 0; i < points.length; i++) {
				pts[i] = new google.maps.LatLng(parseFloat(points[i].getAttribute("lat")),
								   parseFloat(points[i].getAttribute("lng")));
			}
			var poly = new google.maps.Polyline( {
				path: pts,
				strokeColor: colour,
				strokeWeight: width,
				strokeOpacity: opacity
			});

			poly.setMap(map);
			}
		});
	}	
	// ]]>
	</script>
  </head>
  <body onLoad="initialize()">
      <div id="map_wrap" style="width:<?php echo $width ?>px;height:<?php echo $height ?>px;">
        <div id="map_canvas" style="width:<?php echo $width - 120 ?>px;height:<?php echo $height ?>px;"></div>
        <div id="map_side_bar" style="width:116px;height:<?php echo $height - 4 ?>px;border-width:2px;border-style:solid;overflow:auto;"></div>
      </div>
  </body>
</html>

