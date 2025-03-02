<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 * Template Name: Template Categories
 */
global $data;
get_header(); ?>

<div class="container cat-template" id="single">

<h1>Collection of Categories</h1>

<?php
wp_reset_query();
$count=0;
$cats = get_categories('');
foreach ($cats as $cat) :
if($cat->category_parent)  continue; 
$args = array(
'posts_per_page' => 5,
'category_name' => $cat->slug,);
query_posts($args); // reset to original
if (have_posts()) : $count++;?>

	<div class="grid4 col cat-grid <?php if($count == '4' || $count=="8" || $count=="12" || $count=="16"  ) { echo 'no-margin'; } ?>">

		<h2><a href=""><?php echo $cat->name ?></a></h2>

		<ul>
			<?php $count2=0; ?>
			<?php while (have_posts()) : the_post(); ?>	
			<?php $count2++; ?>

			<?php if ($count2 == 1) { ?>
			<?php $thumb = get_post_thumbnail_id();$img_url = wp_get_attachment_url( $thumb,'index-blog' );$image = aq_resize( $img_url,262,202, true ); ?>	

			
			<?php if ($image) { ?>
				<img src="<?php echo $image ?>" class="img-feat"/>
			<?php } ?>
			

				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>

			<?php } else { ?> 

				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>

			<?php } ?>
			<?php endwhile; ?>

		</ul>
	</div>

<?php else : echo '<h2>No Posts for '.$cat->name.' Category</h2>';?>

<?php endif; wp_reset_query; ?>

<?php endforeach; ?>

</div>

<?php get_footer(); ?>