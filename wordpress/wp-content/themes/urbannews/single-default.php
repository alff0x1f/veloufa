<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */

get_header();
?>
<!-- @file: <?=basename(__FILE__)?>  -->
<div class="container" id="single">

	<div class="grid11 col">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<?php 
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'full' );
		$image = aq_resize( $img_url, 659, true ); 
		?>
		
		<!-- SUB TEXT -->
		<?php $subtext = get_post_meta($post->ID, 'siiimple_subtext', TRUE); ?>
		
		<!-- IMG CAPTION -->
		<?php $imgCaption = get_post_meta($post->ID, 'siiimple_img_caption', TRUE); ?>
		
		<!-- VIDEO -->
		<?php $video = get_post_meta($post->ID, 'siiimple_video', TRUE); ?>
		
		<!-- SET VIEWS -->
		<?php setPostViews(get_the_ID()); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		
			<div class="img-wrap <?=(!$image)?"noimg":null ?>">
			
				<?php 
				if ( has_post_format( 'gallery' )) { ?>
					
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
								
								</li>
					
		  	    			<?php endforeach; ?>
                        
		  	    			<?php endif; ?>
		  	    
		  				</ul>
  	    	
  	    			</div><!--FLEXSLIDER LOADING-->
					
				<?php } 
				else if ( $video ) { ?>
				<!--VIDEO -->
				<h2 class="video"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('назад', 'siiimple'); ?></span><br/><!-- <a href="<?php the_permalink(); ?>"> --><span class="uniform-bg"><span><?php the_title(); ?></span></span><!-- </a> --></h2>
				
				<iframe src="<?php echo $video; ?>" width="653" height="350" frameborder="0" class="vid"></iframe>
				
				<?php } 
				else if ( $image ) { ?> 
				
				<h2><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('назад', 'siiimple'); ?></span><br/><!-- <a href="<?php the_permalink(); ?>"> --><span class="uniform-bg"><span><?php the_title(); ?></span></span><!-- </a> -->
					<?php if ($subtext) { ?> <p class="subtext-area"><?php echo $subtext ?></p> <?php } ?>
                </h2>
				
				<?php 
                    $size = getimagesize($image);
                    $iw = $size[0];
                    $ih = $size[1];
                    $prp = ($iw/$ih);
                    $hor = ($prp>1.3)?"background-size:100%; height:".($ih)."px":"background-size:50%; height:".($ih/2)."px;";
                    //$vert = ($prp<1.3)?":null;
                    $kv = ($prp>1 && $prp<1.3)?"background-size:50%; height:".($ih)."px!important;":null;
                ?>
                <style type="text/css">
                .img-wrap .img {
                    <?=$hor?>
                    <?=$vert?>
                    <?=$kv?>
                    }
                @media only screen and (min-width:320px) and (max-width:767px) {
                .img-wrap .img {height:<?=$ih/2?>px!important;}
                }
                </style>
                <div class="img" style="background-image: url('<?=$image?>');"></div>
				<!-- <?=$prp?> <img src="<?=$image?>" width="<?=$iw?>" height="<?=$ih?>" class="single-img" alt="<?php the_title(); ?>"/>  -->             

				<?php } ?>

				<?php if ( !$image ) { ?> 
					<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('назад', 'siiimple'); ?></span><br/><!-- <a href="<?php the_permalink(); ?>"> --><span class="uniform-bg"><span><?php the_title(); ?></span></span><!-- </a> --></h2><div class="clear"></div>
				<?php } ?>			
			</div><!-- END IMG WRAP -->
			
			<!-- CLEAR -->
			<div class="clear"></div>
			

			
			<div class="left-content">
			
				<ul>
				
				<li class="posted"><?php the_time('j F Y') ?></li>
				
				<li class="written"><?php the_author_posts_link(); ?></li>

					<!-- % <?php $comm = getNumEnding('%', array('комментарий', 'комментария', 'комментариев')) ?> -->
					<!-- % <?=$comm ?>-->
				<!-- <li class="comments"><span class="comments"><?php comments_popup_link(__('Добавить комментарий', 'siiimple'), __('1 комментарий', 'siiimple'), __('%', 'siiimple')); ?></span></li> -->

        			<li class="category"><?php echo get_the_category_list( __( ', ', 'siiimple' ) ); ?></li>			
					
					<?php the_tags( '<li class="tags">', ', ', '</li>'); ?>
					
					<li class="views"><?php echo getPostViews(get_the_ID()); ?></li>
					
				</ul><!-- END UL -->
				
				<?php 
				//	global $wpdb;
				//	$images = get_post_meta( get_the_ID(), 'siiimple_sidebar_img', false );
				//	$images = implode( ',' , $images );
					// Re-arrange images with 'menu_order'
				//	$images = $wpdb->get_col( "
    			//	SELECT ID FROM {$wpdb->posts}
    			//	WHERE post_type = 'attachment'
    			//	AND ID in ({$images})
    			//	ORDER BY menu_order ASC
				//	" );
				
				//	foreach ( $images as $att )
				//	{
    		
    			//	$src = wp_get_attachment_image_src( $att, 'thumbnail' );
    			//	$src = $src[0];
    				// Show image
   	 			//	echo "<img src='{$src}' class='sidebar-img'/>";
				//	} 
				?>

			 <?php // include( trailingslashit( get_template_directory() ). '/framework/includes/related.php' ); ?>
			
			<?php if($data['disable_ratings'] !='disable') { ?>
			<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
			<?php } ?>
       			
       		</div><!-- END LEFT CONTENT -->
			<!-- CLEAR -->
			<div class="clear"></div>
			<div class="right-content">
			
				<div class="content-wrap">
				

			
					<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
			
				</div>
			
			</div><!-- END RIGHT CONTENT -->
			
		</div><!-- END POST -->
		
		<!-- CLEAR -->
		<div class="clear"></div>
		
		<?php include( trailingslashit( get_template_directory() ). '/framework/includes/share.php' ); ?>
		
		<div class="clear"></div>
		
		<?php include( trailingslashit( get_template_directory() ). '/framework/includes/related-img.php' ); ?>
		
		<!-- CLEAR -->
		<div class="clear"></div>

		<?php comments_template( '', true ); ?>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.', 'siiimple'); ?></p>

	<?php endif; ?>
	
	</div><!-- END GRID11 COL -->
	
	<?php get_sidebar(); ?>
	
	</div><!-- Let the mystery begin. -->

</div><!-- END CONTAINER -->

<?php get_footer(); ?>
