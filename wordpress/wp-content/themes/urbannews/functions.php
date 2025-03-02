<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */

if ( ! isset( $content_width ) ) 
$content_width = 980;

if ( function_exists( 'add_theme_support' ) )
	add_theme_support( 'post-thumbnails' );

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'full', '', '', true ); // Fullsize
	add_image_size( 'single_img', '150','150', true ); // Single Sidebar Img
}

//add_editor_style();
//add_theme_support( 'custom-background', $args );
//add_theme_support( 'custom-header', $args );

add_theme_support( 'post-formats', array( 'gallery' ) );
	
register_nav_menus(array('main_menu' => __('Primary','siiimple')));

add_theme_support( 'automatic-feed-links' );
load_theme_textdomain( 'siiimple', get_template_directory_uri() .'/lang' );
	
/* ================================================
SCRIPTS
================================================ */

function siiimple_scripts_function() {
	
	//enqueue jQuery
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-tabs');
	
	//CUSTOM
	wp_enqueue_script('custom', get_template_directory_uri() . '/framework/js/custom.js');
	
	wp_enqueue_script('menu', get_template_directory_uri() . '/framework/js/menu.js');
	
	//FLEXSLIDER
	wp_enqueue_script('init-flex', get_template_directory_uri() . '/framework/js/init-flexslider.js');
	
	//COMBINED
	wp_enqueue_script('combined', get_template_directory_uri() . '/framework/js/combined.js');
	
	//PANEL SLIDER
	wp_enqueue_script('slide', get_template_directory_uri() . '/framework/js/slide.js');
	
	if( is_page_template('template-tabs.php') ) {
	
		wp_enqueue_script('tabs', get_template_directory_uri() . '/framework/js/tabs.js');
	
		wp_enqueue_script('tabs-init', get_template_directory_uri() . '/framework/js/init-tabs.js');
	
	 }
	 
	 
	 //503
	if(is_page_template('503.php') ) {
		wp_enqueue_script('countdown', get_template_directory_uri() . '/framework/js/jquery.countdown.js');
		wp_enqueue_script('countdown2', get_template_directory_uri() . '/framework/js/init-503.js');
	}
	
	//end
	}
	
add_action('wp_enqueue_scripts','siiimple_scripts_function');

/* ================================================
CSS
================================================ */

function siiimple_enqueue_css() {
	
	//THEMEOPTIONS
	global $data;
	
	wp_enqueue_style('grid', get_template_directory_uri() . '/framework/css/grid.css', 'style');
	wp_enqueue_style('typography', get_template_directory_uri() . '/framework/css/typography.css', 'style');
	wp_enqueue_style('carousel', get_template_directory_uri() . '/framework/css/carousel.css', 'style');
	wp_enqueue_style('flex', get_template_directory_uri() . '/framework/css/flexslider.css', 'style');
	wp_enqueue_style('fancybox', get_template_directory_uri() . '/framework/css/jquery.fancybox.css', 'style');
	wp_enqueue_style('menu', get_template_directory_uri() . '/framework/css/menu.css', 'style');

	//TEMPLATE TABS
	if( is_page_template('template-tabs.php') ) { 
		wp_enqueue_style('tabs-css', get_template_directory_uri() . '/framework/css/tabs.css', 'style');
	}
		
	//RESPONSIVE
	if($data['disable_responsive'] !='disable') {
		wp_enqueue_style('responsive', get_template_directory_uri() . '/framework/css/responsive.css', 'style');
	}
	
	
}

add_action('wp_enqueue_scripts', 'siiimple_enqueue_css');

/* ================================================
 
SIDEBARS

================================================ */

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar 01',
		'id' => 'sidebar-one',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar 02',
		'id' => 'sidebar-two',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar 03',
		'id' => 'sidebar-three',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar 04',
		'id' => 'sidebar-four',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar 05',
		'id' => 'sidebar-five',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar 06',
		'id' => 'sidebar-six',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Left Sidebar 01',
		'id' => 'left-sidebar-01',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Left Sidebar 02',
		'id' => 'left-sidebar-02',
		'description' => 'Widgets in this area will be shown first section of single pages & blog.',
		 'before_widget' => '<div id="%1$s" class="sidebar">',
           'after_widget' => '</div>',
           'before_title' => '<h3 class="widgettitle"><span class="title-wrap">',
           'after_title' => '</span></h3>',
));

