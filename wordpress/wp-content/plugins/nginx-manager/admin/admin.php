<?php
/**
 * nginxmAdminPanel - Admin Section for Nginx Manager
 * 
 * @package Nginx Manager
 * @author Simone Fumagalli
 * @copyright 2010
 */
class nginxmAdminPanel{
	
	// constructor
	function nginxmAdminPanel() {
		
		// Add the admin menu
		add_action( 'admin_menu', array (&$this, 'add_menu') );
		
		// Add the script and style files
		add_action('admin_print_scripts', array(&$this, 'load_scripts') );
		add_action('admin_print_styles', array(&$this, 'load_styles') );
		
	}
	
	// integrate the menu	
	function add_menu()  {
		
		add_menu_page( 'Nginx Manager', __( 'Nginx manager', 'nginxm' ), 'install_plugins', NGINXMFOLDER, array (&$this, 'show_menu'), plugins_url('nginx-manager/img/nginx-manager-logo.png') );
		add_submenu_page( NGINXMFOLDER, 'Overview', __('Overview', 'nginxm'), 'install_plugins', NGINXMFOLDER, array (&$this, 'show_menu'));
		add_submenu_page( NGINXMFOLDER, 'Setup', __('Setup', 'nginxm'), 'install_plugins', 'nginxm-manage', array (&$this, 'show_menu'));
		add_submenu_page( NGINXMFOLDER, 'Personal URLs', __('Personal URLs', 'nginxm'), 'install_plugins', 'personal-urls', array (&$this, 'show_menu'));
		add_submenu_page( NGINXMFOLDER, 'Purge log', __('Purge log', 'nginxm'), 'install_plugins', 'purge-log', array (&$this, 'show_menu'));
		
	}

