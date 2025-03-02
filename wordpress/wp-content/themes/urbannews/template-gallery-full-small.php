<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 * Template Name: Template Full Gallery w/ Small Images
 */
global $data;
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- THUMB -->
<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 980, true ); ?>
		
<!-- SUB TEXT -->
<?php $subtext = get_post_meta($post->ID, 'siiimple_subtext', TRUE); ?>

<!-- VIDEO -->
<?php $video_main = get_post_meta($post->ID, 'siiimple_video_main', TRUE); ?>
		
<!-- IMG CAPTION -->
<?php $imgCaption = get_post_meta($post->ID, 'siiimple_img_caption', TRUE); ?>

<!-- PAGE GALLERY -->
<?php $page_gallery = get_post_meta($post->ID, 'siiimple_page_gallery', TRUE); ?>
		
<!-- SET VIEWS -->
<?php setPostViews(get_the_ID()); ?>

<div class="container gallery-full" id="single">

	<div class="grid11 col">
		
			<div class="img-wrap">
		
				<?php if ( ($page_gallery == '1') ) { ?>
					
					<div class="flexslider_gallery loading">
					
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  	    
  	    				<ul class="slides">
  	    	
  	    					<?php 
	  	    				$args = array(
	  	    				'orderby' => 'menu_order',
	  	    				'post_type' => 'attachment',
	  	    				'post_parent'    => get_the_ID(),
	  	    				'post_mime_type' => 'image',
	  	    				'post_status'    => null,
	  	    				'numberposts'    => -1,
	  	    				);
	  	    				$attachments = get_posts($args);
	  	    				?>
	  	    	
	  	   					<?php if ($attachments) : ?>
					 
	  	    				<?php foreach ($attachments as $attachment) : ?>
                        	
	  	    				<?php $attachment_url = wp_get_attachment_url($attachment->ID , 'full');  ?>
							<?php $image_gallery = aq_resize($attachment_url, 980, true); //resize & retain image proportions (soft crop) ?>
				
								<li class="page_gallery_img_wrap"><img src="<?php echo $image_gallery ?>"/></li>
					
		  	    			<?php endforeach; ?>
                        
		  	    			<?php endif; ?>
		  	    
		  				</ul>
  	    	
  	    			</div><!--FLEXSLIDER LOADING-->
  	    			
  	    		<?php } else if ( $video_main ) { ?>
  	    		
  	    		<h2 class="video"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  	    		
  	    		<iframe src="<?php echo $video_main; ?>" width="980" height="500" frameborder="0" class="vid"></iframe>
  	    		
  	    		<div class="clear" style="height:10px;"></div>
				
				<?php } else if ( $image ) { ?> 
				
				<h2 class="static-image"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
				<img src="<?php echo $image ?>" class="single-img" alt="image"/>
				
				<?php } else if (!$image){ ?>
				
				<h2 class="no-image"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

			<?php } ?>
			
			</div>
			
			<?php if ($subtext) { ?>
			
			<h3 class="subtext-area"><?php echo $subtext ?>&nbsp;<span class="end">&diams;</span></h3>
			
			<?php } ?>
			
			<!-- CLEAR -->
			<div class="clear"></div>
			
			<?php 
			$count=0;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$currentCategory = get_post_meta($post->ID, 'siiimple_page_cat', TRUE);
			$galleryNumber = get_post_meta($post->ID, 'siiimple_gallery_number', TRUE);
			$args=array(
   			'category_name' => $currentCategory,
			'paged'=> $paged,
    		'posts_per_page' => $galleryNumber
			);
			$temp = $wp_query;
			$wp_query= null;
			$wp_query = new WP_Query($args);

			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); $count++;
			
			//resize image
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
			$image = aq_resize( $img_url, 180, 150, true );
			$video = get_post_meta($post->ID, 'siiimple_video', TRUE);
			
			//LARGE IMAGES
			$thumb_large = get_post_thumbnail_id();
			$img_url_large = wp_get_attachment_url( $thumb_large,'index-blog' );			
			$image_large = aq_resize( $img_url_large, 500, 500, true );
			?>
			
			<div class="grid3 col page_gallery_grid <?php if($count == '5' || $count=="10" || $count=="15" || $count=="20" || $count=="25" || $count=="30" || $count=="35" || $count=="40" || $count=="45") { echo 'no-margin'; } ?>">
					
				<?php if ( has_post_format( 'gallery' )) { ?>
					
					<div class="page_gallery loading">
  	    
  	    				<ul class="slides">
  	    	
  	    					<?php 
	  	    				$args = array(
	  	    				'orderby' => 'menu_order',
	  	    				'post_type' => 'attachment',
	  	    				'post_parent'    => get_the_ID(),
	  	    				'post_mime_type' => 'image',
	  	    				'post_status'    => null,
	  	    				'numberposts'    => -1,
	  	    				);
	  	    				$attachments = get_posts($args);
	  	    				?>
	  	    	
	  	   					<?php if ($attachments) : ?>
					 
	  	    				<?php foreach ($attachments as $attachment) : ?>
                        	
	  	    				<?php $attachment_url = wp_get_attachment_url($attachment->ID , 'full');  ?>
							<?php $image_gallery = aq_resize($attachment_url, 180,150, true); //resize & retain image proportions (soft crop) ?>
								<li><img src="<?php echo $image_gallery ?>"/></li>
					
		  	    			<?php endforeach; ?>
                        
		  	    			<?php endif; ?>
		  	    
		  				</ul>
  	    	
  	    			</div><!--FLEXSLIDER LOADING-->
					
				<?php } else if ( $video ) { ?>
				
				<div class="gallery-small-wrap">
				
					<a class="various fancybox.iframe" href="<?php echo $video; ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>"/></a>
				<span class="play-icon"></span>
				
				</div>
				
					
				
				<?php } else if ( $image ) { ?> 
				
					<img src="<?php echo $image ?>" class="single-img" alt="image"/>
				
				<?php } ?>

					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					
					<p><?php echo excerpt(10); ?></p>
					
				</div><!-- END GRID3 -->
		
				<?php endwhile; endif; ?>
				<div class="clear" style="height:30px;"></div>
				<?php if (function_exists('wp_corenavi')) wp_corenavi(); ?>
				<?php $wp_query = null;
				$wp_query = $temp;
				wp_reset_query(); ?>
				
				<div class="clear" style="height:50px;"></div>
		
		</div><!-- END GRID11 -->
	
	</div>
		
</div><!-- END CONTAINER -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>