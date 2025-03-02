

<?php global $data; ?>

<h2 class="sub-header photos"><span class="title-wrap"><a href="<?php echo $data['photo_carousel_header_link'] ?>"><?php echo $data['photo_carousel_header'] ?>&nbsp;&rsaquo;&rsaquo;</a></span></h2>
			
<div id="sections-photo" class="carousel module">
				
	<ul>
				
		<?php 
		$args=array( 'showposts' => '-1','category_name' => $data['photo_carousel'] );  
		$my_query = new WP_Query($args);$count=0;
		if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
		$count++;
			
		//SMALL IMAGES
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
		$image = aq_resize( $img_url, 305, 155, true );
		$video = get_post_meta($post->ID, 'siiimple_video', TRUE);
			
		//LARGE IMAGES
		$thumb_large = get_post_thumbnail_id();
		$img_url_large = wp_get_attachment_url( $thumb_large,'index-blog' );			
		$image_large = aq_resize( $img_url_large, 500, 500, true );
		?>
					
			<li>
			
				<?php if ($image) { ?>
				<img src="<?php echo $image ?>" alt="<?php the_title(); ?>"/>	
				<a class="photo-gallery extend" href="<?php echo $image_large; ?>"><span class="icon-eye-2"></span></a>
				<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
				<?php } else if(!$image) { ?>

				<img src="<?php echo get_template_directory_uri(); ?>/framework/images/no-img-extra-large.png" alt="<?php the_title(); ?>"/>	
				<a class="photo-gallery extend" href="<?php echo $image_large; ?>"><span class="icon-eye-2"></span></a>
				<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
				<?php } ?>
					
			</li>
					
		<?php endwhile; } ?>
		<?php wp_reset_query(); ?>	
				
	</ul>
				
</div><!-- END SECTION PHOTOS -->

