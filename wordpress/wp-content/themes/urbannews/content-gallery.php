<?php global $data; ?>

<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

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
			<?php $image_gallery = aq_resize($attachment_url, 659,350, true); ?>
				
				<li><img src="<?php echo $image_gallery ?>"/>

				</li>
					
		  	 <?php endforeach; ?>
                        
		  	 <?php endif; ?>
		  	    
		  	</ul>
  	    	
</div><!--FLEXSLIDER LOADING-->

