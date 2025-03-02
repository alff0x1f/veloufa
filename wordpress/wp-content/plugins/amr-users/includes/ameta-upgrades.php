<?php 
	
function amru_please_check_user_reg_settings () { //for versions priot to v4 on upgrade to 4.
global $pagenow, $amain;

	if (!is_admin() or !(current_user_can('manage_options')) ) return;
	if (!($pagenow == 'plugins.php') and  (!($pagenow == 'admin.php'))) return ;	
	if (empty($amain)) $amain = ausers_get_option('amr-users-main');		
	if (!isset($amain['version'])) return; 
	if (!empty($amain['notices_dismissed']['upgrade4.0'])) return;
	
	if (isset ($_REQUEST['dismiss_amr_users_notice'])) {
		$amain['notices_dismissed']['upgrade4.0'] = true;
		update_option('amr-users-main', $amain);
		return;
	}
	if (version_compare($amain['version'],'4.1','<'))


	echo ('<div class="updated notice is-dismissable">'
	.'<p><a href="'.admin_url('/?page=amr-users').'">'.
	__('Please check your user lists if you have upgraded from a version < 4.0','amr-users')
	.'</a></p><p>'
	.__('If you are using "user registration date" in a user list, you may need to adjust the format.','amr-users')
	.'</p>'.

	'<form method="post" action=""><p style="clear: both; class="submit">
		<input class="button-primary" type="submit" name="dismiss_amr_users_notice" value="'. __('Dismiss notice permanently', 'amr-users') .'" />
	</p></form>'
	.'</div>');
}

function amr_check_for_upgrades () {   // NB must be in order of the oldest changes first // called from ausers_get_option
// should already have values then - and will not be new ?
global $amain, $aopt;

	if (empty($amain)) $amain = ausers_get_option('amr-users-main');
	//if (WP_DEBUG) echo '<div class="message">Debug mode: check doing upgrade check </div>';
	// must be in admin and be admin
	if (!is_admin() or !(current_user_can('manage_options')) ) return;
			// handle a series of updates in order 
			
	if (!isset($amain['version'])) 
		$amain['version'] = '0'; // really old?
	if (version_compare($amain['version'],AUSERS_VERSION,'='))
		return;  // if same version, don't repeat check
	
	$prev = $amain['version'];
	echo PHP_EOL.'<div class="updated"><p>';  // closing div at end 
	
		printf(__('Previous version was %s. ', 'amr-users'),$prev );
		_e('New version activated. ', 'amr-users');
		_e('We may need to process some updates.... checking now... ', 'amr-users');

	
	// do old changes first - user may not have updated for a while....

	if ((!isset($amain['version'])) or  
	 (version_compare($amain['version'],'3.1','<'))) { // convert old options from before 3.1
	 
		echo '<br />';
		printf(__('Prev version less than %s', 'amr-users'),'3.1.');
		if (!isset($amain['csv_text'])) 
			$amain['csv_text'] = ('<img src="'
				.plugins_url('amr-users/images/file_export.png')
				.'" alt="'.__('Csv','amr-users') .'"/>');
		if (!isset($amain['refresh_text'])) 
			$amain['refresh_text'] =  ('<img src="'
			.plugins_url('amr-users/images/rebuild.png')
			.'" alt="'.__('Refresh user list cache', 'amr-users').'"/>');
				
		ausers_update_option('amr-users-main',$amain );	
		echo '<br />'.__('Image links updated.', 'amr-users');

		echo '</p>';
	}
	//
	if ((!isset($amain['version'])) or  
	 (version_compare($amain['version'],'3.3.1','<'))) { // check for before 3.3.1
		echo '<br />';
		printf(__('Prev version less than %s', 'amr-users'),'3.3.1.');
		$c = new adb_cache();
		$c->deactivate();
		
		if ((!ameta_cache_enable()) or  (!ameta_cachelogging_enable())) 
		echo '<h2>'.__('Problem creating amr user DB tables', 'amr-users').'</h2>';
		echo '<br />';
		_e('Cacheing tables recreated.', 'amr-users'); 
	}
	//
	if ((!isset($amain['version'])) or  
	 (version_compare($amain['version'],'3.3.6','<'))) { // check for before 3.3.6, 
		echo '<br />';
		printf(__('Prev version less than %s', 'amr-users'),'3.3.6. ');
		echo '</p>'.__('Minor sub option name change for avatar size', 'amr-users').'</p>';
		if (!empty($amain['avatar-size']))
			$amain['avatar_size'] = $amain['avatar-size']; //minor name fix for consistency
		else
			$amain['avatar_size'] = '16';
		unset($amain['avatar-size']);
		ausers_update_option('amr-users-main',$amain );	
		 
	}
// 3.4.4  July 2012
	if ((!isset($amain['version'])) or  
	 (version_compare($amain['version'],'3.4.4','<'))) { // check for before 3.3., 
		echo '<br />';
		printf(__('Prev version less than %s', 'amr-users'),'3.4.4 ');
		echo '<p><b>'.__('New Pagination option default to yes for all lists.', 'amr-users').'</b></p>';

		if (!isset($amain['show_pagination'])) {
			foreach ($amain['names'] as $i => $n) { 
				$amain['show_pagination'][$i] = true;
			}
		}		 
	}
//user_registration_date
// 20170115
	if ((!isset($amain['version'])) or  
	 (version_compare($amain['version'],'4.0','<'))) { // check for before 4.0, 
		echo '<br />';
		printf(__('Prev version less than %s', 'amr-users'),'4.0 ');
		echo '<p><b>'.__('Change dummy user registration date to real user registered with new format options.', 'amr-users').'</b></p>';
// first get the 'ago' format in for the existing 'registered days ago' option
		$aopt = ausers_get_option('amr-users' );

// then convert any user_registered_date fields across to the user_registered
		$aopt	= str_replace('user_registration_date', 'user_registered', $aopt);
	
		ausers_update_option('amr-users',$aopt );

		$nice_names = (ausers_get_option ('amr-users-nicenames'));

		if (!empty ($nice_names['user_registration_date'])) { 
			unset ($nice_names['user_registration_date']);
			ausers_update_option ('amr-users-nicenames',$nice_names);
		}
	
	}
	
	$amain['version'] = AUSERS_VERSION;
	ausers_update_option('amr-users-main',$amain );	 // was 'amr-users-no-lists'

		
	echo '<p>'.__('Finished Update Checks', 'amr-users').' ';
	echo ' <a href="http://wordpress.org/extend/plugins/amr-users/changelog/">'
	.__('Please read the changelog','amr-users' ).'</a>';
	echo '</p>'.PHP_EOL;
	echo '<br />'.__('As a precaution we will now rebuild the nice names.', 'amr-users');
	echo '<br />'.__('Relax .... you won\'t lose anything.', 'amr-users');
	ameta_rebuildnicenames ();
	echo '</div><!-- end updated -->'.PHP_EOL;
	
}


add_action('admin_notices', 	'amru_please_check_user_reg_settings');	
