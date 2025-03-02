<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 659,350, true ); ?>
<?php $video = get_post_meta($post->ID, 'siiimple_video', TRUE); ?>

<?php if ($video) { ?>

<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	
	<iframe src="<?php echo $video; ?>" width="659" height="350" frameborder="0" class="vid"></iframe>
	
<?php } else if ($image) { ?>

<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	
	<img src="<?php echo $image ?>" class="single-img" alt="image"/>
	
<?php } else if (!$image){?>

<h2 class="no-image"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

<?php } ?>