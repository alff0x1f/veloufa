<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * Upgrade operations
 * 
 * @return Boolean
 * @since Nginx Manager 1.2
 */
function nginxm_upgrade() {
	
	global $nginxm, $nginxmNginx;
	
	$current_db_version = $nginxm->global_options["current_db_version"];
	
	if ( version_compare( $current_db_version, NGINXMDBVERSION, '<' ) ) {
		
		// Upgrade
		
		if ( is_null($current_db_version) ) {
			
			echo "<h4>Updating up to 0.1</h4>";
			
			// Since v1.2 there was no any versioning system :-(
			// So, let's do all the upgrades
			$current_db_version = "0.1";
			$nginxmNginx->log("UPGRADE :: No current version found, upgrading from version < 1.2");
			echo "<p><i>No current version found, let's do all the upgrades</i></p>";
			
		}
		
		/* 
		 * v0.1 -> v1.2
		 * 1) Set options for custom taxonomies
		 * 2) Unset option['purgeable_url][category]
		 * 3) Unset option['purgeable_url][author]
		 * 4) Unset option['purgeable_url][date]
		 * */
		if (version_compare($current_db_version, '1.2', '<')) {
			
			echo "<h4>Updating up to 1.2</h4>";
			
			echo "<p><i>Setting local option for custom taxa and unsetting local options for category, date, author archives</i></p>";
			if (is_multisite()) {
				
				$blogs = get_blogs_of_user(true);
				
				foreach ($blogs as $b) {
					
					$nginxm_blog_options = get_blog_option($b->userblog_id, 'nginxm_options');
					
					// Set 1)
					$nginxm_blog_options['automatically_purge_page_custom_taxa']         = 1;
					$nginxm_blog_options['automatically_purge_comment_custom_taxa']      = 1;
					
					// Unset 2)
					unset($nginxm_blog_options['purgeable_url']['category']);
					// Unset 3)
					unset($nginxm_blog_options['purgeable_url']['author']);
					// Unset 4)
					unset($nginxm_blog_options['purgeable_url']['date']);
					
					update_blog_option($b->userblog_id, "nginxm_options", $nginxm_blog_options);
					
					$nginxmNginx->log("UPGRADE :: Update up to version 1.2, blog (id ".$b->userblog_id.") ... DONE");
					echo "<p>UPGRADE :: Update up to version 1.2, blog (id ".$b->userblog_id.") ... DONE</p>";
				}
				
			} else {
				
				$nginxm_options = get_option('nginxm_options');
				
				// Set 1)
				$nginxm_options['automatically_purge_page_custom_taxa']         = 1;
				$nginxm_options['automatically_purge_comment_custom_taxa']      = 1;
				
				// Unset 2)
				unset($nginxm_options['purgeable_url']['category']);
				// Unset 3)
				unset($nginxm_options['purgeable_url']['author']);
				// Unset 4)
				unset($nginxm_options['purgeable_url']['date']);
				
				update_option('nginxm_options', $nginxm_options);
				
				$nginxmNginx->log("UPGRADE :: Update up to version 1.2 ... DONE");
				echo "<p>UPGRADE :: Update up to version 1.2 ... DONE</p>";
				
			}
			
			$nginxmNginx->log("UPGRADE :: Update to version 1.2 ... DONE");
			echo "<p><i>Update to version 1.2 ... DONE</i></p>";
			
		}
		
		/* 
		 * v1.2 -> v1.3
		 * 1) Set options for post moved to the trash
		 * */
		if (version_compare($current_db_version, '1.3', '<')) {
			
			echo "<h4>Updating up to 1.3</h4>";
		
			echo "<p><i>Setting local option for purge when a post is moved to the trash</i></p>";
			if (is_multisite()) {
				
				$blogs = get_blogs_of_user(true);
				
				foreach ($blogs as $b) {
					
					$nginxm_blog_options = get_blog_option($b->userblog_id, 'nginxm_options');
					
					// Set
					$nginxm_blog_options['automatically_purge_all_on_delete']        = 0;
					$nginxm_blog_options['automatically_purge_archives_on_delete']   = 1;
					$nginxm_blog_options['automatically_purge_customtaxa_on_delete'] = 1;
					
					update_blog_option($b->userblog_id, "nginxm_options", $nginxm_blog_options);
					
					$nginxmNginx->log("UPGRADE :: Update up to version 1.3, blog (id ".$b->userblog_id.") ... DONE");
					echo "<p>UPGRADE :: Update up to version 1.3, blog (id ".$b->userblog_id.") ... DONE</p>";
				}
				
			} else {
				
				$nginxm_options = get_option('nginxm_options');
				
				// Set
				$nginxm_options['automatically_purge_all_on_delete']        = 0;
				$nginxm_options['automatically_purge_archives_on_delete']   = 1;
				$nginxm_options['automatically_purge_customtaxa_on_delete'] = 1;
				
				update_option('nginxm_options', $nginxm_options);
				
				$nginxmNginx->log("UPGRADE :: Update up to version 1.3 ... DONE");
				echo "<p>UPGRADE :: Update up to version 1.3 ... DONE</p>";
				
			}
			
			$nginxmNginx->log("UPGRADE :: Update to version 1.3 ... DONE");
			echo "<p><i>Update to version 1.3 ... DONE</i></p>";
			
		}
		
		/* 
		 * v1.3 -> v1.3.4
		 * 1) Set options for mobile
		 * */
		if (version_compare($current_db_version, '1.3.4', '<')) {
			
			echo "<h4>Updating up to 1.3.4</h4>";
			
			echo "<p><i>Setting global options for mobile</i></p>";
			
			$nginxm_global_options = get_site_option('nginxm_global_options');
			
			// Set
			$nginxm_global_options['mobile_uncache']        = 1;
			$nginxm_global_options['mobile_regexp']         = '#2.0 MMP|240x320|400X240|AvantGo|BlackBerry|Blazer|Cellphone|Danger|DoCoMo|Elaine/3.0|EudoraWeb|Googlebot-Mobile|hiptop|IEMobile|KYOCERA/WX310K|LG/U990|MIDP-2.|MMEF20|MOT-V|NetFront|Newt|Nintendo Wii|Nitro|Nokia|Opera Mini|Palm|PlayStation Portable|portalmmm|Proxinet|ProxiNet|SHARP-TQ-GX10|SHG-i900|Small|SonyEricsson|Symbian OS|SymbianOS|TS21i-10|UP.Browser|UP.Link|webOS|Windows CE|WinWAP|YahooSeeker/M1A1-R2D2|NF-Browser|iPhone|iPod|Android|BlackBerry9530|G-TU915 Obigo|LGE VX|webOS|Nokia5800#';
			
			update_site_option('nginxm_global_options', $nginxm_global_options);
			
			$nginxmNginx->log("UPGRADE :: Update to version 1.3.4 ... DONE");
			echo "<p><i>Update to version 1.3.4 ... DONE</i></p>";
			
		}
		
	} else {
		
		// Downgrade
		
		$nginxmNginx->log("DOWNGRADE :: Downgrading version from ".$current_db_version." to ".NGINXMDBVERSION."...");
		echo "<p>DOWNGRADE :: Downgrading version from ".$current_db_version." to ".NGINXMDBVERSION."...</p>";
		
	}
	
	// Update version
	echo "<h4>Updating current DB version</h4>";
	$nginxm_global_options = get_site_option('nginxm_global_options');
	$nginxm_global_options['current_db_version']  = NGINXMDBVERSION;
	update_site_option('nginxm_global_options', $nginxm_global_options);
	
	$nginxmNginx->log("UPDATE :: Update current version to ".NGINXMDBVERSION."... DONE");
	echo "<p>UPDATE :: Update current version to ".NGINXMDBVERSION."... DONE</p>";
	
	$nginxm_options = get_option('nginxm_options');
	
	return true;
	
}

