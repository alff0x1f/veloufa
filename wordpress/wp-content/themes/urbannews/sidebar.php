<?php global $data; ?>

<div id="section-five" class="grid4 col">

	<div class="section-inner">
		<?php if($data['disable_search'] != 'disable') { ?>
	<!-- Поиск -->
		<form id="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
			<input value="<?php _e('Введите слово для поиска…', 'siiimple'); ?>" onfocus="if(this.value=='Введите слово для поиска…'){this.value='';}" onblur="if(this.value=='')	{this.value='Введите слово для поиска…';}" name="s" type="text" id="s" maxlength="99" />
		</form>
		<?php } ?>
	<!-- Логин -->
		<?php
		if ( is_user_logged_in() ) {

		} else {
		    //echo '<a href="http://www.veloufa.ru/" style="float: left;margin-bottom: 10px;margin-right: 15px;"><img src="http://www.veloufa.ru/wp-content/uploads/2012/12/social-connect.png" alt="Войти на сайт" style="margin-right: 15px;"></a>';
		}
		?>	
		<?php if($data['disable_social'] != 'disable') { ?>

		<ul class="social-media-sidebar">
		<?php if($data['disable_facebook_sidebar'] != 'disable') { ?>
		<li><a href="<?php echo $data['facebook_link']; ?>/" title="Facebook Link" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/framework/images/facebook.png" alt="Facebook"></a>
 	   	</li>
    	
    	<?php } ?>
		
		<li><a href="<?php echo $data['twitter_link']; ?>/" title="Twitter Link" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/framework/images/twitter.png" alt="Twitter"></a></li>
		<li><a href="<?php echo $data['rss_link']; ?>/" title="RSS Link" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/framework/images/rss.png" alt="RSS"></a></li>
		</ul>
		
		<?php } ?>
		

		


<div class="clearfix"></div>



		<div class="clear"></div>

		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 01') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 02') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 03') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 04') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 05') ) : ?><?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 06') ) : ?><?php endif; ?>
			
	</div><!-- SECTION INNER -->
		
</div><!-- END FIVE -->










		


