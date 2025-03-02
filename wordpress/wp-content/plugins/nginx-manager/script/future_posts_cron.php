<?php

/**
 * Check and purge scheduled posts.
 * 
 * ATTENTION: you must setup $_SERVER['HTTP_HOST'] and define ABSPATH_SCRIPT with your data
 */


/* setup */
$_SERVER['HTTP_HOST'] = 'your.host.here';
define('ABSPATH_SCRIPT', '/your/absolute/path/here/');


/* script */
require_once(ABSPATH_SCRIPT.'wp-load.php');
require_once(ABSPATH_SCRIPT.'/wp-content/plugins/nginx-manager/nginx-manager.php');
require_once(ABSPATH_SCRIPT.'/wp-content/plugins/nginx-manager/nginxmNginx.php');

global $nginxm, $nginxmNginx;

if (($fps = $nginxm->global_options['future_posts']) && count($nginxm->global_options['future_posts'])) {
	
	foreach ($fps as $blog_id => $fp) {
		
		foreach ($fp as $post_id => $timestamp) {
			
			// check if post timestamp is gone
			if ($timestamp < time()) {
				
				$nginxmNginx->log( "### Purging future post (id $post_id, blog id $blog_id) from external script BEGIN ===" );
				
				// 1. Force homepage purge
				
				$nginxmNginx->log( "### External script: purge homepage" );
				if ( is_multisite() ) {
					$_blog_detail = get_blog_details( $blog_id, true );
					$homepage_url = $_blog_detail->siteurl;
				} else {
					$homepage_url = home_url();
				}
				$nginxmNginx->purgeURL($homepage_url);
				
				// 2. Retrieve homepage
				
				$nginxmNginx->log( "### External script: retrieve homepage" );
				wp_remote_get($homepage_url);
				
				// 3. Remove [blog_id][post_id] from future posts options
				
				$nginxmNginx->log( "### External script: unset future posts" );
				unset($nginxm->global_options['future_posts'][$blog_id][$post_id]);
				update_site_option("nginxm_global_options", $nginxm->global_options);
				
				if ( !count($nginxm->global_options['future_posts'][$blog_id]) ) {
					unset($nginxm->global_options['future_posts'][$blog_id]);
					update_site_option("nginxm_global_options", $nginxm->global_options);
				}
				
				$nginxmNginx->log( "### Purging future post (id $post_id, blog id $blog_id) from external script END ^^^" );
				
			}
		}
		
	}
	
}

?>