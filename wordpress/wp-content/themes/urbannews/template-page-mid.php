<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 * Template Name: Template Page w/ Middle Section
 */
global $data;
get_header(); ?>

<div class="container page-content midmid" id="page-template">

	<div class="grid11 col">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 653, true ); ?>
		
		<!-- SUB TEXT -->
		<?php $subtext = get_post_meta($post->ID, 'siiimple_subtext_page', TRUE); ?>
		
		<!-- PAGE GALLERY -->
		<?php $page_gallery = get_post_meta($post->ID, 'siiimple_basic_page_gallery', TRUE); ?>
		
		<!-- VIDEO -->
		<?php $video_page = get_post_meta($post->ID, 'siiimple_video_page', TRUE); ?>
		
		<!-- SET VIEWS -->
		<?php setPostViews(get_the_ID()); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
		
			<div class="img-wrap">
		
			<?php if ($image) { ?>
			
					<h2><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
					<img src="<?php echo $image ?>" class="single-img" alt="image"/>
				
			<?php } else if ($video_page) { ?>
  	    		
  	    		<iframe src="<?php echo $video_page; ?>" width="659" height="400" frameborder="0" class="vid"></iframe>
  	    		
  	    		<div class="clear" style="height:10px;"></div>
			
			<?php } else if ( $page_gallery == '1' ) { ?>
					
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
							<?php $image_gallery = aq_resize($attachment_url, 659, true); //resize & retain image proportions (soft crop) ?>
				
								<li class="page_gallery_img_wrap"><img src="<?php echo $image_gallery ?>"/></li>
					
		  	    			<?php endforeach; ?>
                        
		  	    			<?php endif; ?>
		  	    
		  				</ul>
  	    	
  	    			</div><!--FLEXSLIDER LOADING-->
			
			<?php } ?>
			
			</div><!-- END IMG WRAP -->
			
			<!-- CLEAR -->
			<div class="clear"></div>
			
			<?php if ($subtext && $image) { ?>
			
			<h3 class="subtext-area"><?php echo $subtext ?>&nbsp;<span class="end">&diams;</span></h3>
			
			<?php } ?>
			
			<div class="page-content">
			
				<?php if (!$image) { ?>
				<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php } ?>			
				
				<?php if ($subtext && !$image) { ?>
			
				<h3 class="subtext-area"><?php echo $subtext ?>&nbsp;<span class="end">&diams;</span></h3>
			
				<?php } ?>
			
				<div class="content-wrap">
			
					<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
			
				</div>
				
				<ul class="share-area">

					<li class="first">Share Post</li>
			
					<!-- FACEBOOK -->
					<li><iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink() ?>&amp;layout=button_count&amp;width=80&amp;action=like&amp;colorscheme=light&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:20px;" allowTransparency="true"></iframe></li>
		
					<!-- LINKEDIN -->		
					<li><script type="in/share" data-url="<?php the_permalink(); ?>" data-counter="right"></script></li>
			
					<!-- TWITTER -->	
					<li><a href="http://twitter.com/share?url=<?php echo urlencode(get_permalink($post->ID)); ?>&via=siiimple&count=horizontal" class="twitter-share-button">Tweet</a></li>
				
					<!-- DIGG -->
					<li><a class="DiggThisButton DiggCompact" href="http://digg.com/submit?url=<?php the_permalink(); ?>"></a></li>
				
				</ul><!-- END SHARE AREA -->
			
			</div><!-- END RIGHT CONTENT -->
			
		</div><!-- END POST -->
		
		<!-- CLEAR -->
		<div class="clear"></div>

		<?php comments_template(); ?>
		
		<?php endwhile; endif; ?>
		
	</div><!-- END GRID 11 -->
	
	<div class="grid3 col page-middle">
	
		<div class="section-inner">
		
				<?php 
				$count=0;
				$args=array( 'showposts' => $mid_num,'category_name' => $mid_cat );  $my_query = new WP_Query($args);
				if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
				$count++;
			
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
				$image = aq_resize( $img_url, 184, 125, true );
				?>

				<div class="thumb-wrap">

					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
					<?php if ($image) { ?>
					<img src="<?php echo $image ?>" class="img-feat" alt="img"/>
					<?php } ?>
					
					<p><?php echo excerpt(10); ?></p>
		
				</div><!-- END THUMBWRAP -->
			
			<?php endwhile; } ?>
			<?php wp_reset_query(); ?>	

		</div><!-- END INNER -->

	</div><!-- END GRID3 -->
	
<?php get_sidebar(); ?>
	
</div><!-- Mysterious DIV -->
	
</div><!-- END CONTAINER -->

<?php get_footer(); ?>