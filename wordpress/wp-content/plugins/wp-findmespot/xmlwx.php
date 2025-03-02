<?php
define('DS', DIRECTORY_SEPARATOR); 
include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DS ."wp-config.php");
include_once ('phpxmllib.php');

global $wpdb;

$geolookup = "http://api.wunderground.com/auto/wui/geo/GeoLookupXML/index.xml?query=";
$pwswx = "http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=";
$airportwx = "http://api.wunderground.com/auto/wui/geo/WXCurrentObXML/index.xml?query=";

$lat = $_GET['lat'];
$lat = urldecode($lat);
if(!preg_match('/^-?[0-9]{1,3}\.[0-9]{0,15}$/i', $lat)) {
	$lat = '';
}
if($lat == '') {
	echo xmlout('ERROR','Latitude syntax error','','','','','','','','','');
	die;
}

$long = $_GET['lng'];
$long = urldecode($long);
if(!preg_match('/^-?[0-9]{1,3}\.[0-9]{0,15}$/i', $long)) {
	$long = '';
}
if($long == '') {
	echo xmlout('ERROR','Longitude syntax error','','','','','','','','','');
	die;
}

$type = $_GET['t'];
$type = urldecode($type);
if(!preg_match('/^[ps]$/i', $type)) {
	$type = '';
}

