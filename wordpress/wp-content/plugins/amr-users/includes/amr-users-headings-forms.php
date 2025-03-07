<?php

function amr_list_user_admin_headings($l){

global $amain;
global $ausersadminurl;

if ( !is_admin() ) return;

echo PHP_EOL.'<div class="wrap"><!-- the nested wrap -->'.PHP_EOL;
echo '<table><tr><td>'.
	'<ul class="subsubsub" style="float:left; white-space:normal;">';

		$t = __('CSV Export','amr-users');
		$n = $amain['names'][$l];
		if (current_user_can('list_users') or current_user_can('edit_users')) {
			echo '<li style="display:block; float:left;">'
				.au_csv_link($t, $l, $n.__(' - Standard CSV.','amr-users')).'</li>';
			echo '<li style="display:block; float:left;"> |'.au_csv_link(__('Txt Export','amr-users'),
						$l.'&amp;csvfiltered',
						$n.__('- a .txt file, with CR/LF filtered out, html stripped, tab delimiters, no quotes ','amr-users')).'</li>';
			}
		if (current_user_can('manage_options')) {
			echo '<li style="display:block; float:left;"> | '
			.au_configure_link(__('Configure this list','amr-users'), $l,$n).'</li>';
			echo '<li style="display:block; float:left;"> | '.au_headings_link( $l)	.'</li>';			
		}
		echo '</ul>';
	
		echo '<ul class="subsubsub" style="float:left; white-space:normal;">';	
			
		echo '<li style="display:block; float:left;"> | '
			.au_buildcache_view_link(__('Rebuild cache now','amr-users'),$l,$n)
			.'</li>';
		echo  '<li style="display:block; float:left;"> | '.au_view_link(__('View','amr-users'), $l,$amain['names'][$l]).'</li>';	

		echo '</ul></td></tr></table>'.PHP_EOL.
		'</div><!-- end the nested wrap -->'.PHP_EOL;

}

function alist_searchform ($i) {
global $amain;
//	if (!is_rtl()) $style= ' style="float:right;" ';
//	else 
	$style= '';

	if (isset($_REQUEST['su']))
		$searchtext = stripcslashes(esc_textarea($_REQUEST['su']));
	else
		$searchtext = '';
	$text = '';
	$text .= PHP_EOL.'<div class="search-box" '.$style.'>'
//	.'<input type="hidden"  name="page" value="ameta-list.php"/>'
	.'<input type="hidden"  name="ulist" value="'.$i.'"/>';
//	echo '<label class="screen-reader-text" for="post-search-input">'.__('Search Users').'</label>';
	$text .= '<input type="text" id="search-input" name="su" value="'.$searchtext.'"/>
	<input type="submit" name="search_users" id="search-submit" class="button" value="'.__('Search Users', 'amr-users').'"/>';
	// 2015 08 05 rename search to search users
	// add domain for front end users
	$text .= PHP_EOL.'</div><!-- end search box-->'
	.PHP_EOL.'<div style="clear:both;"><br /></div>';
//	$text .= '</form>';
	return ($text);
}
 
function alist_per_pageform ($i) {
global $amain;

	if (empty($amain['list_rows_per_page'][$i]))  
		$amain['list_rows_per_page'][$i] = $amain['rows_per_page'];
	$rowsperpage = amr_rows_per_page($amain['list_rows_per_page'][$i]);  // will check for request

	$text = PHP_EOL;
	$text .= '<div class="perpage-box">'
	.'<input type="hidden"  name="ulist" value="'.$i.'"/>';
	$text .= '<label for="rows_per_page">'.__('Per page','amr-users').'</label>';
	$text .= '<input type="text" name="rows_per_page" id="rows_per_page" size="3" value="'.$rowsperpage.'">';
	$text .= '<input type="submit" name="refresh" id="perpage-submit" class="button" value="'.__('Apply','amr-users').'"/>';
	$text .= '</div>'.PHP_EOL;

	return ($text);
}
 