/**
 * nginxm_upgrade_page() - This page showsup , when the database version doesn't fir to the script NGG_DBVERSION constant.
 * 
 * @return Upgrade Message
 */
function nginxm_upgrade_page()  {
    
	$filepath    = admin_url() . 'admin.php?page=' . $_GET['page'];
	
	if ( isset($_GET['upgrade']) && $_GET['upgrade'] == 'now') {
		nginx_start_upgrade($filepath);
		return;
	}
?>
<div class="wrap">
	<div class="icon32" id="icon-plugins"><br></div>
	<h2><?php _e('Upgrade Nginx Manager', 'nginxm') ;?></h2>
	<p><?php _e('The script detect that you upgrade from a older version.', 'nginxm') ;?>
	   <?php _e('Your database tables for Nginx Manager is out-of-date, and must be upgraded before you can continue.', 'nginxm'); ?>
       <?php _e('If you would like to downgrade later, please make first a complete backup of your database.', 'nginxm') ;?></p>
	<p><?php _e('The upgrade process may take a while, so please be patient.', 'nginxm'); ?></p>
	<h3><a href="<?php echo $filepath;?>&amp;upgrade=now"><?php _e('Start upgrade now', 'nginxm'); ?>...</a></h3>      
</div>
<?php
}

/**
 * nginx_start_upgrade() - Proceed the upgrade routine
 * 
 * @param mixed $filepath
 * @return void
 */
function nginx_start_upgrade($filepath) {
?>
<div class="wrap">
	<div class="icon32" id="icon-plugins"><br></div>
	<h2><?php _e('Upgrade Nginx Manager', 'nginx'); ?></h2>
	<p><?php nginxm_upgrade(); ?></p>
	<p class="finished"><b><?php _e('Upgrade finished', 'nginx'); ?></b></p>
	<h3><a class="finished" href="<?php echo $filepath; ?>"><?php _e('Continue', 'nginx'); ?>...</a></h3>
</div>
<?php
}

?>