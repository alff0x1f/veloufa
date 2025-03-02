<?php global $data; ?>

<!-- <div class="clear"></div>-->

<div class="container" id="sections-bottom">

<h2 class="sub-header"><span class="title-wrap"><a href="<?php echo $data['section6_link'] ?>"><?php echo $data['section6_header'] ?>&nbsp;&rsaquo;&rsaquo;</a></span></h2>

	<div class="bottom_slider loading">
	
		<ul class="slides">
		
			<?php 
			$count=0;
			$args=array( 'showposts' => $data['section6_num'],'category_name' => $data['section6_cat'] );  $my_query = new WP_Query($args);
			if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
			$count++;
	
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
			$image = aq_resize( $img_url, 248, 187, true );
			?>
			
			<li>
		
				<div class="title-wrapper">
				<?php if ($image) { ?>
				<h2><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<img src="<?php echo $image ?>" class="img-quote" alt="<?php the_title(); ?>"/>	
				<?php } else if (!$image){ ?>
				<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php } ?>
				</div>
				<p class="intro-text"><?php echo excerpt(19); ?></p>	
						
			</li>
		
			<?php endwhile; } ?>
		
		</ul><!-- END SLIDES -->
		
	</div><!-- TOP SLIDER LOADING -->
	
</div><!-- CONTAINER SECTION ONE -->