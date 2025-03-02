<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 * Template Name: Template Categories Mixed
 */
global $data;
get_header(); ?>

<div class="container cat-template-mixed" id="single">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php endwhile; endif; ?>

<?php
wp_reset_query();
$count=0;
$cats = get_categories('');
foreach ($cats as $cat) :
if($cat->category_parent)  continue; 
$args = array(
'posts_per_page' => 16,
'category_name' => $cat->slug,);
query_posts($args); // reset to original
if (have_posts()) : $count++;?>

	<div class="grid16 col cat-grid">

		<h2 class="cat-header"><span><a href=""><?php echo $cat->name ?></a></span></h2>
		

		<ul>
			<?php $count2=0; ?>
			<?php while (have_posts()) : the_post(); ?>	
			<?php $count2++; ?>

			<?php if ($count2 == 1) { ?>
			<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url, 450,498, true ); ?>	


			<li class="first-cat">
			
				<div class="cat-wrp">
				
					<h2 class="cat-header-first"><span class="date-area"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'siiimple'); ?></span><br/><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
					<?php if ($image) { ?>
					<img src="<?php echo $image ?>" class="img-feat"/>
					<?php } ?>
				
				</div>
				
				
				</li>
				
			<?php } else if ($video){ ?> 
			<?php $video = get_post_meta($post->ID, 'siiimple_video', TRUE);
			
			 ?>
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
			$image2 = aq_resize( $img_url2, 102,102, true ); 
			?>
			
			<?php 
			$thumb21 = get_post_thumbnail_id();
			$img_url21 = wp_get_attachment_url( $thumb21,'index-blog' );
			$image21 = aq_resize( $img_url2, 500, true ); 
			?>
				
				<div class="cat-wrap-small">
				
				<li>
					
					<?php if ($image2) { ?>
					
					<img src="<?php echo $image2 ?>" class="img-cat-small" alt="image"/>
					<a class="photo-gallery extend" href="<?php echo $image21; ?>"><span class="icon-eye-2"></span></a>
					<a class="page-link" href="<?php the_permalink(); ?>"><span class="icon-link-2"></span></a>
					
					<?php } ?>
					
					
				</li>
				</div>	
			
			<?php } ?>
			
			
			<?php endwhile; ?>

		</ul>
	
	</div>

<?php else : echo '<h2>No Posts for '.$cat->name.' Category</h2>';?>

<?php endif; wp_reset_query; ?>

<?php endforeach; ?>

</div>

<?php get_footer(); ?>