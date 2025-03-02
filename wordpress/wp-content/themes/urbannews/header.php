<!DOCTYPE html>
<?php global $data; ?>
<html <?php language_attributes(); ?>>
<head>
<!-- CHARSET -->
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<!-- TITLE -->
<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>
<meta name="google-site-verification" content="Fd3aKMv2_XvjrVr-94FV68u-RHpsIX4HTX6MLV0LXCg" />
<link href="http://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic,700italic&subset=latin,cyrillic" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
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
    <meta property="og:title" content="<?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="<?='http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]; ?>"/>
    <meta property="og:image" content="<?php echo wp_get_attachment_thumb_url( get_post_thumbnail_id( $post->ID ) ); ?>"/>
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
    <meta property="og:description" content="<?php if (is_home()) { echo $data['meta-desc']; } else setup_postdata(get_post($post->ID)); echo excerpt(58) ;?>"/> 
<!-- PINGBACK -->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/css/ie.css" />
<![endif]-->

<!-- APPLE TOUCH ICONS -->
<?php if(!empty($data['custom_apple_touch_icon_1'])) { ?><link rel="apple-touch-icon" href="<?php echo $data['custom_apple_touch_icon_1']; ?>"><?php } ?>
<?php if(!empty($data['custom_apple_touch_icon_2'])) { ?><link rel="apple-touch-icon" sizes="72x72" href="<?php echo $data['custom_apple_touch_icon_2']; ?>"><?php } ?>
<?php if(!empty($data['custom_apple_touch_icon_3'])) { ?><link rel="apple-touch-icon" sizes="114x114" href="<?php echo $data['custom_apple_touch_icon_3']; ?>"><?php } ?>

<!-- TRACKING HEADER -->
<?php echo stripslashes($data['tracking_header']); ?>

<!-- WP HEAD -->
<?php wp_head(); ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style_veloufa.css" type="text/css" media="screen" />
<!--<script type="text/javascript"  src="//vk.com/js/api/openapi.js?72" ></script>

<script  type="text/javascript">
  VK.init({apiId: 3292227, onlyWidgets: true});
</script>-->

<!-- END HEAD -->
</head>
	
<body <?php body_class(); ?>>
<div id="wrap">
<?php if($data['disable_topSlider'] !='disable') { ?> 

<div id="page_container">
  <div id="toppanel">
    	<div id="panel">
    		<div class="container" id="section-one">
				<div class="top_slider loading">
					<ul class="slides">
	
						<?php 
						$count=0;
						$args=array( 'showposts' => $data['top_carousel_num'],'category_name' => $data['top_carousel'] );  $my_query = new WP_Query($args);
						if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
						$count++;
		
						$thumb = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
						$image = aq_resize( $img_url,80,80, true );
						?>
	
						<li>
							<?php if ($image) { ?>
							<img src="<?php echo $image ?>" class="top-img" alt="<?php the_title(); ?>"/>
							<?php } ?>
							<h2 class="top-cat"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>	
							<p class="top-cap-excerpt"><?php echo excerpt(7); ?></p>	
						
						</li>
		
						<?php endwhile; } ?>
		
					</ul><!-- END SLIDES -->
		
				</div><!-- TOP SLIDER LOADING -->
	
			</div><!-- CONTAINER SECTION ONE -->

    	</div><!-- PANEL -->
    
    <div class="panel_button" style="display: visible;">
    	<img src="<?php echo get_template_directory_uri(); ?>/framework/images/plus-new.png"  alt="expand"/> 
    </div>
    
    <div class="panel_button" id="hide_button" style="display: none;">
    	<img src="<?php echo get_template_directory_uri(); ?>/framework/images/minus-new.png" alt="collapse" /> 
    </div>
  	
  	</div><!-- TOP PANEL -->    

</div><!-- PAGE CONTAINER -->	

<div class="clear"></div>

<?php } ?>
	
<div class="container" id="section-menu">

 	<?php if($data['disable_ticker'] !='disable') { ?>
	
	<div id="wrapper">
	
	<h5 class="breaking"><?php echo $data['breaking_news']; ?></h5>
			
		<div class="first">
		
			<dl id="ticker-1">
		
				<?php 
				$count=0;
				$args=array( 'showposts' => '-1','category_name' => $data['ticker'] );  $my_query = new WP_Query($args);
				if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
				$count++;
				?>
					
				<dt><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dt>
				<dd><?php echo excerpt(10); ?></dd>
			
			<?php endwhile; } ?>
	
			</dl><!-- END DL -->
			
		</div><!-- END FIRST -->
			
	</div><!-- END WRAPPER -->
		
	<div class="clear"></div>
	
	<?php } ?>

	<div id="nav">
	
		<?php wp_nav_menu( array(
		'theme_location' => 'main_menu',
		'sort_column' => 'menu_order',
		'menu_class' => 'sf-menu',
		'fallback_cb' => 'default_menu'
		)); 
		
		
		
		?>
	
	
	
	
	</div><!--END NAV-->
		
</div><!-- END CONTAINER SECTION MENU -->

<div class="container" id="section-two">

	<div class="grid4 col logo-area">
		
		<?php if($data['custom_logo'] !='') { ?>
        	
        	<a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><img src="<?php echo $data['custom_logo']; ?>" alt="<?php bloginfo( 'name' ) ?>" /></a>
        
        <?php } else { ?>
         	
         	<h1><a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
         	<p><?php bloginfo( 'description' ); ?></p>
         
		<?php } ?>
        
	</div><!-- END GRID4 -->
	
	
	<div style="float: right;"><!-- logo banner right align --></div>
	
	 
	
	
	<div class="grid12 col">
	
	<?php if($data['horizontal_ad'] !='') { ?>
		
		<a href="<?php echo $data['horizontal_ad_link']; ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><img src="<?php echo $data['horizontal_ad']; ?>" alt="<?php bloginfo( 'name' ) ?>" /></a>
		
	<?php } ?>

	</div><!-- END GRID12 -->
	
</div><!-- END CONTAINER SECTION TWO -->


<meta name='loginza-verification' content='bca3201c4d0fff4b9c53617a7aea3919' />
