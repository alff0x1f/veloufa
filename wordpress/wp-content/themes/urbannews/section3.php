<?php global $data; ?>

<div id="front">

		<h2 class="sub-header"><span class="title-wrap"><a href="<?php echo $data['section3_link'] ?>"><?php echo $data['section3_header'] ?>&nbsp;&rsaquo;&rsaquo;</a></span></h2>
		
		<ul>
			<?php $count=0;
			$args=array( 'showposts' => '13','category_name' => $data['section3_cat'] );  $my_query = new WP_Query($args);
			if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
			$count++; ?>
			

			<?php if ($count == 1) { ?>
			
			<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 250,270, true ); ?>	


			<li class="first-cat">
			
				<div class="cat-wrp">
				
					<?php if ($image) { ?>
					<h2 class="cat-header-first"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<img src="<?php echo $image ?>" class="img-feat" alt="<?php the_title(); ?>"/>
					<?php } ?>
				
				</div>
				
			</li>
				
			<?php } else if ($video){ ?> 
			
			<?php $video = get_post_meta($post->ID, 'siiimple_video', TRUE); ?>
				
				<div class="cat-wrap-small">
				
					<li>
			
						<a class="various fancybox.iframe" href="<?php echo $video; ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>"/></a>
				
						<span class="play-icon"></span>
				
					</li>
				
				</div>	

			<?php } else { ?> 
			
			<?php 
			$thumb2 = get_post_thumbnail_id();
			$img_url2 = wp_get_attachment_url( $thumb2,'index-blog' );
			$image2 = aq_resize( $img_url2, 80,80, true ); 
			?>
			
			<?php 
			$thumb21 = get_post_thumbnail_id();
			$img_url21 = wp_get_attachment_url( $thumb21,'index-blog' );
			$image21 = aq_resize( $img_url2, 500, true ); 
			?>
				
				
				
				<li class="cat-wrap-small">
					
					<?php if ($image2) { ?>
					
					<img src="<?php echo $image2 ?>" class="img-cat-small" alt="image"/>
					<a class="photo-gallery extend" href="<?php echo $image21; ?>"><span class="icon-eye-2"></span></a>
					<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
					
					<?php } else if (!$image2) { ?>
					
						<img src="<?php echo get_template_directory_uri(); ?>/framework/images/no-img-small.jpg" alt="<?php the_title(); ?>"/>

					
					<?php } ?>
				</li>
				
			
			<?php } ?>
			
			<?php endwhile; } ?>
			<?php wp_reset_query(); ?>

		</ul>
	
	</div>
<div class="clear"></div>