//FOOTER 1
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer 01',
		'id' => 'footer-one',
		'description' => 'Widgets in this area will be shown in the footer.',
		 'before_widget' => '<div id="%1$s" class="grid2 col first">',
           'after_widget' => '</div>',
           'before_title' => '<h2 class="footer-header">',
           'after_title' => '</h2>',
));

//FOOTER 2
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer 02',
		'id' => 'footer-two',
		'description' => 'Widgets in this area will be shown in the footer.',
		 'before_widget' => '<div id="%1$s" class="grid2 col">',
           'after_widget' => '</div>',
           'before_title' => '<h2 class="footer-header">',
           'after_title' => '</h2>',
));


//FOOTER 3
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer 03',
		'id' => 'footer-three',
		'description' => 'Widgets in this area will be shown in the footer.',
		 'before_widget' => '<div id="%1$s" class="grid8 col">',
           'after_widget' => '',
           'before_title' => '<h2 class="footer-header">',
           'after_title' => '</h2>',
));

//FOOTER 3
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer 03 Bottom',
		'id' => 'footer-three-bottom',
		'description' => 'Widgets in this area will be shown in the footer.',
		 'before_widget' => '',
           'after_widget' => '</div>',
           'before_title' => '<h2 class="footer-header bottom-widget">',
           'after_title' => '</h2>',
));


//FOOTER 4
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer 04',
		'id' => 'footer-four',
		'description' => 'Widgets in this area will be shown in the footer.',
		 'before_widget' => '<div id="%1$s" class="grid2 col last">',
           'after_widget' => '</div>',
           'before_title' => '<h2 class="footer-header">',
           'after_title' => '</h2>',
));

/* ================================================
 
CUSTOM CSS

================================================ */

function siiimple_custom_css() {
	
		global $data;
		
		$custom_css ='';
		
		//CUSTOM FIELD CSS
		if(!empty($data['custom_css'])) {
			$custom_css .= $data['custom_css'];
		}
		
		//TOP BUTTON BACKGROUND
		if(!empty($data['general_color'])) {
			
			$custom_css .= 'span.date-area,a.flex-active,.sf-menu li:hover,.sf-menu .current-menu-item,#section-three .thumb-wrap h2 a:hover,.btt:hover,.paginate .page-numbers.current,.paginate a.page-numbers:hover,.carousel .mask h2 a:hover,a.photo-gallery.extend:hover,a.page-link:hover,#sections-video.carousel ul li:hover .icon-eye,.blog_gallery .flex-control-paging li a:hover,.blog_gallery .flex-control-paging li a.flex-active,.bottom_slider ol.flex-control-nav.flex-control-paging li a.flex-active,#sections-bottom .bottom_slider h2 a:hover,.main_flexslider h2 a:hover,span.date-area,.main_flexslider p.date-main,.main_flexslider .flex-control-paging li a:hover,.blog_gallery .flex-control-paging li a:hover { background:'.$data['general_color'].' !important; }';
			
			$custom_css .= ' h2 a:hover,h3 a:hover,h4 a:hover,a:hover,a:focus,h2.sub-header,#section-five ul li a:hover,#single #related_posts ul li a,#single #related_posts.bottom-single-related ul li h2 a:hover,#single #related_posts.bottom-single-related ul li h2 a:hover,a.comment-reply-link,.blog-template .right-content p a,span.logo-news,#wrapper dt a,.carousel .pagination-links li.current a,#sections-bottom .bottom_slider ol.flex-control-nav.flex-control-paging li a.flex-active,.post .content-wrap ul li a,.post .content-wrap ol li a { color:'.$data['general_color'].' !important; }';	
			
			$custom_css .= '  #section-one.container .top_slider .flex-direction-nav .flex-next:hover,#section-one.container .top_slider .flex-direction-nav .flex-prev:hover,.flexslider_gallery .flex-direction-nav .flex-next:hover,.flexslider_gallery .flex-direction-nav .flex-prev:hover,.main_flexslider .flex-direction-nav .flex-next:hover, .main_flexslider .flex-direction-nav .flex-prev:hover,.blog_gallery .flex-direction-nav .flex-next:hover, .blog_gallery .flex-direction-nav .flex-prev:hover,.quote_flexslider .flex-direction-nav .flex-next:hover,.quote_flexslider .flex-direction-nav .flex-prev:hover,.page_gallery .flex-direction-nav .flex-next:hover,.page_gallery .flex-direction-nav .flex-prev:hover{ background-color:'.$data['general_color'].' !important; }';
						
		}
		
		if(!empty($data['icon_colors'])) {
			$custom_css .= '.post [class^="icon-"]:before  { color:'.$data['icon_colors'].' !important; }';
		}
		
		if(!empty($data['icon_size'])) {
			$custom_css .= '.post [class^="icon-"]:before  { font-size:'.$data['icon_size'].' !important; }';
		}
		
		//TOP PANEL BACKGROUND
		if(!empty($data['body_border_color'])) {
			
			$custom_css .= 'body  { border-top:5px solid '.$data['body_border_color'].' !important; }';
			
		}
		
		//TOP BUTTON BACKGROUND
		if(!empty($data['panel_btn_color'])) {
			
			$custom_css .= '#toppanel .panel_button  { background:'.$data['panel_btn_color'].' !important; }';
			
		}
		
		//TOP PANEL BACKGROUND
		if(!empty($data['panel_bg_color'])) {
			
			$custom_css .= '#panel  { background: '.$data['panel_bg_color'].' !important; }';
			
		}
		
		//TOP PANEL ITEM BORDER LEFT
		if(!empty($data['panel_btn_color_left'])) {
			
			$custom_css .= '.top_slider ul.slides li  { border-left: 1px solid '.$data['panel_btn_color_left'].' !important; }';
			
		}
		
		//TOP PANEL ITEM BORDER RIGHT
		if(!empty($data['panel_btn_color_right'])) {
			
			$custom_css .= '.top_slider ul.slides li  { border-right: 1px solid '.$data['panel_btn_color_right'].' !important; }';
			
		}
		
		//TICKER BACKGROUND
		if(!empty($data['ticker_color'])) {
			
			$custom_css .= '#wrapper dt  { background: '.$data['ticker_color'].' !important; }';
			
		}
		
		//ECHO CSS
		$css_output = "<!-- Custom CSS -->\n<style type=\"text/css\">\n" . $custom_css . "\n</style>";
		
		if(!empty($custom_css)) {
			echo $css_output;
		}
}