function amr_list_headings ($cols,$icols,$ulist, $sortable,$ahtm) {
global $aopt;

	if (amr_is_plugin_active('amr-users-plus-groupings/amr-users-plus-groupings.php')) { 
		//20170201 ouch plugin name changed stopped this working for a bit
		$icols = amr_remove_grouping_field ($icols);			
	}

	$l = $aopt['list'][$ulist];
	$html = '';	
	$cols = amr_users_get_column_headings ($ulist, $cols, $icols ); // should be added to cache rather		
	$cols = apply_filters('amr-users-headings', $cols,$icols,$ulist);  //**** test this

	foreach ($icols as $ic => $cv) { /* use the icols as our controlling array, so that we have the internal field names */

		if (($cv == 'checkbox')) {
			$html 	.= $ahtm['th'].' class="manage-column column-cb check-column" >'.htmlspecialchars_decode($cols[$ic]).$ahtm['thc'];
		}
		else {
			if ( isset ($cols[$ic]) ) {
				if ($sortable and (!($cv == 'checkbox')) ) {   // might not be a display field
					$v = amr_make_sortable($cv,htmlspecialchars_decode($cols[$ic]));
				}
				else 
					$v = htmlspecialchars_decode($cols[$ic]);
				
				if ($cv === 'comment_count')
					$v 	.= '<a title="'.__('Explanation of comment total functionality','amr-users')
									.'"href="https://wpusersplugin.com/1822/comment-totals-by-authors/">**</a>';

				$html 	.= $ahtm['th'].' class="th th'.$ic.'">'.$v.$ahtm['thc'];
				}
			}
		}
		$hhtml = $ahtm['tr'].'>'.$html.$ahtm['trc']; /* setup the html for the table headings */

	return ($hhtml);
}
 
function amr_make_sortable($colname, $colhead) { /* adds a link to the column headings so that one can resort against the cache */
global $aopt,$amr_current_list;

	$dir = 'SORT_ASC';
	$indicator = ' ';//' <span class="SORT">&#8597;</span>'; //&#11109;
	// change from $_REQUEST to $_GET as is only used via url at moment
	// if ever want to also use via posted form, then need to check $_POST too
	// some sites php server settings aren't correct, or they may have cookies that overwrite in $_REQUEST
	if (!empty($_GET['sort'])) {
		if ($_GET['sort'] == $colname) {
			if (!empty($_GET['dir'])) {
				if ($_GET['dir'] == 'SORT_ASC' ) {
					$dir = 'SORT_ASC';
					$indicator = '&nbsp;<span class="'.$dir.'">&uarr;</span>';
				}
				else {
					$dir = 'SORT_DESC';
					$indicator = '&nbsp;<span class="'.$dir.'">&darr;</span>';
				}	
			}
		}
	}
	else { // just settings 
		if (!empty($aopt['list'][$amr_current_list]['sortby'])) {			
			if (isset( $aopt['list'][$amr_current_list]['sortby'][$colname])) {			
				if (!empty($aopt['list'][$amr_current_list]['sortdir']) and 
					isset ($aopt['list'][$amr_current_list]['sortdir'][$colname]) ) {
					$dir = $aopt['list'][$amr_current_list]['sortdir'][$colname];	
				}
				if ($dir == 'SORT_ASC' ) 
						$indicator = ' <span class="'.$dir.'">&uarr;</span>';
					else 
						$indicator = ' <span class="'.$dir.'">&darr;</span>';
			}
		}
	}
// swop the direction for the link	
	if ($dir == 'SORT_ASC' ) {
		$dir = 'SORT_DESC';
	}
	else 
		$dir = 'SORT_ASC';
	
	$link = amr_adjust_query_args();  //keep filtering when re sorting etc and rwos per page
	$link = add_query_arg('sort', $colname, $link);
	$link = add_query_arg('dir',$dir,$link);
	$link = esc_url($link);	
			
	return('<a title="'.
	__('Click to sort.  Click again to change direction.','amr-users')
	.'" href="'.$link.'">'.$colhead.$indicator.'</a>');
}

?>