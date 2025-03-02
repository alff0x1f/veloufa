<?php
/*
Plugin Name: WP-FindMeSpot
Plugin URI: http://www.caliban.cc
Author: Caliban Designs Inc. 
Author URI: http://Caliban Designs Inc
Description: Plugin regularly requests XML info from FindMeSpot's subscriber public URL page, and saves the lat/long and date/time in the db. It then uses a shortcode to call the Google Maps API to generate a map of the route within given date parameters.
Version: 1.2.5
*/
define('DS', DIRECTORY_SEPARATOR);

class wp_findmespot_class {
	function wp_findmespot_class() {
		$this->plugin_name = plugin_basename(__FILE__);
		add_shortcode('findmespotmap', array(&$this,'add_google_map'));
		add_shortcode('kmlmap', array(&$this,'add_kmlgoogle_map'));
		add_action('admin_menu', array(&$this,'wpfindmespot_add_page'));
		add_action('admin_init', array(&$this,'wpfindmespot_init'));
		add_action('wp_head', array(&$this, 'wpfindmespot_checkupdate'));
		register_activation_hook($this->plugin_name, array(&$this,'wpfindmespot_install'));
	}
	// Init plugin options to white list our options
	function wpfindmespot_init(){
		register_setting( 'wpfindmespotsdmin_options', 'findmespot_options',array(&$this,'wpfindmespot_textoptions_validate'));
		register_setting( 'wpfindmespotsdmin_options', 'findmespot_lastupdate', array(&$this,'wpfindmespot_lastupdateoption_validate'));
	}
	//Validate input from the Admin Page.
	function wpfindmespot_textoptions_validate($input) {
		$options = get_option('findmespot_options');
		$options['googleapi'] = trim($input['googleapi']);
		//Only chars used in the Google API 
		if(!preg_match('/^ABQIAAAA[0-9a-zA-Z_\-]{78}$/i', $options['googleapi'])) {
			$options['googleapi'] = '';
		}
		$options['spotid'] = trim($input['spotid']);
		//Only chars used in the Spot ID 
		if(!preg_match('/^[a-zA-Z0-9]{24,64}$/i', $options['spotid'])) {
			$options['spotid'] = '';
		}
		$options['width'] = trim($input['width']);
		//Only integers.
		if(!preg_match('/^[0-9]{2,4}$/i', $options['width'])) {
			$options['width'] = '500';
		}
		else {
			if ((int)$options['width'] < 1 || (int)$options['width'] > 3000)
				$options['width'] = '500';
		}
		$options['height'] = trim($input['height']);
		//Only integers.
		if(!preg_match('/^[0-9]{2,4}$/i', $options['height'])) {
			$options['height'] = '500';
		}
		else {
			if ((int)$options['height'] < 1 || (int)$options['height'] > 3000)
				$options['height'] = '500';
		}
		$options['iconstyle'] = trim($input['iconstyle']);
		//Only text, 1-20 chars.
		if(!preg_match('/^[a-z]{1,20}$/', $options['iconstyle'])) {
			$options['iconstyle'] = 'default';
		}	
		return $options;
	}
	function wpfindmespot_lastupdateoption_validate($input) {
		$options = get_option('findmespot_lastupdate');
		$options['lastupdate'] = trim($input['lastupdate']);
		//Only date-time
		if(!preg_match('/^[0-9]{4}-(?:01|02|03|04|05|06|07|08|09|10|11|12)-[0123][0-9]T[012][0-9]:[012345][0-9]:[012345][0-9][\+\-][01][0-9]:[012345][0-9]$/i', $options['lastupdate'])) {
			$options['lastupdate'] = '';
		}
		if(false === strtotime($options['lastupdate'])) {
			$options['lastupdate'] = date(DATE_ATOM,strtotime('now'));
		}
		
		$options['interval'] = trim($input['interval']);
		//Only integers
		if(!preg_match('/^[0-9]{1,3}$/i', $options['interval'])) {
			$options['interval'] = '15';
		}
		
		return $options;
	}
	
	// Add menu page
	function wpfindmespot_add_page() {
		add_options_page('WP FindMeSpot', 'WP FindMeSpot', 'manage_options', 'wpfindmespot', array(&$this,'wpfindmespot_do_page'));
	}
			   
