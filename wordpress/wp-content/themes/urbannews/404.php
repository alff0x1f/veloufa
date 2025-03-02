<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */
global $data;
get_header();
?>

<div class="container" id="single">

	<div class="grid11 col">
	
		<?php if($data['404_img'] !='') { ?>
		
		<img src="<?php echo $data['404_img']; ?>">
		
		<?php } ?>

		<h1><?php echo $data['404_header'] ?></h1>
		
		<p><?php echo $data['404_text'] ?></p>
		

	</div>

	<?php get_sidebar(); ?>

	</div>
	
</div>

<?php get_footer(); ?>