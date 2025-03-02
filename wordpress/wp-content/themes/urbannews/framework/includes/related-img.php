<?php $orig_post = $post;
	global $post;
	$categories = get_the_category($post->ID);
	
	if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
	
	$args=array(
		'category__in' => $category_ids,
		'post__not_in' => array($post->ID),
		'posts_per_page'=> 4, // Number of related posts that will be shown.
		'caller_get_posts'=>1
	);
	$my_query = new wp_query( $args );
	if( $my_query->have_posts() ) 
	
		
	{
	echo '<div id="related_posts" class="bottom-single-related"><h3 class="rs">Рекомендуемое</h3><ul>';
	
	while( $my_query->have_posts() ) {
	
	$my_query->the_post();
	
	$thumb2 = get_post_thumbnail_id();$img_url2 = wp_get_attachment_url( $thumb2,'index-blog' );$image2 = aq_resize( $img_url2, 150,150,true );
	?>
	
	<li><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>">
	<?php if ($image){ ?>
	<img src="<?php echo $image2; ?>">
	<?php } ?></a>
	
	<h2><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2></li>
		
	<?php } echo '</ul></div>'; } }
	$post = $orig_post;
	wp_reset_query(); 
?>