	function  wpfindmespot_do_page() {
	?>
		<div class="wrap">
		<h2>WP-FindMeSpot</h2>
		<p>This plugin relies on both a Google API Key and an ID from the findmespot.com service. You can get a Google API key easily enough, but you will need to have purchased and activated the Spot satellite messenger device, and you will also need to set up a public shared page on the findmespot.com site.</p>
			<form method="post" action="options.php">
				<?php settings_fields('wpfindmespotsdmin_options'); ?>
				<?php $options = get_option('findmespot_options'); ?>
				<?php $lastupdate = get_option('findmespot_lastupdate'); ?>            
				<table class="form-table">
				<tr valign="top">
				<th><strong>Google Maps API Key</strong><p>(Don't have a Google Maps API Key? Get one at <a href="http://code.google.com/apis/maps/signup.html">Google Maps API page</a></p></th>
				<td><input type="text" name="findmespot_options[googleapi]" style="width: 80%;" value="<?php echo $options['googleapi'] ?>" /></td></tr>
				<tr valign="top">            
				<th><strong>Spot ID</strong><p>You'll need a Spot ID. To find out what your Spot ID is, go to your FindMeSpot shared page. In the browser's address bar, you'll see something like:<br /> "http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0XapxKiqW4RCHYhVkaCBpaHT3cNMUcEef" Your Spot ID would be the random numbers and letters following the "glId" key. Don't include the "=" symbol.</p></th>
				<td><input type="text" name="findmespot_options[spotid]" style="width: 80%;" value="<?php echo $options['spotid'] ?>" /></td>
				</tr>
				<tr valign="top">
				<th><strong>Last Update:</strong></th>
				<td><input type="text" name="findmespot_lastupdate[lastupdate]" style="width: 80%;" value="<?php echo $lastupdate['lastupdate'] ?>" /></td>
				</tr>
      			<tr valign="top">
				<th><strong>Update Interval:</strong><p>This number determines the frequency of retrieving the location data from the Spot website. The Spot Messenger transmits the data every 10-12 minutes, so we suggest setting this number somewhere between 15 and 20. <br /> (Mininum: 1, Maximum: 999)</p></th>
				<td><input type="text" name="findmespot_lastupdate[interval]" style="width: 40px;" maxlength="3" value="<?php echo $lastupdate['interval'] ?>" /></td>
				</tr>
				<tr valign="top">
				<th><strong>Map Size:</strong><p>Minimum 1x1, Maximum 3000x3000. Otherwise, the value will default to 500.</p></th>
				<td>Width: <input type="text" name="findmespot_options[width]" style="width: 10%;" value="<?php echo $options['width'] ?>" />&nbsp;&nbsp;Height: <input type="text" name="findmespot_options[height]" style="width: 10%;" value="<?php echo $options['height'] ?>" /></td>
				</tr>
  				<tr valign="top">
				<th><strong>Weather Icon Style:</strong><p>This sets the weather icon style in the popups.<br />  (Version 3 map only)</p></th>
				<td><select name="findmespot_options[iconstyle]" style="width: 120px;">
                	<?php
					//loop through images directory looking for "icon-<stylename>.xml" files.
					$mydir = dirname(__FILE__) . "/images/"; 
					$d = dir($mydir); 
					$styles = array();
					//Lopp through and collect the style names.
					while($entry = $d->read()) { 
						if (preg_match('/icon\-(.*?)\.xml/i',$entry,$iconmatches)) {
							$iconname = $iconmatches[1];
							array_push($styles,$iconname);
						} 
					} 
					$d->close(); 
					//get the current iconstyle option, set to default if it doesn't exist
					$iconstyle = $options['iconstyle'];
					if (!isset($iconstyle)) {
						$iconstyle = 'default';			
					}
					//make option tags.
					foreach($styles as $stylename) {
						echo '<option value="' . $stylename . '"' . ($iconstyle == $stylename?' selected':'') . '>' . $stylename . '</option>' . "\n";
					}
					?>
                </select></td>
				</tr>
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
        <?php
	}

	function wpfindmespot_install() {
		global $wpdb;
		$table_name = $wpdb->prefix . "findmespot";
	
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$wpdb->show_errors(); 
			$wpdb->query( "CREATE TABLE " . $table_name . " (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  entry_date datetime NOT NULL,
			  lat varchar(20) NOT NULL,
			  lng varchar(20) NOT NULL,
			  entry_type varchar(20) NOT NULL,
			  esn_id varchar(20) NOT NULL,
			  UNIQUE KEY id (id),
			  KEY `esn_id` (`esn_id`),
			  KEY `entry_date` (`entry_date`)
			);");
	
			$initlat = "0";
			$initlong = "0";
			$initdate = "1971-01-01";
			$insert = "INSERT INTO " . $table_name .
				" (entry_date, lat, lng) " .
				"VALUES ('" . $wpdb->escape($initdate) . "','" . $wpdb->escape($initlat) . "','" . $wpdb->escape($initlong) . "')";
	
			$results = $wpdb->query( $insert );
		}
	}

