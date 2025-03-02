<?php
/*
Plugin Name: J.B.Market Weather Widget
Description: J.B.Market Weather Widget for WordPress
Version: 2.0
Author: J.B.Market (support@jbmarket.net)
License: GPL2 or later
*/

/*  Copyright 2012  J.B.Market  (email : support@jbmarket.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class JBWeatherWidget extends WP_Widget {
   
    function JBWeatherWidget() {
        defined ("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
        
        $widget_options = array(
            "classname"   => "JBWeatherWidget",
            "description" => "J.B.Weather Widget 2.0 for WordPress"
        );
        
        parent::WP_Widget("jbweatherwidget", "J.B.Weather Widget", $widget_options);
        
        if (is_active_widget(false, false, $this->id_base)) {
            wp_enqueue_style("jbweather_style", plugins_url("jbweather/css/style.css", __FILE__));
            wp_enqueue_style("jbweather_ui_style", plugins_url("jbweather/css/blitzer/jquery-ui-1.8.23.custom.css", __FILE__));
            wp_enqueue_script("jbweather_scripts", plugins_url("jbweather/js/jbweather.js", __FILE__), array("jquery", "jquery-ui-autocomplete"));
        }
    }

    /* UI */
    function widget($args, $instance) {
        extract( $args, EXTR_SKIP );
        
        $title = $instance["title"] ? $instance["title"] : "";
        ?>
            <?php echo $before_widget; ?>
            <?php echo $before_title . $title . $after_title; ?>

            <?php $unique = $this->unique(); ?>

            <div class="<?php echo $unique; ?>">
                <?php echo $this->getBody(); ?>
            </div>

            <?php echo $this->initScript( $instance, $unique ); ?>

        <?php
    }
    
    /* Form (parameters) */
    function form($instance) {
        ?>

        <p>
            <label for="<?php echo $this->get_field_id("title"); ?>">
                Title:
                <input    id="<?php echo $this->get_field_id("title"); ?>"
                        name="<?php echo $this->get_field_name("title"); ?>"
                       value="<?php echo esc_attr($instance["title"]); ?>"
                       class="widefat"
                />
            </label>
        </p>
            
        <p>
            <label for="<?php echo $this->get_field_id("apiKey"); ?>">
                API Key:
                <input    id="<?php echo $this->get_field_id("apiKey"); ?>"
                        name="<?php echo $this->get_field_name("apiKey"); ?>"
                       value="<?php echo esc_attr($instance["apiKey"]); ?>"
                       class="widefat"
                />
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id("width"); ?>">
                Widget width (in pixels):
                <input    id="<?php echo $this->get_field_id("width"); ?>"
                        name="<?php echo $this->get_field_name("width"); ?>"
                       value="<?php echo esc_attr($instance["width"]); ?>" 
                       class="widefat"
                />
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id("basicColor"); ?>">
                Widget basic color:
                <input    id="<?php echo $this->get_field_id("basicColor"); ?>"
                        name="<?php echo $this->get_field_name("basicColor"); ?>"
                       value="<?php echo esc_attr($instance["basicColor"]); ?>" 
                       class="widefat"
                />
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id("location"); ?>">
                Location:
                <input    id="<?php echo $this->get_field_id("location"); ?>"
                        name="<?php echo $this->get_field_name("location"); ?>"
                       value="<?php echo esc_attr($instance["location"]); ?>" 
                       class="widefat"
                />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id("autoDetect"); ?>">
                Auto detect:
                <select id="<?php echo $this->get_field_id("autoDetect"); ?>" name="<?php echo $this->get_field_name("autoDetect"); ?>" class="widefat">
                    <option <?php echo $instance["autoDetect"] == "1" ? "selected" : ""; ?> value="1">Yes</option>
                    <option <?php echo $instance["autoDetect"] == "0" ? "selected" : ""; ?> value="0">No</option>
                </select>
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id("detectType"); ?>">
                Detect type:
                <select id="<?php echo $this->get_field_id("detectType"); ?>" name="<?php echo $this->get_field_name("detectType"); ?>" class="widefat">
                    <option <?php echo $instance["detectType"] == "1" ? "selected" : ""; ?> value="1">GeoIP</option>
                    <option <?php echo $instance["detectType"] == "0" ? "selected" : ""; ?> value="0">HostIP</option>
                </select>
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id("degreesUnits"); ?>">
                Degree Units:
                <select id="<?php echo $this->get_field_id("degreesUnits"); ?>" name="<?php echo $this->get_field_name("degreesUnits"); ?>" class="widefat">
                    <option <?php echo $instance["degreesUnits"] == "C" ? "selected" : ""; ?> value="C">Celsius</option>
                    <option <?php echo $instance["degreesUnits"] == "F" ? "selected" : ""; ?> value="F">Fahrenheit</option>
                </select>
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id("windUnits"); ?>">
                Wind Units:
                <select id="<?php echo $this->get_field_id("windUnits"); ?>" name="<?php echo $this->get_field_name("windUnits"); ?>" class="widefat">
                    <option <?php echo $instance["windUnits"] == "K" ? "selected" : ""; ?> value="K">Kilometers</option>
                    <option <?php echo $instance["windUnits"] == "M" ? "selected" : ""; ?> value="M">Miles</option>
                </select>
            </label>
        </p>
        
        
        <p>
            <label for="<?php echo $this->get_field_id("curl"); ?>">
                Use cURL:
                <select id="<?php echo $this->get_field_id("curl"); ?>" name="<?php echo $this->get_field_name("curl"); ?>" class="widefat">
                    <option <?php echo $instance["curl"] == "1" ? "selected" : ""; ?> value="1">Yes</option>
                    <option <?php echo $instance["curl"] == "0" ? "selected" : ""; ?> value="0">No</option>
                </select>
            </label>
        </p>
        
        <?php
    }
    
    private function getBody() {
        include(dirname(__FILE__) . DS . "jbweather" . DS . "view" . DS . "tmpl.php");
    }
    
    private function initScript($instance, $unique) {
        
        if ($instance['autoDetect'] == 1) :
            $instance["location"] = $this->detectLocation($instance["detectType"], $instance["location"]);
        endif;
        
        $instance['url'] = plugin_dir_url(__FILE__) . "jbweather";
        
        foreach ($instance as $opt => $value):
            $params[] = $opt . ':"' . $value . '"';
        endforeach;
        $params = implode(',', $params);

        echo "
            <script type='text/javascript'>
            
                jQuery(document).ready(function(){
                    var JBW = new JBWeather('".$unique."');
                    JBW.init({{$params}});
                });
            </script>
        ";
    }
    
    private function unique() {
        $valid_chars = "QWERTYUIOPASDFGHJKLZXCVBNM";
        $length = 5;
        $unique = "";
        $num_valid_chars = strlen($valid_chars);
        for ($i = 0; $i < $length; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick - 1];
            $unique .= $random_char;
        }
        return $unique;
    }
    
    private function detectLocation($type, $default) {
        if ($type == 0) {

            /* Autodetect using HOSTIP service */

            $xml = simplexml_load_file('http://api.hostip.info/?ip=' . $this->getUserIP());
            $country = $xml->xpath('//gml:featureMember//Hostip//countryName');
            $city = $xml->xpath('//gml:featureMember//Hostip//gml:name');

            if ($country[0] != '(Private Address)') :

                if ($city[0] == '(Unknown city)') :
                    return ucwords($country[0]);
                else:
                    return ucwords($city[0]) . ',' . ucwords($country[0]);
                endif;

            else :
                /* Unable to locate user location; return default */
                return $default; 
            endif;
        } else if ($type == 1) {

            /* Autodetect using geoip database */
            require_once dirname(__FILE__) . "/jbweather/geoip/geoipcity.inc";
            require_once dirname(__FILE__) . "/jbweather/geoip/geoipregionvars.php";

            $gi = geoip_open(dirname(__FILE__) . "/jbweather/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
            $record = geoip_record_by_addr($gi, $this->getUserIP());
            $country = $record->country_name;
            $city = $record->city;

            if ($country) :
                return ucwords($city) . ', ' . ucwords($country);
            else :
                /* Unable to locate user location; return default */
                return $default;
            endif;

            geoip_close($gi);
        } else {
            /* Wrong detection type; return default */
            return $default;
        }
        
    }
    
    private function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        } return $ip;
    }
}

/* Register Widget */
function JBWeatherWidget_init() {
    register_widget("JBWeatherWidget");
}
add_action("widgets_init", "JBWeatherWidget_init");