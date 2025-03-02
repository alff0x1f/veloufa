<?php
/*
Plugin Name: 2-4 comment fix
Plugin URI: http://www.wordpressplugins.ru/critical/2-4-comment-fix.html
Description: Исправляет окончания в комментариях. Делает из "2 комментариев" - "2 комментария". Редакция плагина от <a href="http://www.wordpressplugins.ru">WordpressPlugins.ru</a>.
Version: 1.00.1
Author: Ján Varhol 
Author URI: http://varhol.sk/
*/

/*
Для использования активируйте плагин и вставьте в файлы шаблона строчку 
<?php if(function_exists('comments_popup_link_2_4')) { comments_popup_link_2_4('Комментировать', '% Комментарий', '% Комментариев', '% Комментария'); } ?>
*/

function comments_number_2_4( $zero = false, $one = false, $more = false, $twotofour = false, $deprecated = '' ) {
	global $id;
	$number = get_comments_number($id);

	if ( $number == 0) {
		$output = ( false === $zero ) ? __('No Comments') : $zero; }
	elseif ((($number > 1) && ($number < 5)) || ((($number % 10) > 1) && (($number % 10) < 5)) && ($number > 20)) {
		$output = str_replace('%', $number, ( false === $twotofour ) ? __('% Comments') : $twotofour); }
	elseif ((($number > 20) && (($number % 10) == 1)) || ($number == 1)) {
		$output = str_replace('%', $number, ( false === $one ) ? __('% Comment') : $one); }
	else {		
	$output = str_replace('%', $number, ( false === $more) ? __('% Comments') : $more); }

	echo apply_filters('comments_number_2_4', $output, $number);
}


function comments_popup_link_2_4($zero='No Comments', $one='% Comment', $more='% Comments', $twotofour='% Comments', $CSSclass='', $none='Comments Off') {
	global $id, $wpcommentspopupfile, $wpcommentsjavascript, $post, $wpdb;

	if ( is_single() || is_page() )
		return;

	$number = get_comments_number($id);

	if ( 0 == $number && 'closed' == $post->comment_status && 'closed' == $post->ping_status ) {
		echo '<span' . ((!empty($CSSclass)) ? ' class="' . $CSSclass . '"' : '') . '>' . $none . '</span>';
		return;
	}

	if ( !empty($post->post_password) ) { // if there's a password
		if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			echo(__('Enter your password to view comments'));
			return;
		}
	}

	echo '<a href="';
	if ($wpcommentsjavascript) {
		if ( empty($wpcommentspopupfile) )
			$home = get_option('home');
		else
			$home = get_option('siteurl');
		echo $home . '/' . $wpcommentspopupfile.'?comments_popup='.$id;
		echo '" onclick="wpopen(this.href); return false"';
	} else { // if comments_popup_script() is not in the template, display simple comment link
		if ( 0 == $number )
			echo get_permalink() . '#respond';
		else
			comments_link();
		echo '"';
	}

	if (!empty($CSSclass)) {
		echo ' class="'.$CSSclass.'"';
	}
	$title = attribute_escape(get_the_title());
	echo ' title="' . sprintf( ('Комментировать статью &quot;%s&quot;'), $title ) .'">';
	comments_number_2_4($zero, $one, $more,$twotofour, $number);
	echo '</a>';
}
add_filter('comments_popup_link','comments_popup_link_2_4');

?>
