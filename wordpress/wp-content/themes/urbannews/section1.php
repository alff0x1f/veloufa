<?php global $data; ?>

<div id="section-three" class="grid4 col">

	<div class="section-inner">
	
		<?php if($data['disable_message'] !='disable') { ?> 
	
		<h3 class="intro"><?php echo $data['left-sidebar-header-message'] ?></h3>
		<p class="intro-text"><?php echo $data['left-sidebar-message'] ?></p>
		
		<?php } ?>
		
		<?php 
		$count=0;
		$args=array( 'showposts' => $data['popular_num'],'category_name' => $data['popular'] );  $my_query = new WP_Query($args);
		if ( $my_query->have_posts()  ) { while ($my_query->have_posts()) : $my_query->the_post(); 
		$count++;
			
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
		$image = aq_resize( $img_url, 170, 140, true );
		
		//LARGE IMAGES
		$thumb_large = get_post_thumbnail_id();
		$img_url_large = wp_get_attachment_url( $thumb_large,'index-blog' );			
		$image_large = aq_resize( $img_url_large, 500, 500, true );
		?>

			<div class="thumb-wrap">
			
				<div class="main-wrap">

					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
						<?php if ($image) { ?>
						
							<div class="section1-wrap">
				
								<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" class="section-one-img"/>
							
								<a class="photo-gallery extend" href="<?php echo $image_large; ?>"><span class="icon-eye-2"></span></a>
								<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
							
							</div>
				
						<?php } ?>
				
					</div>
			
				<p><?php echo excerpt(15); ?></p>
					
				<?php if($data['disable_ratings'] !='disable') { ?> 
				<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
				<?php } ?>
		
			</div><!-- END THUMBWRAP -->
			
		<?php endwhile; } ?>
		<?php wp_reset_query(); ?>	
			
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Sidebar 01') ) : ?><?php endif; ?>
			
		<div class="clear" style="height:20px;"></div>
			
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Sidebar 02') ) : ?><?php endif; ?>

	</div><!-- END INNER -->

</div><!-- SECTION THREE -->