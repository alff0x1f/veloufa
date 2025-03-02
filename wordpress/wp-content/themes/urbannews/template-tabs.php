<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 * Template Name: Template Tabs
 */
global $data;
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="container tabs-template" id="tabs-container">

<h1 class="title"><?php the_title(); ?></h1>

<?php		
global $post;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$tabCategory = get_post_meta($post->ID, 'siiimple_tab_cat', TRUE);
$tabCategoryNum = get_post_meta($post->ID, 'siiimple_tab_cat_num', TRUE);
$args = array(
'category_name' => $tabCategory,
'numberposts' => $tabCategoryNum,
'order' => 'ASC'
);
$services_posts = get_posts($args);
?>

<div class="idTabs">

	<ul class="list">
	
	<?php $count=0;foreach($services_posts as $post) : setup_postdata($post); $count++; ?>
	<?php 
	$thumb = get_post_thumbnail_id();
	$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
	$image = aq_resize( $img_url, 65, 65, true );
	?>
       
       <li>
       <div class="tab-title-wrap">
       <?php if ( $image ) { ?> 
				
					<img src="<?php echo $image ?>" class="img-tab" alt="image"/>
				
				<?php } ?>
				
		
       <a href="#tab-<?php echo $count; ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></div></li>
       
    <?php endforeach; ?>
    
    </ul>
    
	
    <div class="services-content">
    
    <?php $count=0;foreach($services_posts as $post) : setup_postdata($post);$count++; ?>
	<?php $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'grid-thumb'); ?>
	
		<div id="tab-<?php echo $count; ?>" class="service-content-item">
		<h2 class="tab-sub-title"><?php the_title(); ?></h2>
			<?php the_content(); ?>
		</div>
		 
	<?php endforeach; ?>
		 
    </div> <!-- END SERVICES CONTENT -->
 
 </div> <!-- END ID TABS -->
 
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>