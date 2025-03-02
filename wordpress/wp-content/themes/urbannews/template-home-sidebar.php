<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */
global $data;
get_header(); ?>

<div class="container main">

	<div id="full-section-left" class="grid12 col">

		<?php include( trailingslashit( get_template_directory() ). '/section1.php' ); ?>

		<div id="section-four" class="grid8 col">

			<div class="section-inner-main">
			
				<!-- BEGIN SECTION 2 -->

				<?php include( trailingslashit( get_template_directory() ). '/section-featured.php' ); ?>
			
				<?php if($data['disable_section_photo'] !='disable') { ?>
				<?php include( trailingslashit( get_template_directory() ). '/section-photo.php' ); ?>
				<?php } else if($data['disable_section_photo'] == 'disable') {?>
				<div class="clear" style="height:80px;"></div>
				<?php } ?>
			
				<?php if($data['disable_section_video'] !='disable') { ?>
				<?php include( trailingslashit( get_template_directory() ). '/section-video.php' ); ?>
				<?php } ?>
		
				<?php if($data['disable_section_quote'] !='disable') { ?>
				<?php include( trailingslashit( get_template_directory() ). '/section-quote.php' ); ?>
				<?php } ?>
			
			</div><!-- END SECTION INNER MAIN-->

		</div><!-- END SECTION4 GRID8 COL -->

		<div class="grid11 col bottom-left">
		
			<div class="section-inner-main-bottom">
			
				<?php if($data['disable_section3'] !='disable') { ?>

					<?php include( trailingslashit( get_template_directory() ). '/section3.php' ); ?>
				
				<?php } ?>
			
				<?php if($data['disable_section4'] !='disable') { ?>
				
					<?php include( trailingslashit( get_template_directory() ). '/section4.php' ); ?>
				
				<?php } ?>
				
				<?php if($data['disable_section5'] !='disable') { ?>
				
					<?php include( trailingslashit( get_template_directory() ). '/section5.php' ); ?>
				
				<?php } ?>
			
			</div>
	
		</div><!-- END GRID11 BOTTOM LEFT -->

	</div><!-- END GRID 12 -->

	<?php get_sidebar(); ?>

	</div><!-- CONTAINER -->

</div><!-- END CONTAINER -->

<?php if($data['disable_section6'] !='disable') { ?>

<?php include( trailingslashit( get_template_directory() ). '/section6.php' ); ?>

<?php } ?>

<?php get_footer(); ?>