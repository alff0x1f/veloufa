<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */
global $data;
get_header(); ?>

<!-- file: <?=basename(__FILE__)?>  -->
<div class="container page-content page-sidebar" id="page-template">

	<div class="grid11 col">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 659, true ); ?>
		
		<!-- SUB TEXT -->
		<?php $subtext = get_post_meta($post->ID, 'siiimple_subtext_page', TRUE); ?>
		
		<!-- PAGE GALLERY -->
		<?php $page_gallery = get_post_meta($post->ID, 'siiimple_basic_page_gallery', TRUE); ?>
		
		<!-- VIDEO -->
		<?php $video_page = get_post_meta($post->ID, 'siiimple_video_page', TRUE); ?>
		
		<!-- SET VIEWS -->
		<?php setPostViews(get_the_ID()); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
		
		<div class="img-wrap <?=(!$image)?"noimg":null ?>">
			
			<?php 

			if ($image) { ?>
			
				<h2><span class="uniform-bg"><span><?php the_title(); ?></span></span></h2>
				<?php 
                    $size = getimagesize($image);
                    $iw = $size[0];
                    $ih = $size[1];
                    $prp = ($iw/$ih);
                    $hor = ($prp>1.3)?"background-size:100%; height:".($ih)."px":"background-size:50%; height:".($ih/2)."px;";
                    //$vert = ($prp<1.3)?":null;
                    $kv = ($prp>1 && $prp<1.3)?"background-size:50%; height:".($ih)."px;":null;
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
				<!-- <?=$prp?>  ## <?=$iw/$ih?> <img src="<?=$image?>" width="<?=$iw?>" height="<?=$ih?>" class="single-img" alt="<?php the_title(); ?>"/>  -->
                
				
            <?php } 
			else if ( $page_gallery == '1' ) { ?>
					
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
  	    	
  	    			</div>
  	    	<!--FLEXSLIDER LOADING-->
			<?php } 
            else if (!$image) { ?> 
				<h2 class="<?=(!$$video_page)?"video":"no-image" ?>">
				<!-- <span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/>--> 
				<!-- <a href="<?php the_permalink(); ?>"> --><span class="uniform-bg"><span><?php the_title(); ?></span></span><!-- </a> --></h2>
				<?php if ($subtext && !$image) { ?><h3 class="subtext-area"><?php echo $subtext ?>&nbsp;<span class="end">&diams;</span></h3><?php } ?>	
				<?php if ($video_page && !$image) { ?><iframe src="<?php echo $video_page; ?>" width="659" height="400" frameborder="0" class="vid"></iframe><?php } ?>	

			<?php }
			?>

		
			</div>
			<!-- END IMG WRAP -->
			
			<!-- CLEAR -->
			<div class="clear"></div>
			
			<?php if ($subtext && $image) { ?><h3 class="subtext-area"><?php echo $subtext ?>&nbsp;<span class="end">&diams;</span></h3><?php } ?>
			<!-- ##@## -->

			<div class="left-content" style="display:none">
				<ul>
					<li class="posted"><?php the_time('M j, Y') ?></li>
					<li class="written"><?php the_author_meta('display_name'); ?></li>
					<!-- <li class="comments"><span class="comments"><?php comments_popup_link(__('No comments yet', 'siiimple'), __('1 comment', 'siiimple'), __('% comments', 'siiimple')); ?></span></li> -->
					<li class="views"><?php echo getPostViews(get_the_ID()); ?></li>
				</ul><!-- END UL -->
				
				<?php 
					global $wpdb;
					$images = get_post_meta( get_the_ID(), 'siiimple_sidebar_img', false );
					$images = implode( ',' , $images );
					// Re-arrange images with 'menu_order'
					$images = $wpdb->get_col( "
    				SELECT ID FROM {$wpdb->posts}
    				WHERE post_type = 'attachment'
    				AND ID in ({$images})
    				ORDER BY menu_order ASC
					" );
				
					foreach ( $images as $att )
					{
    		
    				$src = wp_get_attachment_image_src( $att, 'thumbnail' );
    				$src = $src[0];
    				// Show image
   	 				echo "<img src='{$src}' class='sidebar-img'/>";
					} 
				?>

			<?php include (TEMPLATEPATH . '/framework/includes/related.php'); ?>
			
			<?php if($data['disable_ratings'] !='disable') { ?> 
			<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
			<?php } ?>
       			
       		</div>
            
            <!-- END LEFT CONTENT -->
			
			<div class="right-content">

				<div class="content-wrap">
			
					<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
			
				</div>
			
			</div><!-- END RIGHT CONTENT -->
			
			<!-- CLEAR -->
		<div class="clear"></div>
		
		<?php include (TEMPLATEPATH . '/framework/includes/share.php'); ?>
			
		</div><!-- END POST -->
		
		<!-- CLEAR -->
		<div class="clear"></div>

		<?php comments_template(); ?>
		
		<?php endwhile; endif; ?>
		
	</div><!-- END GRID 11 -->
	
	<?php get_sidebar(); ?>
	
	</div><!-- some weird div -->
	
</div><!-- end container -->
	
<?php get_footer(); ?>
