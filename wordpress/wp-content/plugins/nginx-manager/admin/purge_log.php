<div class="wrap">

	<?php
	// Delete log file
	if (isset($_POST['delete_log'])) {
		if ($fp = fopen(NGINXM_ABSPATH .'log/current.log',"w+")) {
			fwrite($fp, "");
			fclose($fp);
		}
	}
	?>
	
	<div id="icon-options-general" class="icon32"><br /></div>
	
	<h2><?php echo __("Events log","nginxm") ?></h2>

	<?php if (!is_writable(NGINXM_ABSPATH .'log/current.log')) { ?>
		<span class="error fade" style="display : block"><p><?php printf (__("Can't write on log file.<br /><br />Check you have write permission on <strong>%s</strong>", "nginxm"), NGINXM_ABSPATH .'log/current.log'); ?></p></span>
	<?php } ?>
	
	<p><?php printf(__("Your log level is currently set to <strong>%s</strong>", "nginxm"), $nginxm->global_options['log_level']) ?>
	
	<?php if (file_exists(NGINXM_ABSPATH."log/current.log")) { ?>
		
		<ul class="subsubsub" id="log_filter">
			<li id="all_filter" style="font-weight : bold;"><a href="#" onClick="showLog('all')"><?php _e("All", 'nginxm') ?></a> | </li>
			<li id="nginxm_log_warning_filter"><a href="#" onClick="showLog('nginxm_log_warning')"><?php _e("Warning", 'nginxm') ?></a> | </li>
			<li id="nginxm_log_error_filter"><a href="#" onClick="showLog('nginxm_log_error')"><?php _e("Error", 'nginxm') ?></a></li>
		</ul>
		
		<form id="post_form" method="post" action="#" name="smart_http_delete_log_form">
			<p class="search-box">
				<input type="submit" class="button" name="delete_log" value="<?php _e("Delete current log", 'nginxm') ?>">
			</p>
		</form>
		
		<?php
		
		$file_path 	= NGINXM_ABSPATH."log/current.log";
		
		// recupera i dati dal file
		$filesize 	= filesize( $file_path );
		$fh 		= fopen( $file_path, "r" );
		
		if ($filesize > 5000) {
			fseek( $fh, $filesize-5000 );
		}
		
		$log_file = "";
		
		$pattern = '/^.*\| (WARNING|ERROR|INFO)\ |.*$/';
		
		while( !feof( $fh ) ) {
			
			if ( $string = str_replace("\n", '', fgets( $fh ) ) ) {
				
				preg_match( $pattern, $string, $match );
				
				if ( count( $match ) > 1 ) {
					$log_file =$log_file."<li class=\"nginxm_log_".strtolower($match[1])."\">".stripslashes($string)."</li>";
				}
			}
			
		}
		
		fclose( $fh );

		?>
		
		<div style="width: 98%; height : 40em; overflow:auto; border : 1px solid #DFDFDF;"><ul style="margin : 6px;" id="nginxm_error_list"><?php echo $log_file;?></ul></div>
		
		<p><em><?php printf ( __("<a href=\"%1\$s\">Download</a> the whole log file (about %2\$s Mb).", "nginxm" ), WP_PLUGIN_URL."/nginx-manager/log/current.log", number_format(($filesize/1048576), 2, ',', '.') ) ?></em></p>
		
	<?php } else { ?>
		<p><em><?php _e( "No log file found.", "nginxm" ); ?></em></p>
	<?php } ?>
	
</div>
