<?php global $data; ?>

<?php if($data['disable_share'] !='disable') { ?> 

<ul class="share-area">

	<li class="first"><?php echo $data['share_text'] ?></li>
	

			
	<?php if($data['disable_share_facebook'] !='disable') { ?> 
	<!-- FACEBOOK -->
	<li><iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink() ?>&amp;layout=button_count&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:20px;" allowTransparency="true"></iframe></li>
	<?php } ?>
		
	<?php if($data['disable_share_linkedin'] !='disable') { ?> 
	<!-- LINKEDIN -->		
	<li><script type="in/share" data-url="<?php the_permalink(); ?>" data-counter="right"></script></li>
	<?php } ?>
			
	<?php if($data['disable_share_twitter'] !='disable') { ?> 
	<!-- TWITTER -->	
	<li><a href="http://twitter.com/share?url=<?php echo urlencode(get_permalink($post->ID)); ?>&via=veloufa&count=horizontal" class="twitter-share-button">Tweet</a></li>
	<?php } ?>
		
	<?php if($data['disable_share_digg'] !='disable') { ?> 		
	<!-- DIGG -->
	<li><a class="DiggThisButton DiggCompact" href="http://digg.com/submit?url=<?php the_permalink(); ?>"></a></li>
	<?php } ?>
	
	<?php if($data['disable_share_comments'] !='disable') { ?>
	<li class="comments" style="float:right;"><span class="comments"><?php comments_popup_link(__('0 Comments', 'siiimple'), __('1 Comment', 'siiimple'), __('% Comments', 'siiimple')); ?></span></li>
	<?php } ?>
	

	

	
	
	
	

			
</ul><!-- END SHARE AREA -->

<?php } ?>