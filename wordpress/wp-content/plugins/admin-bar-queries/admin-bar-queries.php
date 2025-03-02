<?php 
/*
Plugin Name: Admin Bar Queries
Plugin URI: http://carmelosantana.com/
Description: MySQL queries, script timer and CPU load in your admin bar.
Version: 0.5.21
Author: Carmelo Santana
Author URI: http://carmelosantana.com
*/

// add to admin bar
add_action( 'wp_before_admin_bar_render', 'do_admin_bar_queries', 999 );

// admin bar queries
function do_admin_bar_queries(){
	if ( !is_super_admin() )
		return;

	global $wp_admin_bar;			

	// queries + timer
	$wp_admin_bar->add_menu( array(
		'id' => 'admin_bar_queries',
		'title' => get_num_queries().'q '.timer_stop(0, 2).'s',
		'href' => '#'
	));

	// if available, add load
	if ( function_exists('sys_getloadavg') ){
		$load = sys_getloadavg();

		if ( is_array($load) and !empty($load) ){
			foreach ( $load as $c => $l )
				$load[$c] = round($l, 2);

			$wp_admin_bar->add_menu( array(
				'parent' => 'admin_bar_queries',
				'id' => 'admin_bar_memory',
				'title' => 'Load: <strong>' . $load[0] . /*', ' . $load[1] . ', ' . $load[2] .*/ '</strong>',
				'href' => '#'
			));
		}
	}
}