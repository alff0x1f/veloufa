<?php
/*
Plugin Name: 	Advanced Widget Pack
Plugin URI: 	http://codecanyon.net/item/advanced-widget-pack
Description: 	Adds an set of advanced widgets to your WordPress site.
Version: 		1.4
Author: 		WPInsite
Author URI: 	http://www.wpinsite.com
License: 		Sold exclusively on CodeCanyon
*/

/**
 * Set up the autoloader.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/lib/'));
spl_autoload_extensions('.class.php');
if(!function_exists('buffered_autoloader')){
	function buffered_autoloader($c){
		try {
			spl_autoload($c);
		} catch (Exception $e) {
			$message = $e->getMessage();
			return $message;
		}
	}
}
spl_autoload_register('buffered_autoloader');

/**
 * Get the plugin object. All the bookkeeping and other setup stuff happens here.
 */
$advanced_widget_pack = Advanced_Widget_Pack::get_instance();
register_deactivation_hook(__FILE__, array(&$advanced_widget_pack, 'remove_options'));
?>