	// load the script for the defined page and load only this code	
	function show_menu() {
		
		global $nginxm;
		
		// check for upgrade and show upgrade screen
		if ( version_compare( $nginxm->global_options["current_db_version"], NGINXMDBVERSION, '<>' ) ) {
			include_once ( dirname (__FILE__) . '/upgrade.php' );
			nginxm_upgrade_page();
			return;			
		}
		
		switch ($_GET['page']) {
			
			case "nginxm-manage" :
				
				$update 			= 0;
				$error_time 		= false;
				$error_log_filesize = false;
				
				if ( isset($_POST['is_submit']) && ($_POST['is_submit'] == 1) ) {
					
					if ( (!is_numeric($_POST['cache_ttl'])) || (empty($_POST['cache_ttl'])) ) {
						$error_time = "Cache lifetime must be a number";
					} else {
						$nginxm->global_options['cache_ttl']        = $_POST['cache_ttl'];
					}
					
					$nginxm->global_options['log_level']            = $_POST['log_level'];
					
					if ( (!is_numeric($_POST['log_filesize'])) || (empty($_POST['log_filesize'])) ) {
						$error_log_filesize = "Log file size must be a number";
					} else {
						$nginxm->global_options['log_filesize']     = $_POST['log_filesize'];
					}
					
					$nginxm->global_options['cookie_regexp']                        = $_POST['cookie_regexp'];
					
					/* Mobile options, @since Nginx Manager 1.3.4 */
					$nginxm->global_options['mobile_uncache']                       = (isset($_POST['mobile_uncache']) and ($_POST['mobile_uncache'] == 1) ) ? 1 : 0;
					$nginxm->global_options['mobile_regexp']                        = $_POST['mobile_regexp'];
					
					$nginxm->options['automatically_purge_homepage']                = (isset($_POST['automatically_purge_homepage']) and ($_POST['automatically_purge_homepage'] == 1) ) ? 1 : 0;
					
					$nginxm->options['automatically_purge_purls']                   = (isset($_POST['automatically_purge_purls']) and ($_POST['automatically_purge_purls'] == 1) ) ? 1 : 0;
					
					// When a post is published
					$nginxm->options['automatically_purge_page']                    = (isset($_POST['automatically_purge_page']) and ($_POST['automatically_purge_page'] == 1) ) ? 1 : 0;
					$nginxm->options['automatically_purge_page_archive']            = (isset($_POST['automatically_purge_page_archive']) and ($_POST['automatically_purge_page_archive'] == 1) ) ? 1 : 0;
					$nginxm->options['automatically_purge_page_custom_taxa']        = (isset($_POST['automatically_purge_page_custom_taxa']) and ($_POST['automatically_purge_page_custom_taxa'] == 1) ) ? 1 : 0;
					
					// When a comment is published
					$nginxm->options['automatically_purge_comment_page']            = (isset($_POST['automatically_purge_comment_page']) and ($_POST['automatically_purge_comment_page'] == 1) ) ? 1 : 0;
					$nginxm->options['automatically_purge_comment_archive']         = (isset($_POST['automatically_purge_comment_archive']) and ($_POST['automatically_purge_comment_archive'] == 1) ) ? 1 : 0;
					$nginxm->options['automatically_purge_comment_custom_taxa']     = (isset($_POST['automatically_purge_comment_custom_taxa']) and ($_POST['automatically_purge_comment_custom_taxa'] == 1) ) ? 1 : 0;
					
					// When a post is moved to the Trash
					$nginxm->options['automatically_purge_all_on_delete']           = (isset($_POST['automatically_purge_all_on_delete']) and ($_POST['automatically_purge_all_on_delete'] == 1) ) ? 1 : 0;
					$nginxm->options['automatically_purge_archives_on_delete']      = (isset($_POST['automatically_purge_archives_on_delete']) and ($_POST['automatically_purge_archives_on_delete'] == 1) ) ? 1 : 0;
					$nginxm->options['automatically_purge_customtaxa_on_delete']    = (isset($_POST['automatically_purge_customtaxa_on_delete']) and ($_POST['automatically_purge_customtaxa_on_delete'] == 1) ) ? 1 : 0;
					
					// Custom post types, @since Nginx Manager 1.3.4
					$nginxm->options['nginxm_custom_post_types']                    = str_replace(' ','',$_POST['nginxm_custom_post_types']);
					
					// Update site and blog options
					update_site_option( "nginxm_global_options", $nginxm->global_options );
					update_option( "nginxm_options", $nginxm->options );
					
					$update = 1;
					
				}
	    		
	    ?>
	    
		<div class="wrap">
			
			<div class="icon32" id="icon-options-general"><br /></div>
			<h2>Setup</h2>
	
	        <?php if ($update) { ?>
				<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e('Settings saved', 'nginxm'); ?></strong></p></div>
	        <?php } ?>
	
			<form id="post_form" method="post" action="#" name="smart_http_expire_form">
				
				<h3>Sitewide options</h3>
				
				<h4>Cache</h4>
			
				<input type="hidden" name="is_submit" value="1" />
	
				<table class="form-table">
	
					<tr valign="top">
						<th scope="row"><label for="expire_time_value">Regular expression for cookies:</label></th>
						<td>
							<input id="cookie_regexp" class="large-text" type="text" name="cookie_regexp" value="<?php echo $nginxm->global_options['cookie_regexp']?>" />
	                        <span class="description">Requests that match this regexp are not cached. The Regexp is not validated.</span>
						</td>
					</tr>
	
					<tr valign="top">
						<th scope="row"><label for="expire_time_value">NGINX cache lifetime:</label></th>
						<td>
							<input id="cache_ttl" class="small-text" type="text" name="cache_ttl" value="<?php echo $nginxm->global_options['cache_ttl']?>" />
	                        <?php if ($error_time) { ?>
								<span class="error fade" style="display : block"><p><strong><?php echo $error_time; ?></strong></p></span>
	                        <?php } ?>
	                         <span class="description">Cache lifetime in seconds. If set to 0 no page is cached.</span>
						</td>
					</tr>
				
				</table>
				
				<h4>Logging</h4>
				
				<?php if (!is_writable(NGINXM_ABSPATH .'log/current.log')) { ?>
					<span class="error fade" style="display : block"><p><?php printf (__("Can't write on log file.<br /><br />Check you have write permission on <strong>%s</strong>", "nginxm"), NGINXM_ABSPATH .'log/current.log'); ?></p></span>
				<?php } ?>	
				
				<table class="form-table">
					
					<tbody>
						<tr>
							<th><label for="nginxm_logs_path"><?php _e('Logs path', 'nginxm'); ?></label></th>
							<td><?php echo NGINXM_ABSPATH ?>log/current.log</td>
						</tr>
			
						<tr>
							<th><label for="nginxm_log_level"><?php _e('Log level', 'nginxm'); ?></label></th>
							<td>
								<select name="log_level">
									<option value="NONE"<?php selected( $nginxm->global_options['log_level'],'NONE' ); ?>><?php _e('None', 'nginxm'); ?></option>
									<option value="INFO"<?php selected( $nginxm->global_options['log_level'],'INFO' ); ?>><?php _e('Info', 'nginxm'); ?></option>
									<option value="WARNING"<?php selected( $nginxm->global_options['log_level'],'WARNING' ); ?>><?php _e('Warning', 'nginxm'); ?></option>
									<option value="ERROR"<?php selected( $nginxm->global_options['log_level'],'ERROR' ); ?>><?php _e('Error', 'nginxm'); ?></option>
								</select>
							</td>
						</tr>
						
						<tr>
							<th><label for="log_filesize"><?php _e('Max log file size', 'nginxm'); ?></label></th>
							<td>
								<input id="log_filesize" class="small-text" type="text" name="log_filesize" value="<?php echo $nginxm->global_options['log_filesize']?>" /> Mb
								<?php if ($error_log_filesize) { ?>
									<span class="error fade" style="display : block"><p><strong><?php echo $error_log_filesize; ?></strong></p></span>
								<?php } ?>
							</td>
						</tr>
						
					</tbody>
					
				</table>
				
				<h4>Mobile</h4>
				
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">Cache if mobile:</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text"><span>If the request is from a mobile device disable cache</span></legend>
									<label for="mobile_uncache"><input type="checkbox" value="1" id="mobile_uncache" name="mobile_uncache"<?php checked( $nginxm->global_options['mobile_uncache'], 1 ); ?>> If the request is from a mobile device disable cache</label><br />
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="mobile_regexp">Regular expression for mobile:</label></th>
							<td>
								<input id="mobile_regexp" class="large-text" type="text" name="mobile_regexp" value="<?php echo $nginxm->global_options['mobile_regexp']; ?>" />
								<span class="description">HTTP User Agents that match this regexp are not cached. The Regexp is not validated.</span>
							</td>
						</tr>
					</tbody>
				</table>
				
				<br />
				
				<h3>Blog options</h3>
				
				<h4>Purging</h4>
				
				<table class="form-table">
				
					<tr valign="top">
						<th scope="row">Homepage:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>When a post/page is modified</span></legend>
								<label for="automatically_purge_homepage"><input type="checkbox" value="1" id="automatically_purge_homepage" name="automatically_purge_homepage"<?php checked( $nginxm->options['automatically_purge_homepage'], 1 ); ?>>Always purge the homepage</label><br />
							</fieldset>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">Personal URLs:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>When a post/page is modified</span></legend>
								<label for="automatically_purge_purls"><input type="checkbox" value="1" id="automatically_purge_purls" name="automatically_purge_purls"<?php checked( $nginxm->options['automatically_purge_purls'], 1 ); ?>>Always purge the personal URLs</label><br />
							</fieldset>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">When a post/page is modified:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>When a post/page is modified</span></legend>
								<label for="automatically_purge_page"><input type="checkbox" value="1" id="automatically_purge_page" name="automatically_purge_page"<?php checked( $nginxm->options['automatically_purge_page'], 1 ); ?>>Purge cache for the page/post</label><br />
								<label for="automatically_purge_page_archive"><input type="checkbox" value="1" id="automatically_purge_page_archive" name="automatically_purge_page_archive"<?php checked( $nginxm->options['automatically_purge_page_archive'], 1 ); ?>>Purge cache for the archive (date, category, tag, author)</label><br />
								<label for="automatically_purge_page_custom_taxa"><input type="checkbox" value="1" id="automatically_purge_page_custom_taxa" name="automatically_purge_page_custom_taxa"<?php checked( $nginxm->options['automatically_purge_page_custom_taxa'], 1); ?>>Purge cache for the related custom taxonomies</label><br />
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">When a new comment is published:</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>When a new comment is published</span></legend>
								<label for="automatically_purge_comment_page"><input type="checkbox" value="1" id="automatically_purge_comment_page" name="automatically_purge_comment_page"<?php checked( $nginxm->options['automatically_purge_comment_page'], 1); ?>>Purge cache for the related page/post</label><br />
								<label for="automatically_purge_comment_archive"><input type="checkbox" value="1" id="automatically_purge_comment_archive" name="automatically_purge_comment_archive"<?php checked( $nginxm->options['automatically_purge_comment_archive'], 1 ); ?>>Purge cache for archive related to the page (date, category, tag, author)</label><br />
								<label for="automatically_purge_comment_custom_taxa"><input type="checkbox" value="1" id="automatically_purge_comment_custom_taxa" name="automatically_purge_comment_custom_taxa"<?php checked( $nginxm->options['automatically_purge_comment_custom_taxa'], 1 ); ?>>Purge cache for the related custom taxonomies</label><br />
							</fieldset>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( "When a post is moved to the Trash:", "nginxm" ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( "When a post is moved to the Trash:", "nginxm" ); ?></span></legend>
								<label for="automatically_purge_all_on_delete">
									<input onclick="if(jQuery('#automatically_purge_all_on_delete').is(':checked')){jQuery('#automatically_purge_archives_on_delete').attr('disabled','disabled');jQuery('#automatically_purge_customtaxa_on_delete').attr('disabled','disabled');}else{jQuery('#automatically_purge_archives_on_delete').removeAttr('disabled');jQuery('#automatically_purge_customtaxa_on_delete').removeAttr('disabled');}" type="checkbox" value="1" id="automatically_purge_all_on_delete" name="automatically_purge_all_on_delete"<?php checked( $nginxm->options['automatically_purge_all_on_delete'], 1 ); ?>><?php _e( "Purge all the blog", "nginxm" ); ?>
								</label><br />
								<label for="automatically_purge_archives_on_delete">
									<input type="checkbox" value="1" id="automatically_purge_archives_on_delete" name="automatically_purge_archives_on_delete"<?php checked( $nginxm->options['automatically_purge_archives_on_delete'], 1 ); ?><?php disabled( $nginxm->options['automatically_purge_all_on_delete'] ); ?>>Purge cache for the archive (date, category, tag, author)
								</label><br />
								<label for="automatically_purge_customtaxa_on_delete">
									<input type="checkbox" value="1" id="automatically_purge_customtaxa_on_delete" name="automatically_purge_customtaxa_on_delete"<?php checked( $nginxm->options['automatically_purge_customtaxa_on_delete'], 1); ?><?php disabled( $nginxm->options['automatically_purge_all_on_delete'] ); ?>>Purge cache for the related custom taxonomies
								</label><br />
							</fieldset>
						</td>
					</tr>
										
				</table>
				
				<h4>Custom post types</h4>
				
				<table class="form-table">
	
					<tr valign="top">
						<th scope="row"><label for="nginxm_custom_post_types">Purge CPT:</label></th>
						<td>
							<input id="nginxm_custom_post_types" class="large-text" type="text" name="nginxm_custom_post_types" value="<?php echo $nginxm->options['nginxm_custom_post_types']?>" />
							<span class="description">Insert comma separated Custom Post Types.</span>
						</td>
					</tr>
					
				</table>
				
				
				
				<p class="submit">
					<input type="submit" name="smart_http_expire_save" class="button-primary" value="Save" />
				</p>
	
			</form>
		</div>
		<?php 
			break;
			
			case "purge-log" :
				include_once ( dirname (__FILE__) . '/purge_log.php' );
				break;
				
			case "personal-urls" :
				include_once ( dirname (__FILE__) . '/personal_urls.php' );
				break;
						
			default :
				include_once ( dirname (__FILE__) . '/overview.php' );
				nginxm_admin_overview(); 	
				break;
				
		}
		
	}
	
	function load_scripts() {
		
		// no need to go on if it's not a plugin page
		if ( !isset($_GET['page']) )
			return;
		
		switch ($_GET['page']) {
			
			case NGINXMFOLDER :
				wp_enqueue_script( 'postbox' );
				wp_enqueue_script('meta_box_init', WP_PLUGIN_URL . '/nginx-manager/js/meta_box_init.js');
				
			case 'purge-log' :
				wp_enqueue_script('nginxm', WP_PLUGIN_URL . '/nginx-manager/js/view_log.js', array('jquery') );
				wp_enqueue_style('nginxm-view-log', WP_PLUGIN_URL . '/nginx-manager/css/view_log.css', array('jquery') );
				
			break;
			
		}
	}
	
	function load_styles() {
	}
	
}

?>