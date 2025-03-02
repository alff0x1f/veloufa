<?php 

global $nginxmNginx, $nginxm;

$error_msg   = '';
$updated_msg = '';

?>

<div class="wrap">
	
	<?php
	
	// save new URL
	if (isset($_POST['url'])) {
		
		$url = $_POST['url'];
		
		// check valid URL
		if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) {
			
			// update options
			$nginxm->options['purgeable_url']['urls'][] = $url;
			update_option( "nginxm_options", $nginxm->options );
			
			$updated_msg = __("URL correctly added.","nginxm");
			
		} else {
			$error_msg = __("Insert a valid URL.","nginxm");
		}
	}
	
	// remove personal URL from options
	if (isset($_POST['remove-purl-by-key'])) {
		
		if (array_key_exists($_POST['remove-purl-by-key'], $nginxm->options['purgeable_url']['urls'])) {
			
			unset($nginxm->options['purgeable_url']['urls'][$_POST['remove-purl-by-key']]);
			update_option( "nginxm_options", $nginxm->options );
			
			$updated_msg = __("URL correctly deleted.","nginxm");
		}
		
	}
	
	// purge personal URLs
	if (isset($_POST['nm-purge-purls']) && $_POST['nm-purge-purls'] == '1') {
		
		$nginxmNginx->log( "Purging personal urls from Personal URLs subpage BEGIN ===" );
		
		if (isset($nginxm->options['purgeable_url']['urls'])) {
			
			foreach ($nginxm->options['purgeable_url']['urls'] as $u) {
				$nginxmNginx->purgeUrl($u, false);
			}
			
			$updated_msg = __("Personal URLs purged.","nginxm");
			
		} else {
			
			$nginxmNginx->log( "No personal urls available" );
			$updated_msg = __( "No personal urls available.", "nginxm" );
		}
		
		$nginxmNginx->log( "Purging personal urls from Personal URLs subpage END ^^^" );
	}
	?>
	
	<div id="icon-options-general" class="icon32"><br /></div>
	
	<h2><?php echo __("Personal URLs","nginxm") ?></h2>
	
	<p><?php echo __("Add your personal URL to be purged.","nginxm") ?></p>
	<span class="description"><?php echo sprintf( __(" URL must be valid to be accepted (e.g. 'http://%s/my-snippet.html').","nginxm"), $_SERVER['HTTP_HOST']) ?></span>
	
	<p>
		<?php 
		if ( isset($nginxm->options['purgeable_url']['urls']) && count($nginxm->options['purgeable_url']['urls'])>0 ) {
			?>
			<form style="display: inline;" id="nm-purge-purls-form" name="nm-purge-purls-form" method="post" action="#">
				<input type="hidden" name="nm-purge-purls" value="1" />
				<a style="cursor: pointer;" onclick='jQuery("#nm-purge-purls-form").submit();'>Purge personal URLs</a>
			</form> or <?php 
		}?>
		<a style="cursor: pointer;" onclick='jQuery("#nm-add-url-form").show("blind");'>Add URL</a>
	</p>
	
	<form id="nm-add-url-form" name="nm-add-url-form" method="post" action="#" style="display: none;">
		<input id="nm-add-url-url" style="width:60%;" type="text" name="url" />
		<input type="submit" name="smart_http_expire_save" class="button-primary" value="Save" />
	</form>
	<?php if ($error_msg) { ?>
		<div class="error" id="message"><p><strong><?php echo $error_msg;?></strong></p></div>
	<?php } else if ($updated_msg) { ?>
		<div class="updated" id="message"><p><strong><?php echo $updated_msg;?></strong></p></div>
	<?php } ?>
	<br />
	
	<?php 
	if ( isset($nginxm->options['purgeable_url']['urls']) && count($nginxm->options['purgeable_url']['urls'])>0 ) {
		
		?>
		<table class="widefat fixed">
			
			<thead>
				<tr class="thead">
					<th>URL</th>
					<th style="text-align: center;width: 10%">Remove URL</th>
				</tr>
			</thead>
			
			<tfoot>
				<tr class="tfoot">
					<th>URL</th>
					<th style="text-align: center;">Remove URL</th>
				</tr>
			</tfoot>
			
			<tbody><?php 
				
				foreach ($nginxm->options['purgeable_url']['urls'] as $key => $u) {
					?>
					<tr>
						<td><?php echo $u; ?></td>
						
						<td style="text-align: center;">
							<form id="remove-purl-<?php echo $key?>" method="post" action="" name="post">
								<input type="hidden" name="remove-purl-by-key" value="<?php echo $key ?>" />
								<a style="cursor: pointer;" onclick="if(confirm('Stai per rimuovere questo URL.\r\n\r\nContinuare?')){jQuery('#remove-purl-<?php echo $key?>').submit();}"><b>X</b></a>
							</form>
						</td>
					</tr>
					<?php 
				} ?>
			</tbody>
			
		</table><?php 
		
	} else { ?>
		<h2><?php echo __("No URL saved","nginxm") ?></h2><?php 
	} ?>
	
</div>