if ($type == 'p') {  // personal weather station
	try {
		//Do the geolookup on the lat and long to get the nearest station and pws
		$locationdata = get_url_contents($geolookup . $lat . ',' . $long);
		if (false === $locationdata){
			echo xmlout('GEOLOOKUP','Weather unavailable','','','','','','','','','');
			die;
		}
		$data = XML_unserialize($locationdata);
		$xmlparsed = false;
		//collect the station ID from the unserialized XML
		//Is this a single station or an array of stations?
		//single
		if (array_key_exists('id',$data['location']['nearby_weather_stations']['pws']['station'])) {
			$xmlparsed = true;
			$stationid = $data['location']['nearby_weather_stations']['pws']['station']['id'];
		}
		//array
		elseif (array_key_exists('0',$data['location']['nearby_weather_stations']['pws']['station'])) {
			foreach ($data['location']['nearby_weather_stations']['pws']['station'] as $sta) {
				$stationid = $sta['id'];
				if (preg_match('/[0-9a-zA-Z]/i',$stationid)) {
					$xmlparsed = true;
					break;
				}	
			}
		}
		//Different XML format than expected
		if (!$xmlparsed) {
			echo xmlout('STATIONID','No Personal Weather Stations found','','','','','','','','','');
			die;	
		}
		//If the stationID is not as expected, then die
		if ($stationid == null) {
			$stationid = '';
		}
		if ($stationid == '') {
			echo xmlout('STATIONID','Weather unavailable','','','','','','','','','');
			die;
		}
		//Get the station weather data
		$stationxml = $pwswx . $stationid;
		$currentobs = get_url_contents($stationxml);
		
		$obsdata = XML_unserialize($currentobs);
		if (false === $obsdata){
			echo xmlout('STATIONXML','Weather unavailable','','','','','','','','','');
			die;
		}
		//per the usage agreement, we need to credit the Weather Underground for the info.
		$credit = $obsdata['current_observation']['credit'];
		$crediturl = $obsdata['current_observation']['credit_URL'];
		$imgurl = $obsdata['current_observation']['image']['url'];
		$imgtitle = $obsdata['current_observation']['image']['title'];
		
		//Now pull the current conditions
		$weather = $obsdata['current_observation']['weather'];
		$iconname = '';
		$currentcondstr = 'Temperature: ';
		$stationinfo = $obsdata['current_observation']['location']['full'];
		$stationlink = $obsdata['current_observation']['history_url'];
		$currentcond = $obsdata['current_observation']['temp_f'];
		if (is_numeric($currentcond))
			$currentcondstr .= $currentcond . '&#176;F / ';
		$currentcond = $obsdata['current_observation']['temp_c'];
		if (is_numeric($currentcond))
			$currentcondstr .= $currentcond . '&#176;C/';
		$currentcond = $obsdata['current_observation']['relative_humidity'];
		if (is_numeric($currentcond))
			$currentcondstr .= '<br />Humidity: ' . $currentcond . '%';
		$currentcond = $obsdata['current_observation']['pressure_string'];
		if (strpos($currentcond,'.') !== false)
			$currentcondstr .= '<br />Pressure: ' . $currentcond ;
		$currentcond = $obsdata['current_observation']['precip_today_string'];
		if (strpos($currentcond,'.') !== false)
			$currentcondstr .= '<br />Precip today: ' . $currentcond ;
		$currentcond = $obsdata['current_observation']['wind_dir'];
		$currentcondstr .= '<br />Wind: ';
		if (strlen($currentcond) > 0)
			$currentcondstr .= $currentcond . ',';
		$currentcond1 = $obsdata['current_observation']['wind_mph'];
		if (is_numeric($currentcond1))
			$currentcondstr .= ' ' . $currentcond1 . ' mph';
		$currentcond = $obsdata['current_observation']['wind_gust_mph'];
		if (is_numeric($currentcond) && $currentcond > $currentcond1)
			$currentcondstr .= ', ' . $currentcond . ' mph gusts';
		
		$currentdate = $obsdata['current_observation']['observation_time'];
	} 
	catch (Exception $e) {
		echo xmlout('CODEERROR','Weather unavailable','','','','','','','','','');
		die;
	}
}
else {  //standard weather station. We pull the data from the WU webpage for the nearest weather, since the WU webpage uses both personal weather stations as well as regular weather stations.
	try {
		$locationdata = get_url_contents($geolookup . $lat . ',' . $long);
		if (false === $locationdata){
			echo xmlout('GEOLOOKUP','Weather unavailable','','','','','','','','','');
			die;
		}
		$data = XML_unserialize($locationdata);
		$stationurl = $data['location']['wuiurl'];
		if (false === strpos($stationurl,'http://')){
			echo xmlout('STATIONURL','Weather unavailable','','','','','','','','','');
			die;
		}		
		
		$nearby = '';
		if (array_key_exists(0,$data['location']['nearby_weather_stations']['airport']['station'])) {
			foreach($data['location']['nearby_weather_stations']['airport']['station'] as $sta) {			
				$nearby = $sta['icao'];
				if (preg_match('/[0-9a-zA-Z]/i',$nearby)) {
					break;
				}
			}
		}
		elseif (array_key_exists('icao',$data['location']['nearby_weather_stations']['airport']['station'])) {
			$nearby = $data['location']['nearby_weather_stations']['airport']['station']['icao'];
		}
		if (!preg_match('/[0-9a-zA-Z]/i',$nearby)) {
			$airportwx = $pwswx;
			if (array_key_exists(0,$data['location']['nearby_weather_stations']['pws']['station'])) {
				foreach($data['location']['nearby_weather_stations']['pws']['station'] as $sta) {			
					$nearby = $sta['id'];
					if (preg_match('/[0-9a-zA-Z]/i',$nearby)) {
						break;
					}
				}
			}
			elseif (array_key_exists('id',$data['location']['nearby_weather_stations']['pws']['station'])) {
				$nearby = $data['location']['nearby_weather_stations']['pws']['station']['id'];
			}
		}
		
		$credit = '';
		$crediturl = '';
		$imgurl = '';
		$imgtitle = '';
		$iconname = '';
		if ($nearby != '' && strlen($nearby) > 1) {
			$locationdata = get_url_contents($airportwx . $nearby);
			$data = XML_unserialize($locationdata);	
			/*
			if ($data['current_observation']['station_id'] == null || $data['current_observation']['station_id'] == '') {
				echo xmlout('NEARESTSTSTATION','Weather unavailable-No Nearby Station','','','','','','','','','');
				die;
			}
			*/
			$credit = $data['current_observation']['credit'];
			$crediturl = $data['current_observation']['credit_URL'];
			$imgurl = $data['current_observation']['image']['url'];
			$imgtitle = $data['current_observation']['image']['title'];
			$weather = $data['current_observation']['weather'];
			if (array_key_exists('icons',$data['current_observation'])) {
				if (array_key_exists('icon_set',$data['current_observation']['icons'])) {
					if (array_key_exists('icon_url',$data['current_observation']['icons']['icon_set'][0])) {
						$iconname = $data['current_observation']['icons']['icon_set'][0]['icon_url'];
						$iconname = preg_replace('/^.*\/(.*)\..*$/','$1',$iconname);
					}
				}
			}
		}
		$wupage = get_url_contents($stationurl);
		if (false === strpos($wupage,'http://')){
			echo xmlout('WUPAGE','Weather unavailable','','','','','','','','','');
			die;
		}
		preg_match('/\<link.*?type\="application\/rss\+xml".*?(http\:\/\/.*?)"\s*\/>/i',$wupage,$rssmatches);
		
		$rssxml = '';
		$rssxml = $rssmatches[1];
		if (false === strpos($rssxml,'http://')){
			echo xmlout('RSSXML','Weather unavailable','','','','','','','','','');
			die;
		}
		if($iconname == '') {
			if(preg_match('/\/i\/c\/[a-z]\/(.*?).gif.*?alt=\"(.*?)\"/is',$wupage,$iconmatches)) {
				$iconname = $iconmatches[1];
				$weather = $iconmatches[2];
			}
			else {
				$iconname = 'unknown';
				$weather = '';
			}
		}
		$currentobs = get_url_contents($rssxml);
		$obsdata = XML_unserialize($currentobs);
		$stationinfo = $obsdata['rss']['channel']['title'];
		$stationinfo = str_replace('from Weather Underground','',$stationinfo);
		$stationlink = $obsdata['rss']['channel']['item']['0']['link'];
		$currentcond = $obsdata['rss']['channel']['item']['0']['description'];
		$currentdate = $obsdata['rss']['channel']['item']['0']['pubDate'];
		preg_match('/^(.*)\<img/i',$currentcond,$currentcondmatch);
		$currentcond = $currentcondmatch[1];
		$currentcondstr = str_replace('|','<br />',$currentcond);
		$iconname = str_replace('_','',$iconname);
		if (preg_match('/\s([0-9]?[0-9])\:/',$currentdate,$pmmatch)) {
			$ampm = $pmmatch[1];
		}
		else {
			$ampm = '12';
		}
		if (is_numeric($ampm)) {
			$ampm = (int)$ampm;
		}
		if (substr($iconname,0,2) != 'nt' && (strpos(strtolower(str_replace('.','',$currentdate)),'pm') !== false || ($ampm < 6 || $ampm > 18))) {
			$iconname = 'nt' . $iconname;
		}
	}
	catch (Exception $e) {
		echo xmlout('CODEERROR','Weather unavailable','','','','','','','','','');
		die;
	}
}

