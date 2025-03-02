<?php global $data; ?>

<h2 class="sub-header latest-bottom"><span class="title-wrap"><a href="<?php echo $data['section5_link'] ?>"><?php echo $data['section5_header'] ?>&nbsp;&rsaquo;&rsaquo;</a></span></h2>


	<div class="grid11 col">
	<!-- <? $f=pathinfo(__FILE__); echo $file['basename'];?>  -->			
			<?php 
			$count=0;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$currentCategory = 'novosti';
			$galleryNumber = get_post_meta($post->ID, 'novosti', TRUE);
			$args=array(
   			'post_type'=>'post',
   			'category_name' => $currentCategory,
			'paged'=> $paged,
    		'posts_per_page' => $galleryNumber
			);
			$temp = $wp_query;
			$wp_query= null;
			$wp_query = new WP_Query($args);

			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); $count++;
			
			//resize image
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'index-blog' );			
			$image = aq_resize( $img_url, 130, 130, true );
			$video = get_post_meta($post->ID, 'siiimple_video', TRUE);

			//LARGE IMAGES
			$thumb_large = get_post_thumbnail_id();
			$img_url_large = wp_get_attachment_url( $thumb_large,'index-blog' );			
			$image_large = aq_resize( $img_url_large, 500, 500, true );

			?>
			
			<div>
			
					
				<?php if ( has_post_format( 'gallery' )) { ?>
					
			
				
				<?php } else if ( $image ) { ?> 
				
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="section5tbl"><tr>
    <td class="pp"><a href="<?php the_permalink(); ?>"><div> 
				
						<img src="<?php echo $image ?>" class="single-img" alt="<?php the_title(); ?>"/>
						
					
					</div></a></td>
				
				<?php } else if (!$image) { ?>
				
	<table border="0" cellspacing="0" cellpadding="0" class="section5tbl"><tr>
    <td class="pp"><a href="<?php the_permalink(); ?>"><img src="http://www.veloufa.ru/wp-content/themes/urbannews/framework/images/noimage<?=rand(1,3)?>.jpg"></a></td>
				
				<?php } ?>
<!-- 
<?php foreach((get_the_category()) as $category) { 
echo '<a href="'.get_category_link($category->cat_ID).'" title="'.$category->cat_name.'">'.$category->cat_name.'</a> ';
} ?>
-->
					<td class="ppp" style="vertical-align: top;"><span class="dt"><?php the_time('j F Y') ?></span>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo excerpt(30); ?></p>
					</td>
	</tr></table>	
				
				</div><!-- END GRID3 -->
		



		<?php endwhile; endif; ?>
				<!--<div class="clear" style="height:0px;"></div> -->
				<?php if (function_exists('wp_corenavi')) wp_corenavi(); ?>
				<?php $wp_query = null;
				$wp_query = $temp;
				wp_reset_query(); ?>
				
				<!-- <div class="clear" style="height:50px;"></div>-->
				
		</div><!-- END GRID11 -->



<div class="clear" style="height:10px;"></div>