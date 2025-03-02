<?php  
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * Load the meta boxes
 *
 */

function nginxm_admin_overview() {
	
	global $nginxmNginx, $nginxm; ?>
	
	<div class="wrap">
		
		<div class="icon32" id="icon-index"><br /></div>
		<h2>Overview</h2>
		
		<h3>WP HTTP Proxy</h3>
			
		<?php if (defined('WP_PROXY_HOST') && defined('WP_PROXY_PORT') && defined('WP_PROXY_BYPASS_HOSTS')) { ?>
			
			<p>Configured
			
				<table class="form-table">
				
					<tr>
						<th>WP_PROXY_HOST</th>
						<td><?php echo WP_PROXY_HOST ?></td>
					</tr>
				
					<tr>
						<th>WP_PROXY_PORT</th>
						<td><?php echo WP_PROXY_PORT ?></td>
					</tr>
					
					<tr>
						<th>WP_PROXY_BYPASS_HOSTS</th>
						<td><?php echo WP_PROXY_BYPASS_HOSTS ?></td>
					</tr>
					
				</table>
			
			</p>
			
			<p><b style="color : #ff0000">IMPORTANT</b> <br />If you are using a proxy you must modify the file <i>class-http.php</i> and delete lines 1624 and 1625.
			
<pre><code>if ( $check['host'] == 'localhost' || $check['host'] == $home['host'] )
	return false;</code></pre></p>
			
		<?php } ?>
		
		<p>Not configured</p>
		
		<h3>HTTP connections</h3>
		<p>Can your WP installation open HTTP connetions to purge cache ? <?php echo ($nginxmNginx->checkHttpConnection() == 'OK') ? '<b style="color : #00aa00">YES</b>' : '<b style="color : #ff0000">NO - This plugin will probably now works.</b>' ?></p>
		
		<h3>NGINX configuration</h3>
		<p>You have to setup your NGINX propely to make this plugin works. Here you find a template for your NGINX setup.</p>
		
		<div id="dashboard-widgets-wrap" class="nginxm-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" style="width:100%;">
							<?php do_meta_boxes('NGINXMFOLDER', 'normal', ''); ?>
						</div>
					</div>
				</div>
		    </div>
		</div>
		
		<p>Some useful link
			<ul>
				<li><a href="http://wiki.nginx.org/" title="NGINX Wiki">Nginx Wiki</a></li>
				<li><a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21" title="HTTP Expires">HTTP Expires</a></li>
			</ul>
		</p>
		
		
		<?php 
		/* Checks for custom post types, @since Nginx Manager 1.2 */ 
		?>
		<h3>Custom post types</h3>
		
		<?php 
		if ($_custom_types = get_post_types(array('public' => true, '_builtin' => false))) {
			if ( count( $_custom_types ) > 0 && is_array( $_custom_types ) ) {
				echo "<p>Custom post types defined: <b>".implode($_custom_types, "</b>, <b>")."</b></p>";
			}
		} else {
			?><p>No custom post types defined</p><?php 
		}
		
		
		/* Checks for custom taxonomies, @since Nginx Manager 1.2 */
		?>
		<h3>Custom taxonomies</h3>
		
		<?php 
		if ($_custom_taxa = get_taxonomies(array('public' => true, '_builtin' => false))) {
			
			// Get rid of the standard taxonomies
			foreach ($_custom_taxa as $tkey => $ctax) {
				if ( in_array( $ctax, array( 'category', 'post_tag', 'link_category' ) ) ) {
					unset($_custom_taxa[$tkey]);
				}
			}
			
			if ( count( $_custom_taxa ) > 0 && is_array( $_custom_taxa ) ) {
				echo "<p>Custom taxonomies defined: <b>".implode($_custom_taxa, "</b>, <b>")."</b></p>";
			} else {
				?><p>No custom taxonomies defined, there are built-in taxonomies only</p><?php 
			}
			
		} else {
			?><p>No custom taxonomies defined</p><?php 
		}
		?>
		
		
		<h3>Debug</h3>
		
		<!-- Global options -->
		<p><a style="cursor: pointer;" onclick='if(jQuery(".debug-global-options").is(":hidden")){jQuery(".debug-global-options").show("blind");}else{jQuery(".debug-global-options").hide("blind");}'>Global options</a></p>
		<div class="debug-global-options" style="display: none;background-color: #eeeeee;padding: 5px;">
			<?php 
			echo "<pre>";
			var_dump($nginxm->global_options);
			echo "</pre>";
			?>
		</div>
		
		<!-- Local options -->		
		<p><a style="cursor: pointer;" onclick='if(jQuery(".debug-local-options").is(":hidden")){jQuery(".debug-local-options").show("blind");}else{jQuery(".debug-local-options").hide("blind");}'>Local options</a></p>
		<div class="debug-local-options" style="display: none;background-color: #eeeeee;padding: 5px;">
			<?php 
			echo "<pre>";
			var_dump($nginxm->options);
			echo "</pre>";
			?>
		</div>
		
		<!-- Nginxm vars -->
		<p><a style="cursor: pointer;" onclick='if(jQuery(".debug-nginxmLoader-vars").is(":hidden")){jQuery(".debug-nginxmLoader-vars").show("blind");}else{jQuery(".debug-nginxmLoader-vars").hide("blind");}'>nginxmLoader vars</a></p>
		<div class="debug-nginxmLoader-vars" style="display: none;background-color: #eeeeee;padding: 5px;">
			<p>Version: <?php echo "<b>".$nginxm->version."</b>"; ?></p>
			<p>DB Version: <?php echo "<b>".$nginxm->db_version."</b>"; ?></p>
			<p>Minium WP: <?php echo "<b>".$nginxm->minium_WP."</b>"; ?></p>
			<p>Plugin name: <?php echo "<b>".$nginxm->plugin_name."</b>"; ?></p>
		</div>
		
		<!-- Constants -->
		<p><a style="cursor: pointer;" onclick='if(jQuery(".debug-nginxm-costants").is(":hidden")){jQuery(".debug-nginxm-costants").show("blind");}else{jQuery(".debug-nginxm-costants").hide("blind");}'>Constants</a></p>
		<div class="debug-nginxm-costants" style="display: none;background-color: #eeeeee;padding: 5px;">
			<p>NGINXMFOLDER: <?php echo "<b>".NGINXMFOLDER."</b>"; ?></p>
			<p>NGINXMVERSION: <?php echo "<b>".NGINXMVERSION."</b>"; ?></p>
			<p>NGINXMDBVERSION: <?php echo "<b>".NGINXMDBVERSION."</b>"; ?></p>
		</div>
			
	</div>

<?php
	
}

