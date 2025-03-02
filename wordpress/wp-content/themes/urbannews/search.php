<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */
global $data;
get_header(); ?>

<div class="container" id="single">

	<div class="grid11 col">
	
	<?php if (have_posts()) : ?>

	<?php $count = 0; ?>
	<?php while (have_posts()) : the_post(); ?>
	<!-- IMG CAPTION -->
	<?php $imgCaption = get_post_meta($post->ID, 'siiimple_img_caption', TRUE); ?>
	<!-- VIDEO -->
	<?php $video = get_post_meta($post->ID, 'siiimple_video', TRUE); ?>
	<?php $count++; ?>
	<?php if ($count == 1) : ?>
		
	<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 653, true ); ?>
		
		<div class="post first">
		
			<div class="img-wrap">
			
				<?php if ( has_post_format( 'gallery' )) { ?>
					
					<div class="flexslider_gallery loading">
  	    
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
							<?php $image_gallery = aq_resize($attachment_url, 653, true); //resize & retain image proportions (soft crop) ?>
				
								<li><img src="<?php echo $image_gallery ?>"/>
								<!--<p class="flex-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>-->
								</li>
					
		  	    			<?php endforeach; ?>
                        
		  	    			<?php endif; ?>
		  	    
		  				</ul>
  	    	
  	    			</div><!--FLEXSLIDER LOADING-->
					
				<?php } else if ( $video ) { ?>
				
				<iframe src="<?php echo $video; ?>" width="653" height="350" frameborder="0" class="vid"></iframe>
				
				<?php } else if ( $image ) { ?> 
				
				<img src="<?php echo $image ?>" class="single-img" alt="image"/>
				
				<?php if ($imgCaption) { ?>
			
					<span class="image-caption"><p><?php echo $imgCaption ?></p></span>
				
				<?php } ?>
				
				<?php } ?>
			
			</div><!-- END IMG WRAP -->
			
			<p class="date-archives"><?php the_time('l, F jS, Y') ?></p>
		
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			<p><?php echo excerpt(58); ?></p>
			<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
			
		</div>
		
	<?php else : ?>  
	
	<?php 
	$thumb = get_post_thumbnail_id();
	$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
	$image = aq_resize( $img_url, 150, 150, true );
	?>
			
		<div <?php post_class() ?>>
		
			<?php if ($image) { ?>
				<img src="<?php echo $image ?>" class="img-feat"/>
            <?php } else if (!$image) { ?>
                <a href="<?php the_permalink(); ?>"><img src="http://www.veloufa.ru/wp-content/themes/urbannews/framework/images/noimage<?=rand(1,3)?>.jpg" class="img-feat"/></a>
            <?php } ?>

			<p class="date-archives"><?php the_time('l, F jS, Y') ?></p>
		
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			<p><?php echo excerpt(38); ?></p>
			<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
			
		</div><!-- END POST -->
		<div class="clear"></div>
	<?php endif; ?>
	<?php endwhile; ?>
	<?php endif; ?>

		<div class="clear" style="height:30px;"></div>
		<?php if (function_exists('wp_corenavi')) wp_corenavi(); ?>
		<?php wp_link_pages(); ?>
		
	</div>

	<?php get_sidebar(); ?>

	</div>
	
</div>


<?php get_footer(); ?>