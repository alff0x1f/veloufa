

<?php global $data; ?>

<h2 class="sub-header video"><span class="title-wrap"><a href="<?php echo $data['featured_video_header_link'] ?>"><?php echo $data['featured_video_header'] ?>&nbsp;&rsaquo;&rsaquo;</a></span></h2>
		
<div id="sections-video" class="carousel module">
				
	<ul>
				
		<?php 
			$args=array( 'showposts' => $data['featured_video_num'],'category_name' => $data['featured_videos'] );  $my_query = new WP_Query($args);$count=0;
			if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
			$count++;
			
			//SMALL IMAGES
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
			$image = aq_resize( $img_url, 150, 150, true );
			$video = get_post_meta($post->ID, 'siiimple_video', TRUE);
			
			//LARGE IMAGES
			$thumb2 = get_post_thumbnail_id();
			$img_url2 = wp_get_attachment_url( $thumb2,'index-blog' );			
			$image2 = aq_resize( $img_url2, 500, true );
		?>
			
		<?php if ($video) { ?>
				
			<li>
			
				<?php if ($image) { ?>
				<a class="various fancybox.iframe" href="<?php echo $video; ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>"/></a>				
				<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2">&nbsp;&nbsp;<?php the_title(); ?></span></a>
				<?php } else if (!$image) {?>
					<a class="various fancybox.iframe" href="<?php echo $video; ?>"><img src="<?php echo get_template_directory_uri(); ?>/framework/images/no-img-small-no-sidebar.png" alt="<?php the_title(); ?>"/></a>
				<span class="play-icon"></span>
				<?php } ?>
			
			</li>
			
		<?php } else { ?>
			
			<li>
						
				<?php if ($image) { ?>
					<img src="<?php echo $image ?>" alt="<?php the_title(); ?>"/>	
					<a class="photo-gallery extend" href="<?php echo $image2; ?>"><span class="icon-eye-2"></span></a>
					<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
				<?php } else if (!$image) {?>
					<img src="<?php echo get_template_directory_uri(); ?>/framework/images/no-img-small-no-sidebar.png" alt="<?php the_title(); ?>"/>
					<a class="photo-gallery extend" href="<?php echo $image2; ?>"><span class="icon-eye-2"></span></a>
					<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
				<?php } ?>
								
			</li>
			
		<?php } ?>
			
		<?php endwhile; } ?>
		<?php wp_reset_query(); ?>		
				
	</ul>
			
</div><!-- END SECTION VIDEO -->