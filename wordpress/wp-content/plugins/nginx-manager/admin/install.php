<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * Add default options and capabilties 
 */

function nginxm_install () {
	
   	global $wp_roles, $nginxm;
   	
	// Check for capability
	if ( !current_user_can('activate_plugins') ) 
		return;
	
	// Set the capabilities for the administrator
	$role = get_role('administrator');
	// We need this role, no other chance
	if ( empty($role) ) {
		update_site_option( "nginxm_init_check", __('Sorry, Nginx Manager works only with a role called administrator',"nginxm") );
		return;
	}
   	
	$role->add_cap('Nginx Manager | Config');
	$role->add_cap('Nginx Manager | Purge cache');
	
	$nginxm_global_options = get_site_option('nginxm_global_options');
	
	// Set the GLOBAL default settings, if we didn't upgrade
	if ( empty( $nginxm_global_options ) ) {
		
		$nginxm_global_options = nginxm_get_default_global_options();
		
		update_site_option("nginxm_global_options", $nginxm_global_options);
		
	}
	
	// check if multisite
	if (is_multisite()) {
		
		// multisite installation
		
		// Set the default settings for each blog, if we didn't upgrade
		$blogs = get_blogs_of_user(true);
	
		foreach ($blogs as $b) {
			
			$nginxm_options = get_blog_option($b->userblog_id, 'nginxm_options');
			
			// Set the LOCAL default settings, if we didn't upgrade
			if ( empty( $nginxm_options ) ) {
				
				$nginxm_options = nginxm_get_default_local_options();
				
				update_blog_option($b->userblog_id, "nginxm_options", $nginxm_options);
				
			}
				
		}
	} else {
		
		// it's a single installation
		$nginxm_options = get_option('nginxm_options');
		
		// Set the LOCAL default settings, if we didn't upgrade
		if ( empty( $nginxm_options ) ) {
			
			$nginxm_options = nginxm_get_default_local_options();
			
			update_option("nginxm_options", $nginxm_options);
			
		}
		
	}
	
	// setup cron event
	wp_schedule_event(time(), 'daily', 'nm_check_log_file_size_daily');
	
}

/**
 * Remove all options and capabilities
 */

function nginxm_uninstall() {
	
	global $nginxm, $nginxmNginx;
	
	// unschedule events
	wp_clear_scheduled_hook('nm_check_log_file_size_daily');
	
	// then remove all options
	delete_site_option( 'nginxm_global_options' );
	
	// check if multisite
	if (is_multisite()) {
		
		// multisite installation
		$blogs = get_blogs_of_user(true);
		foreach ($blogs as $b) {
			delete_blog_option( $b->userblog_id, 'nginxm_options' );
		}
		
	} else {
		// single installation
		delete_option( 'nginxm_options' );
	}

	// now remove the capability
	nginxm_remove_capability('Nginx Manager | Config');
	nginxm_remove_capability('Nginx Manager | Purge cache');	
	
}

/**
 * Deregister a capability from all classic roles
 */

function nginxm_remove_capability($capability){
	// this function remove the $capability only from the classic roles
	$check_order = array("subscriber", "contributor", "author", "editor", "administrator");

	foreach ($check_order as $role) {
		$role = get_role($role);
		$role->remove_cap($capability) ;
	}

}

/**
 * Return an array with default global options
 * 
 * @return Array
 * @since Nginx Manager 1.2.1
 */
function nginxm_get_default_global_options() {
	
	global $nginxm;
	
	$nginxm_global_options = array();
	
	// Cookie and cache timelife
	$nginxm_global_options['cookie_regexp']         = '/wordpress_(?!test_cookie)|comment_author|wp-postpass/';
	$nginxm_global_options['cache_ttl']             = '600';
	
	// Log settings
	$nginxm_global_options['log_level']             = 'INFO';
	$nginxm_global_options['log_filesize']          = 5;
	
	// Set plugin version, @since Nginx Manager 1.2
	$nginxm_global_options['current_db_version']    = $nginxm->db_version;
	
	// Mobile options, @since Nginx Manager 1.3.4
	$nginxm_global_options['mobile_uncache']        = 1;
	$nginxm_global_options['mobile_regexp']         = '#2.0 MMP|240x320|400X240|AvantGo|BlackBerry|Blazer|Cellphone|Danger|DoCoMo|Elaine/3.0|EudoraWeb|Googlebot-Mobile|hiptop|IEMobile|KYOCERA/WX310K|LG/U990|MIDP-2.|MMEF20|MOT-V|NetFront|Newt|Nintendo Wii|Nitro|Nokia|Opera Mini|Palm|PlayStation Portable|portalmmm|Proxinet|ProxiNet|SHARP-TQ-GX10|SHG-i900|Small|SonyEricsson|Symbian OS|SymbianOS|TS21i-10|UP.Browser|UP.Link|webOS|Windows CE|WinWAP|YahooSeeker/M1A1-R2D2|NF-Browser|iPhone|iPod|Android|BlackBerry9530|G-TU915 Obigo|LGE VX|webOS|Nokia5800#';
	
	return $nginxm_global_options;
	
}

/**
 * Return an array with default local options
 * 
 * @return Array
 * @since Nginx Manager 1.2.1
 */
function nginxm_get_default_local_options() {
	
	$nginxm_options = array();
	
	// Options for homepage, personal URLs and an array for capture URL
	$nginxm_options['automatically_purge_homepage']                 = 1;
	$nginxm_options['automatically_purge_purls']                    = 1;
	$nginxm_options['purgeable_url']                                = array();
	
	// Options "When a post/page is modified"
	$nginxm_options['automatically_purge_page']                     = 1;
	$nginxm_options['automatically_purge_page_archive']             = 1;
	$nginxm_options['automatically_purge_page_custom_taxa']         = 1;
	
	// Options "When a new comment is published"
	$nginxm_options['automatically_purge_comment_page']             = 1;
	$nginxm_options['automatically_purge_comment_archive']          = 1;
	$nginxm_options['automatically_purge_comment_custom_taxa']      = 1;
	
	// Options "When a post is moved to the trash"
	$nginxm_options['automatically_purge_all_on_delete']            = 0;
	$nginxm_options['automatically_purge_archives_on_delete']       = 1;
	$nginxm_options['automatically_purge_customtaxa_on_delete']     = 1;
	
	return $nginxm_options;
	
}

?>