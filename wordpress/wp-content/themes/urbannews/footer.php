<?php
/**
 * @package WordPress
 * @subpackage Siiimple
 */
?>

<?php global $data; ?>

<div class="footer-base">

<div class="btt"><a href="#top"><img src="<?php echo get_template_directory_uri(); ?>/framework/images/nav-up.png" alt="back-to-top"></a></div>
	
	<div class="container" id="footer-area">
	
	<?php if($data['footer_ad'] !='') { ?>
	
		<div class="ad-footer">
	
			<a href="<?php echo $data['footer_ad_link']; ?>"><img src="<?php echo $data['footer_ad']; ?>" alt="footer-ad"></a>
		
		</div>
		
	<?php } ?>
		
		<div class="clear"></div>
	
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 01') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 02') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 03') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 03 Bottom') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 04') ) : ?><?php endif; ?>
		
	<div class="clear"></div>
		
	<div class="very-bottom">
	
		<?php if($data['footer_left'] !='') { ?>
	
			<h3><?php echo $data['footer_left']; ?></h3>
		
		<?php } ?>
	
	</div><!-- END VERY BOTTOM -->
		
	</div><!-- END FOOTER BASE -->
	
</div><!-- END FOOTER AREA -->

<?php if($data['disable_share_linkedin'] !='disable') { ?> 
<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
<?php } ?>

<?php if($data['disable_share_twitter'] !='disable') { ?> 
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<?php } ?>

<?php if($data['disable_share_digg'] !='disable') { ?> 
<script type="text/javascript">
(function() {
var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
s.type = 'text/javascript';
s.async = true;
s.src = 'http://widgets.digg.com/buttons.js';
s1.parentNode.insertBefore(s, s1);
})();
</script>
<?php } ?>

<?php echo stripslashes($data['tracking_footer']); ?>

<?php wp_footer(); ?>
</div>
</body>

</html>
