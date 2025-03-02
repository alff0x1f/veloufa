<?php global $data; ?>



<h2 class="sub-header latest-bottom"><span class="title-wrap"><a href="<?php echo $data['section7_link'] ?>"><?php echo $data['section7_header'] ?>&nbsp;&rsaquo;&rsaquo;</a></span></h2>
	
<div id="blocks">
				
		<?php 
		$count=0;
		$args=array( 'showposts' => '4','category_name' => $data['section7_cat'] );  $my_query = new WP_Query($args);
		if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
		$count++;
	
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
		$image = aq_resize( $img_url, 150, 150, true );
		$video = get_post_meta($post->ID, 'siiimple_video', TRUE);	
		
		//LARGE IMAGES
		$thumb_large = get_post_thumbnail_id();
		$img_url_large = wp_get_attachment_url( $thumb_large,'index-blog' );			
		$image_large = aq_resize( $img_url_large, 500, 500, true );
		?>
		
		<div class="grid3 col page_gallery_grid <?php if($count == '4' || $count=="8" || $count=="12") { echo 'no-margin'; } ?>">
					
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
							<?php $image_gallery = aq_resize($attachment_url, 150,150, true); //resize & retain image proportions (soft crop) ?>
				
								<li><img src="<?php echo $image_gallery ?>"/ alt="img-gallery"></li>
					
		  	    			<?php endforeach; ?>
                        
		  	    			<?php endif; ?>
		  	    
		  				</ul>
  	    	
  	    			</div><!--FLEXSLIDER LOADING-->
					
				<?php } else if ( $image ) { ?>
				
					<a href="<?php the_permalink(); ?>"><img src="<?php echo $image ?>" class="single-img" alt="image"/></a>
				
				<?php } else if ( $video ) { ?> 
				
					<iframe src="<?php echo $video; ?>" width="150" height="150" frameborder="0" class="vid"></iframe>
				
				<?php } ?>

					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					
					<p class="intro-text"><?php echo excerpt(10); ?></p>
					
				</div><!-- END GRID3 -->

			<?php endwhile; } ?>
	
</div><!-- END LATEST BOTTOM -->