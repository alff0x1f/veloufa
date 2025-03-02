
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<TITLE>01</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link rel="alternate" type="application/rss+xml" title="GPSLib RSS Feed" href="http://www.gpslib.ru/tracks/rss/" />
<LINK rel="stylesheet" type="text/css" href="/styles.css">
<LINK rel="icon" href="/favicon.ico" type="image/x-icon">
<LINK rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</HEAD>
<body onload="load();" onunload="GUnload();" style="margin:0;padding:0;">

	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="http://i.gpslib.ru/google_maps_copyright.js"></script>

    <script type="text/javascript">
    //<![CDATA[
	var map=null;
	var copyrights={};

	var collection1 = new CopyrightCollection('Map data &copy;2011');
	collection1.addCopyright(new Copyright(
		1,	
		new google.maps.LatLngBounds(new google.maps.LatLng(-90, -179), new google.maps.LatLng(90, 180)),
		0,
		'OpenStreetMap.org')
	);

	copyrights['OSM'] = collection1;

	var osmMapType = new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			return "http://tile.openstreetmap.org/" +
			zoom + "/" + coord.x + "/" + coord.y + ".png";
		},
		tileSize: new google.maps.Size(256, 256),
		isPng: true,
		alt: "OpenStreetMap layer",
		name: "OpenStreetMap",
		maxZoom: 19
	});

	function initialize() {

		var latlng = new google.maps.LatLng(53.131363,50.077408);

		var myOptions = {
			zoom: 10,
						center: latlng,
			mapTypeControlOptions: {mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE]},
			mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		map = new google.maps.Map(document.getElementById("map"), myOptions);
		map.mapTypes.set('OSM',osmMapType);

		var myNewOptions = {
			zoom: 10,
			center: latlng,
			mapTypeId: 'OSM',

			mapTypeControlOptions: {mapTypeIds: ['OSM', google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE]}
			};
		map.setOptions(myNewOptions);

		copyrightNode = document.createElement('div');
		copyrightNode.id = 'copyright-control';
		copyrightNode.style.fontSize = '11px';
		copyrightNode.style.fontFamily = 'Arial, sans-serif';
		copyrightNode.style.margin = '0 2px 2px 0';
		copyrightNode.style.whiteSpace = 'nowrap';
		copyrightNode.index = 0;
		map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(copyrightNode);

		google.maps.event.addListener(map, 'idle', updateCopyrights);
		google.maps.event.addListener(map, 'maptypeid_changed', updateCopyrights);

		map.mapTypes.set('OSM',osmMapType);
		}

	function updateCopyrights() {
		var notice = '';
		var collection = copyrights[map.getMapTypeId()];
		var bounds = map.getBounds();
		var zoom = map.getZoom();
		if (collection && bounds && zoom) notice = collection.getCopyrightNotice(bounds, zoom);
		copyrightNode.innerHTML = notice;
  		}

    function GUnload() {
		}

    function load() {
		initialize();
		var georssLayer = new google.maps.KmlLayer('http://gpslib.ru/tracks/download/f_39476.kml?color=');
		georssLayer.setMap(map);
		}
    //]]>
    </script>

    <div id="map" style="width: 100%; height: 100%"></div>
</body>
</html>