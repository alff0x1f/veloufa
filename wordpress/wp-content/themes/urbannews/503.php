<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 * Template Name: Template 503
 */
global $data;
?>

<!DOCTYPE html>
<?php global $data; ?>
<html <?php language_attributes(); ?>>

<head>

<!-- CHARSET -->
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<!-- TITLE -->
<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>

<!-- VIEWPORT -->
<?php if($data['disable_responsive'] !='disable') { ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php } ?>

<!-- DESCRIPTION -->
<meta name="description" content="<?php if(!empty($data['meta-desc'])) { ?><?php echo $data['meta-desc']; ?><?php } ?>" />

<!-- KEYWORDS -->
<meta name="keywords" content="<?php if(!empty($data['meta-key'])) { ?><?php echo $data['meta-key']; ?><?php } ?>" />

<!-- FAVICON -->
<?php if(!empty($data['custom_favicon'])) { ?><link rel="icon" type="image/png" href="<?php echo $data['custom_favicon']; ?>" /><?php } ?>

<!-- STYLESHEET -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<!-- PINGBACK -->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/css/ie.css" />
<![endif]-->

<!-- APPLE TOUCH ICONS -->
<?php if(!empty($data['custom_apple_touch_icon_1'])) { ?><link rel="apple-touch-icon" href="<?php echo $data['custom_apple_touch_icon_1']; ?>"><?php } ?>
<?php if(!empty($data['custom_apple_touch_icon_2'])) { ?><link rel="apple-touch-icon" sizes="72x72" href="<?php echo $data['custom_apple_touch_icon_2']; ?>"><?php } ?>
<?php if(!empty($data['custom_apple_touch_icon_3'])) { ?><link rel="apple-touch-icon" sizes="114x114" href="<?php echo $data['custom_apple_touch_icon_3']; ?>"><?php } ?>

<!-- TRACKING HEADER -->
<?php echo stripslashes($data['tracking_header']); ?>

<link href='http://fonts.googleapis.com/css?family=Raleway:400,200,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>

<!-- WP HEAD -->
<?php wp_head(); ?>

<!-- END HEAD -->
</head>	

<body <?php body_class(); ?> id="cdt">

<div class="container" id="countdown">

		<?php if(!empty($data['503_header'])) { ?>
			
			<h2><?php echo $data['503_header']; ?></h2>
		
		<?php } ?>

		<?php if(!empty($data['503_text'])) { ?>
			
			<p><?php echo $data['503_text']; ?></p>
		
		<?php } ?>

<div id="countdown">
	<p id="time"></p>
</div>

</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery.noConflict();
	var myTime = new Date(<?php if(!empty($data['503_year'])) { ?><?php echo $data['503_year']; ?><?php } ?>, <?php if(!empty($data['503_month'])) { ?><?php echo $data['503_month']; ?><?php } ?>-1, <?php if(!empty($data['503_day'])) { ?><?php echo $data['503_day']; ?><?php } ?>);
	jQuery('#time').countdown({
		format: 'odHMS',
		until: myTime
	});
});
</script>
