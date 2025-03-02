<?php
/*
Plugin Name: NGINX Manager
Plugin URI: http://wordpress.org/extend/plugins/nginx-manager/
Description: Manage Nginx cache 
Author: Simone Fumagalli & Marco Zanzottera
Version: 1.3.4.4
Author URI: http://www.iliveinperego.com
Network: true
License URI: http://www.gnu.org/licenses/gpl.html
 
    Copyright 2010    Simone Fumagalli & Marco Zanzottera

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define('NGINXM_ABSPATH', str_replace("\\","/", WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/' ));

global $current_blog;

if ( is_admin() ) {
	require_once (dirname (__FILE__) . '/admin/admin.php');
	$nginxmAdminPanel = new nginxmAdminPanel();
}

require_once (dirname (__FILE__) . '/nginxmNginx.php');
$nginxmNginx = new nginxmNginx();

if (!class_exists('nginxmLoader')) {
	
	class nginxmLoader {
		
		var $version            = '1.3.4.4'; // Plugin version
		var $db_version         = '1.3.4'; // DB version, change it to show the upgrade page
		var $minium_WP          = '3.0';
		var $options            = null;
		var $global_options     = null;
		
		function nginxmLoader() {
			
			// Stop the plugin if we missed the requirements
			if ( !$this->required_version() )
				return;
			
			// Get some constants first
			$this->load_options();
			$this->define_constant();
//			$this->define_tables();
			
			$this->plugin_name = plugin_basename(__FILE__);
			
			// Init options & tables during activation & deregister init option
			register_activation_hook( $this->plugin_name, array(&$this, 'activate') );
			register_deactivation_hook( $this->plugin_name, array(&$this, 'deactivate') );
			
			// Start this plugin once all other plugins are fully loaded
			// !! Changed from 'plugins_loaded' to 'init' @since Nginx Manager 1.2 for custom post types
			add_action( 'init', array(&$this, 'start_plugin'), 15 );
			
		}
		
		function start_plugin() {
			
			global $nginxmNginx;
			
			// Load the language file
			// $this->load_textdomain();
			
			// Check for upgrade
			$this->check_for_upgrade();
			
			// Purge URL on post publish and on new comment
			add_action( 'publish_post', array(&$nginxmNginx, 'purgePost'), 200, 1);
			add_action( 'publish_page', array(&$nginxmNginx, 'purgePost'), 200, 1);
			add_action( 'comment_post', array(&$nginxmNginx, 'purgePostOnComment'), 200, 1);
			
			/* Purge URL when the status of a comment changes, @since Nginx Manager 1.2.2 */
			add_action( 'wp_set_comment_status', array(&$nginxmNginx, 'purgePostOnComment'), 200, 2);
			
			/* Purge custom post types, @since Nginx Manager 1.3.4 */
			if ( isset($this->options['nginxm_custom_post_types']) && $this->options['nginxm_custom_post_types'] != '' ) {
				
				$_nginxm_cpt = split(',', $this->options['nginxm_custom_post_types']);
				
				foreach ( $_nginxm_cpt as $ncpt ) {
					add_action( 'publish_'.trim($ncpt), array( &$nginxmNginx, 'purgePost' ), 200, 1 );
				}
				
			}
			
			// Insert/delete record for future posts
			add_action( 'transition_post_status', array(&$this, 'set_future_post_option_on_future_status'), 20, 3 );
			add_action( 'delete_post',            array(&$this, 'unset_future_post_option_on_delete'), 20, 1 );
			
			// Send correct headers
			add_action( 'wp_headers', array(&$nginxmNginx, 'correctExpires'), 100, 1 );
			
			// Check log file size
			add_action( 'nm_check_log_file_size_daily', array(&$nginxmNginx, 'checkAndTruncateLogFile'), 100, 1 );
			
			// Purge on edit attachments
			add_action( 'edit_attachment',      array(&$nginxmNginx, 'purgeImageOnEdit'), 100, 1 );
			
			/* Check new site added, @since Nginx Manager 1.2.1 */
			add_action( 'wpmu_new_blog', array(&$this, 'update_new_blog_options'), 10, 1 );
			
			/* Purge when a post is moved to the Trash, @since Nginx Manager 1.3 */
			add_action( 'transition_post_status', array(&$nginxmNginx, 'purge_on_post_moved_to_trash'), 20, 3 );
			
			/* Purge (homepage) when a term taxonomy is edited or deleted, @since Nginx Manager 1.3.1 */
			add_action( 'edit_term',   array(&$nginxmNginx, 'purge_on_term_taxonomy_edited'), 20, 3 );
			add_action( 'delete_term', array(&$nginxmNginx, 'purge_on_term_taxonomy_edited'), 20, 3 );
			
			/* Purge homepage when 'check_ajax_referer' action is triggered, @since Nginx Manager 1.3.4.3 */
			add_action( 'check_ajax_referer', array(&$nginxmNginx, 'purge_on_check_ajax_referer'), 20, 2 );
			
			/* Integrations with other plugins */
			
			// NextGEN Gallery
			require_once (dirname (__FILE__) . '/integration/class-nginxm-nextgengallery.php');
			$NginxmNextgengallery = new NginxmNextgengallery();
			add_action('ngg_ajax_image_save',   array(&$NginxmNextgengallery, 'ngg_purge_url_on_saving_images'), 100, 1 );
			add_action('ngg_update_gallery',    array(&$NginxmNextgengallery, 'ngg_purge_post_on_editing_galleries'), 100, 1 );
			add_action('ngg_gallery_sort',      array(&$NginxmNextgengallery, 'ngg_purge_post_on_editing_galleries'), 100, 1 );
			
		}
		
		// Call when plugin activation
		function activate() {
			
			include_once (dirname (__FILE__) . '/admin/install.php');
			nginxm_install();
			
		}
		
		// Called on plugin deactivation
		function deactivate() {
			
			include_once (dirname (__FILE__) . '/admin/install.php');
			nginxm_uninstall();
			
		}
		
		function define_constant() {
			
			// define versions
			define('NGINXMVERSION',   $this->version );
			define('NGINXMDBVERSION', $this->db_version );
			
			// define URL
			define('NGINXMFOLDER', plugin_basename( dirname(__FILE__)) );
			
		}
		
		function required_version() {
			
			global $wp_version;
			
			// Check for WP version installation
			$wp_ok = version_compare( $wp_version, $this->minium_WP, '>=' );
			
			if ( ($wp_ok == FALSE) ) {
				add_action(
					'admin_notices', 
					create_function(
						'', 
						'global $nginxm; printf (\'<div id="message" class="error"><p><strong>\' . __(\'Sorry, Nginx manager works only under WordPress %s or higher\', "nginxm" ) . \'</strong></p></div>\', $nginxm->minium_WP );'
					)
				);
				return false;
			}
			
			return true;
			
		}
		
		function load_options() {
			
			// Load the options
			$this->global_options   = get_site_option( 'nginxm_global_options' );
			$this->options          = get_option( 'nginxm_options' );
		}
		
		function load_textdomain() {
			load_plugin_textdomain('nginxm', false, dirname( plugin_basename(__FILE__) ) . '/lang');
		}
		
		function set_future_post_option_on_future_status($new_status, $old_status, $post) {
			
			global $blog_id, $nginxmNginx;
			
			/**
			 * Purge post on post transition status
			 * @since Nginx Manager 1.3.4.3
			 */
			if ( $old_status != $new_status 
				&& $old_status != 'inherit' 
				&& $new_status != 'inherit'
				&& $old_status != 'auto-draft' 
				&& $new_status != 'auto-draft'
				&& $new_status != 'publish'
				&& !wp_is_post_revision( $post->ID ) ) {
				
				$nginxmNginx->log( "Purge post on transition post STATUS from ".$old_status." to ".$new_status );
				
				$nginxmNginx->purgePost($post->ID);
				
			}
			
			if ($new_status == 'future') {
			    
			    $post_types = array('post', 'page');
			    
			    $nginxm_custom_post_types = ($this->options['nginxm_custom_post_types']) ? $this->options['nginxm_custom_post_types']: '';
			    
			    if ($nginxm_custom_post_types) {
			        $custom_post_types = explode(',', $nginxm_custom_post_types);
			        if (is_array($custom_post_types)) {
			            $post_types = array_merge($post_types, $custom_post_types);
			        }
			    }
				
				// check if param is a post with status 'future'
				if ( $post && $post->post_status == 'future' && in_array($post->post_type, $post_types) ) {
					
					$nginxmNginx->log( "Set/update future_posts option (post id = ".$post->ID." and blog id = ".$blog_id.")" );
					
					// update option
					$this->global_options['future_posts'][$blog_id][$post->ID] = strtotime($post->post_date_gmt)+60;
					update_site_option("nginxm_global_options", $this->global_options);
					
				}
			}
		}
		
		function unset_future_post_option_on_delete($post_id) {
			
			global $blog_id, $nginxmNginx;
			
			// if is a post/page/custom post...
			if ($post_id && !wp_is_post_revision($post_id)) {
				
				// ...and it's scheduled
				if ( isset($this->global_options['future_posts'][$blog_id][$post_id]) && count($this->global_options['future_posts'][$blog_id][$post_id]) ) {
					
					$nginxmNginx->log( "Unset future_posts option (post id = ".$post_id." and blog id = ".$blog_id.")" );
					
					unset($this->global_options['future_posts'][$blog_id][$post_id]);
					update_site_option("nginxm_global_options", $this->global_options);
					
					if ( !count($this->global_options['future_posts'][$blog_id]) ) {
						unset($this->global_options['future_posts'][$blog_id]);
						update_site_option("nginxm_global_options", $this->global_options);
					}
					
				}
				
			}
		}
		
		/**
		 * Check plugin upgrades
		 * 
		 * @since Nginx Manager 1.2
		 */
		function check_for_upgrade() {
			
			$_current_db_version = $this->global_options["current_db_version"];
			
			// Inform about a database upgrade
			if ( version_compare( $_current_db_version, NGINXMDBVERSION, '<>' ) ) {
				add_action(
					'admin_notices',
					create_function(
						'',
						'echo \'<div id="message" class="error"><p><strong>' . sprintf(__('Please update the database of Nginx Manager as soon as possible (%s -> %s).', 'nginxm'), ($_current_db_version) ? $_current_db_version : '???', NGINXMDBVERSION ) . ' <a href="admin.php?page=nginx-manager">' . __('Click here to proceed.', 'nginxm') . '</a>' . '</strong></p></div>\';'));
			}
        
			return;
		}
		
		/**
		 * Set default local options for the new blog
		 * 
		 * @param $blog_id
		 * @since Nginx Manager 1.2.1
		 */
		function update_new_blog_options( $blog_id ) {
			
			global $nginxmNginx;
			
			include_once (dirname (__FILE__) . '/admin/install.php');
			
			$nginxmNginx->log( "New site added (id $blog_id)" );
			
			$nginxm_options = nginxm_get_default_local_options();
			
			update_blog_option( $blog_id, "nginxm_options", $nginxm_options, true );
			$nginxmNginx->log( "Default local options updated for the new blog (id $blog_id)" );
			
		}
		
	}
	
	// Let's start the holy plugin
	global $nginxm;
	$nginxm = new nginxmLoader();
	
}

?>