<?php global $data; ?>
<div id="sections-quote">

	<div class="quote_flexslider loading">
  	    
  	    <ul class="slides">

			<?php 
			$count=0;
			$args=array( 'showposts' => $data['quote_num'],'category_name' => $data['quote_cat'] );  
			$my_query = new WP_Query($args);
			if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
			$count++;
	
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
			$image = aq_resize( $img_url, 125, 125, true );
			?>
		
				<li>
						
					<?php if ($image) { ?>
						
						<img src="<?php echo $image ?>" class="img-quote" alt="<?php the_title(); ?>"/>
					
					<?php } ?>
			
						<p><?php echo excerpt(80); ?></p>
						<div class="arrow-down"></div>
						<h5 class="quote-src"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			
				</li>
		
			<?php endwhile; } ?>
			<?php wp_reset_query(); ?>	
		
			</ul>
		
	</div><!-- END QUOTE FLEXSLIDER -->

</div><!-- END SECTIONS QUOTE -->