	function wpfindmespot_checkupdate() {
		global $wpdb;
		$lu = get_option('findmespot_lastupdate');
		$lastupd = strtotime($lu['lastupdate']);
		$interval = $lu['interval'];
		if ($interval == null)
			$interval = '15';
		if (! is_numeric($interval))
			$interval = '15';
		$interval = '-' . $interval . ' minutes';
		if (false === $lastupd)
			$lastupd = strtotime('-1 day',strtotime('now'));
			//Don't keep hammering the FindMeSpot website. Use the interval setting.
			if ($lastupd <= strtotime($interval,strtotime(date(DATE_ATOM,strtotime('now'))))) {
			$new_date = array ('lastupdate' => date(DATE_ATOM,strtotime('now')));
			update_option('findmespot_lastupdate',$new_date);
			$sid = get_option('findmespot_options');
			$spotid = $sid['spotid'];
			//$domain = 'share.findmespot.com';
			$domain = 'https://api.findmespot.com';			
			$timeout = 30;
			//$path = '/messageService/guestlinkservlet?glId=' . $spotid . '&completeXml=true';
			$path = '/spot-main-web/consumer/rest-api/2.0/public/feed/' . $spotid . '/message';

			$xml = $this->loadXML2($domain, $path, $timeout); 
			if(false === $xml) {
				return;
			}
			if( !$xml->totalCount > 0 )
				return;
			$latest_entry = $wpdb->get_var($wpdb->prepare("SELECT MAX(entry_date) FROM " . $wpdb->prefix . "findmespot"));
			$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "findmespot"));
			if ($count > 1) {
				$wpdb->query("DELETE FROM " . $wpdb->prefix . "findmespot WHERE entry_date = '1971-01-01 00:00:00'");
			}
			foreach ($xml->message as $msg) {
				$esn = $msg->esn;
				$msgtype = $msg->messageType;
				$ts = $msg->timestamp;
				$lat = $msg->latitude;
				$long = $msg->longitude;
				$timestamp = date(DATE_ATOM,strtotime($ts));
				if (strtotime($timestamp) > strtotime($latest_entry)) {
					$wpdb->insert($wpdb->prefix . "findmespot", array('entry_date' => $timestamp,
																	  'lat' => $lat,
																	  'lng' => $long,
																	  'entry_type' => $msgtype,
																	  'esn_id' => $esn)
								  );
				}
			}
		}
	}
	
	function loadXML2($domain, $path, $timeout = 30) {
		$fp = fsockopen($domain, 80, $errno, $errstr, $timeout);
		if($fp) {
			// make request
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Host: $domain\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
		   
			// get response
			$resp = "";
			while (!feof($fp)) {
				$resp .= fgets($fp, 128);
			}
			fclose($fp);
			// check status is 200
			$status_regex = "/HTTP\/1\.\d\s(\d+)/";
			if(preg_match($status_regex, $resp, $matches) && $matches[1] == 200) {   
				// load xml as object
				$parts = explode("\r\n\r\n", $resp);   
				return simplexml_load_string($parts[1]);               
			}
		}
		return false;
	} 
	
	function add_google_map($attr, $content = null) {
		//IE does things a little different than other browsers. Detect the browser here.
		$compatible = true;
		include_once(dirname(__FILE__) . DS . "browser_detection.php");
		if ( ( browser_detection( 'browser_working' ) == 'ie' ) ) {
			$compatible = false;
		}
		extract( shortcode_atts( array(
		  /*
		  	v = version. This refers to the Google Maps API version, either 2 (default) or 3.
		  */
		  'v' => '2',
		  'start' => '',
		  'end' => '',
		  'id' => '',
		  /* map types for v3:
		  	h (HYBRID)  	This map type displays a transparent layer of major streets on satellite images.
			r (ROADMAP) (default)	This map type displays a normal street map.
			s (SATELLITE) 	This map type displays satellite images.
			t (TERRAIN)		This map type displays maps with physical features such as terrain and vegetation.
		  */
		  'type' => 'r',
		  'sv' => 'no',
		  'wx' => 'none',
		  'h' => 'default',
		  'w' => 'default',
		  ), $attr ) );
		$version = esc_attr($attr['v']);
		$start = esc_attr($attr['start']);
		$end = esc_attr($attr['end']);
		$id = esc_attr($attr['id']);
		$maptype = esc_attr($attr['type']);
		$weather = esc_attr($attr['wx']);
		//get our options from the options table
		$options = get_option('findmespot_options');
		$hpassed = esc_attr($attr['h']);
		$wpassed = esc_attr($attr['w']);
		if (!is_numeric($hpassed))
			$hpassed = 'default';
		if (!is_numeric($wpassed))
			$wpassed = 'default';
		if ($wpassed == 'default') {
			$width = $options['width'];
		}
		else {
			if ((int)$wpassed < 1 || (int)$wpassed > 3000)
				$wpassed = '500';
			$width = $wpassed;
		}
		if (! is_numeric($width)) 
			$width = '500';
		
		if ($hpassed == 'default') {
			$height = $options['height'];
		}
		else {
			if ((int)$hpassed < 1 || (int)$hpassed > 3000)
				$hpassed = '500';
			$height = $hpassed;
		}
		if (! is_numeric($height)) 
			$height = '500';
		//IE doesn't do iframe sizing the same way as other browsers. Add 10px to eliminate scrollbars
		if (!$compatible)
			$height = $height + 10;
		$icostyle = $options['iconstyle'];
		//either version 2 or 3, make 2 the default.
		if ($version != '2' && $version != '3')
			$version = '2';

		//make 'r' the default maptype
		$maptype = strtolower($maptype);
		if ($maptype != 'h' && $maptype != 'r' && $maptype != 's' && $maptype != 't')
			$maptype = 'r';
		//set the default streetview display
		$sv = strtolower($sv);
		if ($sv != 'yes')
			$sv = 'no';
		//set the default weather type to 'none'
		$wx = strtolower($wx);
		if ($wx != 'station' && $wx != 'personal')
			$wx = 'none';

		$txt = '<p><iframe class="map_iframe" style="overflow:none;" scroll="no" src="';
		if ($version == '2') {
			$txt .= plugins_url( '', __FILE__ ) . '/maps.php?s=' . urlencode($start) . '&e=' . urlencode($end) . '&id=' . urlencode($id) . '&wx=' . urlencode($wx) . '&h=' . $hpassed . '&w=' . $wpassed; 
		}
		else {
			$txt .= plugins_url( '', __FILE__ ) . '/maps3.php?s=' . urlencode($start) . '&e=' . urlencode($end) . '&id=' . urlencode($id) . '&t=' . urlencode($maptype) . '&wx=' . urlencode($wx) . '&sv=' . urlencode($sv) . '&h=' . $hpassed . '&w=' . $wpassed;
		}
		$txt .= '" width="' . $width . '" height="' . $height . '"></iframe></p>';
		return $txt;
	}

	function add_kmlgoogle_map($attr, $content = null) {
		//IE does things a little different than other browsers. Detect the browser here.
		$compatible = true;
		include_once(dirname(__FILE__) . DS . "browser_detection.php");
		if ( ( browser_detection( 'browser_working' ) == 'ie' ) ) {
			$compatible = false;
		}
		extract( shortcode_atts( array(
		  'w' => 'default',
		  'h' => 'default',
		  'k' => '',
		  ), $attr ) );
		$k = esc_attr($attr['kml']);
		$hpassed = esc_attr($attr['h']);
		$wpassed = esc_attr($attr['w']);
		$options = get_option('findmespot_options');
		if (!is_numeric($hpassed))
			$hpassed = 'default';
		if (!is_numeric($wpassed))
			$wpassed = 'default';
		if ($wpassed == 'default') {
			$w = $options['width'];
		}
		else {
			if ((int)$wpassed < 1 || (int)$wpassed > 3000)
				$wpassed = $options['width'];
			$w = $wpassed;
		}
		if (! is_numeric($w)) 
			$w = '500';
		
		if ($hpassed == 'default') {
			$h = $options['height'];
		}
		else {
			if ((int)$hpassed < 1 || (int)$hpassed > 3000)
				$hpassed = $options['height'];
			$h = $hpassed;
		}
		if (! is_numeric($h)) 
			$h = '500';

		//IE doesn't do iframe sizing the same way as other browsers. Add 10px to eliminate scrollbars
		if (!$compatible)
			$h = $h + 10;
		$txt = '<p><iframe class="map_iframe" src="';
		$txt .= plugins_url( 'kmlmap.php', __FILE__ ) . '?w=' . urlencode($wpassed) . '&h=' . urlencode($hpassed) . '&k=' . urlencode($k); 
		$txt .= '" width="' . $w . '" height="' . $h . '"></iframe></p>';
		return $txt;
	}
}
$wpfindmespot = new wp_findmespot_class;