add_meta_box('dashboard_files', "Cached files", 'meta_nginx_files', 'NGINXCFILES', 'normal', 'core');
add_meta_box('dashboard_right_now', "Example", 'meta_nginx_conf', 'NGINXMFOLDER', 'normal', 'core');

function meta_nginx_conf() {
?>

<pre id="nginx_conf">
				
 proxy_cache_path /path/to/cache/your_cache levels=1:2 keys_zone=YOUR_CACHE_NAME:10m inactive=30m max_size=2g;

 # This must be the first rule for "location" 
 location ~ /purge(/.*) {
	allow 127.0.0.1;
	# allow 11.22.33.44;
	deny all;
	proxy_cache_purge YOUR_CACHE_NAME "$scheme://$host$1";
 }	
					
 location / {
					
	# If logged in, don't cache.
	# This must be the same as the one you have in the "Setup" 
	if ($http_cookie ~* "comment_author_|wordpress_(?!test_cookie)|wp-postpass_" ) {
		set $do_not_cache 1;
	}

	proxy_cache_key "$scheme://$host$request_uri$do_not_cache";
	proxy_cache YOUR_CACHE_NAME;
	proxy_pass http://ALL_backend;
					
 }
					
 # Do not cache request for admin
					
 location ~* wp\-.*\.php|wp\-admin {
	proxy_pass http://ALL_ADMIN_backend;
 }
					
 location ~ \.(gif|jpg|png|css|jpeg|js)$ {
	proxy_pass http://ALL_backend;
	proxy_cache YOUR_CACHE_NAME;
	proxy_cache_valid 200 10m; # Lifetime in NGINX cache 
	proxy_cache_use_stale error timeout invalid_header updating http_500 http_502 http_503 http_504;
	access_log off;
	expires 10m; # Lifetime in user browser cache | Set "Expires" HTTP header returned to the user. See note for details.
 }

</pre>
<?php } ?>