echo xmlout('OK',$currentcondstr,$stationinfo,$stationlink,$currentdate,$imgurl,$imgtitle,$credit,$crediturl,$weather,$iconname);

function xmlout($procstatus,$xmldata,$stationinfo='',$stationlink='',$stationdate='',$imageurl='',$imagetitle='',$creds='',$credlink='',$wx='',$iconame='') {
	header("Content-type: text/xml");
	$xml_output = "<?xml version=\"1.0\"?>\n";
	$xml_output .= "<weather procstatus=\"" . $procstatus . "\">\n\t<conditions>\n\t\t<![CDATA[\n";
	$xml_output .= $xmldata;
	$xml_output .= "\n\t\t]]>\n\t</conditions>\n";
	$xml_output .= "\t<stationinfo>\n\t\t<![CDATA[\n";
	$xml_output .= $stationinfo;
	$xml_output .= "\n\t\t]]>\n\t</stationinfo>\n";
	$xml_output .= "\t<stationlink>\n\t\t<![CDATA[\n";
	$xml_output .= $stationlink;
	$xml_output .= "\n\t\t]]>\n\t</stationlink>\n";
	$xml_output .= "\t<stationdate>\n\t\t<![CDATA[\n";
	$xml_output .= $stationdate;
	$xml_output .= "\n\t\t]]>\n\t</stationdate>\n";
	$xml_output .= "\t<imgurl>\n\t\t<![CDATA[\n";
	$xml_output .= $imageurl;
	$xml_output .= "\n\t\t]]>\n\t</imgurl>\n";
	$xml_output .= "\t<imgtitle>\n\t\t<![CDATA[\n";
	$xml_output .= $imagetitle;
	$xml_output .= "\n\t\t]]>\n\t</imgtitle>\n";
	$xml_output .= "\t<credit>\n\t\t<![CDATA[\n";
	$xml_output .= $creds;
	$xml_output .= "\n\t\t]]>\n\t</credit>\n";
	$xml_output .= "\t<creditlink>\n\t\t<![CDATA[\n";
	$xml_output .= $credlink;
	$xml_output .= "\n\t\t]]>\n\t</creditlink>\n";
	$xml_output .= "\t<wx>\n\t\t<![CDATA[\n";
	$xml_output .= $wx;
	$xml_output .= "\n\t\t]]>\n\t</wx>\n";
	$xml_output .= "\t<iconname>\n\t\t<![CDATA[\n";
	$xml_output .= str_replace('_','',$iconame);
	$xml_output .= "\n\t\t]]>\n\t</iconname>\n";
	$xml_output .= "</weather>\n";
	return $xml_output;
}

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