add_action('wp_head', 'siiimple_custom_css');

/* ================================================
NAVIGATION
================================================ */

function wp_corenavi() {
  global $wp_query, $wp_rewrite;
  $pages = '';
  $max = $wp_query->max_num_pages;
  if (!$current = get_query_var('paged')) $current = 1;
  $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
  $a['total'] = $max;
  $a['current'] = $current;
 
  $total = 1; //1 - display the text "Page N of N", 0 - not display
  $a['mid_size'] = 5; //how many links to show on the left and right of the current
  $a['end_size'] = 1; //how many links to show in the beginning and end
  $a['prev_text'] = '&laquo; Previous'; //text of the "Previous page" link
  $a['next_text'] = 'Next &raquo;'; //text of the "Next page" link
 
  if ($max > 1) echo '<div class="paginate">';
  if ($total == 1 && $max > 1) /* $pages = '<span class="pages">Page ' . $current . ' of ' . $max . '</span>'."\r\n"; */
  echo $pages . paginate_links($a);
  if ($max > 1) echo '</div>';
}

/* ================================================
EXCERPT
================================================ */

function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
    }

    function content($limit) {
      $content = explode(' ', get_the_content(), $limit);
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
      } else {
        $content = implode(" ",$content);
      } 
      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content); 
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
    }

/* ================================================
POST VIEWS
================================================ */

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' просмотров';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/* ================================================
INSERT END (Remove if Preferred)
================================================ */

function insertRSSFootNote($content) {
        if(!is_feed() && !is_home()) {
                $content.= "";
                
        }
        return $content;
}
add_filter ('the_content', 'insertRSSFootNote');

/* ================================================
TWITTER
================================================ */

function rarst_twitter_user( $username, $field, $display = false ) {
$interval = 3600;
$cache = get_option('rarst_twitter_user');
$url = 'http://api.twitter.com/1/users/show.json?screen_name='.urlencode($username);

if ( false == $cache )
$cache = array();

// if first time request add placeholder and force update
if ( !isset( $cache[$username][$field] ) ) {
$cache[$username][$field] = NULL;
$cache[$username]['lastcheck'] = 0;
}

// if outdated
if( $cache[$username]['lastcheck'] < (time()-$interval) ) {

// holds decoded JSON data in memory
static $memorycache;

if ( isset($memorycache[$username]) ) {
$data = $memorycache[$username];
}
else {
$result = wp_remote_retrieve_body(wp_remote_request($url));
$data = json_decode( $result );
if ( is_object($data) )
$memorycache[$username] = $data;
}

if ( is_object($data) ) {
// update all fields, known to be requested
foreach ($cache[$username] as $key => $value)
if( isset($data->$key) )
$cache[$username][$key] = $data->$key;

$cache[$username]['lastcheck'] = time();
}
else {
$cache[$username]['lastcheck'] = time()+60;
}

update_option( 'rarst_twitter_user', $cache );
}

if ( false != $display )
echo $cache[$username][$field];
return $cache[$username][$field];
}

/* ================================================
AVERAGE READER FEEDBURNER
================================================ */ 

function feed_subscribers(){
        $feed_url = 'http://feeds.feedburner.com/yourname';
        $count = get_transient('feed_count');
        if ($count != false) return $count;
        $count = 0;
        $data  = wp_remote_get('http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.$feed_url.'');
   if (is_wp_error($data)) {
        return 'error';
   }else{
        $body = wp_remote_retrieve_body($data);
        $xml = new SimpleXMLElement($body);
        $status = $xml->attributes();
        if ($status == 'ok') {
                $count = $xml->feed->entry->attributes()->circulation;
        } else {
                $count = 300; // fallback number
        }
   }
        set_transient('feed_count', $count, 60*60*24); // 24 hour cache
        echo $count;
}

/* ================================================
FUNCTIONS
================================================ */

//SHORTCODES
require_once( get_template_directory() . '/framework/functions/shortcodes.php');

//AQUA RESIZER
require_once( get_template_directory() . '/framework/functions/aq_resizer.php');

//THEME OPTIONS
require_once( get_template_directory() . '/admin/index.php');

// ADMIN ONLY
if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) {
	// Re-define meta box path and URL
	define( 'RWMB_URL', trailingslashit( get_stylesheet_directory_uri() . '/framework/meta-box' ) );
	define( 'RWMB_DIR', trailingslashit( get_stylesheet_directory() . '/framework/meta-box' ) );

	// Include the meta box script
	require_once RWMB_DIR . 'meta-box.php';

	// Include the meta box definition (the file where you define meta boxes, see `demo/demo.php`)
	include 'framework/functions/meta.php';
}

/* ================================================
COMMENTS
================================================ */

if ( ! function_exists( 'twentyeleven_comment' ) ) :

function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'siiimple' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'siiimple' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s %2$s <span class="says"></span>', 'siiimple' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s в %2$s', 'siiimple' ), get_comment_date(), get_comment_time() )
							)
						);
					?>
					
					<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Ответить <span>&darr;</span>', 'siiimple' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->

					<?php edit_comment_link( __( 'Редактировать', 'siiimple' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'siiimple' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()
//END


/**
 * Функция возвращает окончание для множественного числа слова на основании числа и массива окончаний
 * @param  $number Integer Число на основе которого нужно сформировать окончание
 * @param  $endingsArray  Array Массив слов или окончаний для чисел (1, 4, 5),
 *         например array('яблоко', 'яблока', 'яблок')
 * @return String
 */
function getNumEnding($number, $endingArray)
{
    $number = $number % 100;
    if ($number>=11 && $number<=19) {
        $ending=$endingArray[2];
    }
    else {
        $i = $number % 10;
        switch ($i)
        {
            case (1): $ending = $endingArray[0]; break;
            case (2):
            case (3):
            case (4): $ending = $endingArray[1]; break;
            default: $ending=$endingArray[2];
        }
    }
    return $ending;
}


/// fb social graph

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

function insert_fb_in_head() {
	global $post;
	if ( !is_singular()) //if it is not a post or a page
		return;
        echo '<meta property="fb:admins" content="YOUR USER ID"/>';
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="Your Site NAME Goes HERE"/>';
	if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
		$default_image="http://example.com/image.jpg"; //replace this with a default image on your server or an image in your media library
		echo '<meta property="og:image" content="' . $default_image . '"/>';
	}
	else{
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
	}
	echo "
";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );


?>