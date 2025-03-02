<?php
/**
 * Advanced Widget Pack - Tabbed Posts
 */



class Advanced_Widget_Pack_Widget_TabbedPosts extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_tabbedposts';
	
	/* The plugins current version number */
	const VERSION = '1.3';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Display the tabbed interface displaying popular posts, latest posts, comments and tags.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Tabbed Posts', self::SLUG), $widgetOptions, $controlOptions);
		
		$this->advanced_widget_pack = Advanced_Widget_Pack::get_instance();
		
	}

	/**
	 * Show the Widgets settings form
	 *
	 * @param Array $instance
	 */
	public function form($instance) {
		
		/* Set up some default widget settings. */      
		$defaults = array(
			'widgetwidth' 	=> 250,
			'width_by'		=> 'px',
			'showpopular' 	=> true, 
			'pop_thumbnail' => true,
			'showrecent' 	=> true, 
			'rec_thumbnail' => true,
			'showcomments' 	=> true, 
			'showtags' 		=> true, 
			'numpopular' 	=> 5, 
			'numrecent' 	=> 5, 
			'numcomments' 	=> 5, 
			'numtags' 		=> 20, 
			'ordertags' 	=> 'name'
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Tabbed Posts Settings -->
            <fieldset class="widefat advancedwidgetpack-general" style="margin-bottom:8px; overflow:hidden; padding:5px 10px 0 10px; width:200px">
                
                <legend><?php _e('Tabbed Posts Settings', self::SLUG); ?></legend>
                <p>
                    <!-- The width of the widget -->
                    <label for="<?php echo $this->get_field_id('widgetwidth'); ?>" title="<?php _e('You can set the actual width of the widget in px (e.g 250)', self::SLUG); ?>"><?php _e('Widget Width:', self::SLUG); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('widgetwidth'); ?>" name="<?php echo $this->get_field_name('widgetwidth'); ?>" value="<?php echo esc_attr($widgetwidth); ?>" style="width:50px;" />
                    <select name="<?php echo $this->get_field_name('width_by'); ?>" id="<?php echo $this->get_field_id('width_by'); ?>" style="width:50px;">
                        <option value="px" <?php if(esc_attr($width_by) == "px"){ echo "selected='selected'";} ?>><?php _e('px', self::SLUG); ?></option>
                        <option value="%" <?php if(esc_attr($width_by) == "%"){ echo "selected='selected'";} ?>><?php _e('%', self::SLUG); ?></option>
                    </select>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Optimum size should be mimimum 250px or 100%', self::SLUG); ?></span>
                </p>
             </fieldset>
             
             <fieldset class="widefat advancedwidgetpack-general" style="margin-bottom:8px; overflow:hidden; padding:5px 10px 0 10px; width:200px">   
                <legend><?php _e('Popular Posts', self::SLUG); ?></legend>
                <!-- Display popular -->
                <p>
                    <input class="checkbox" type="checkbox" <?php if($showpopular) { ?> checked="checked"<?php } ?> id="<?php echo $this->get_field_id('showpopular'); ?>" name="<?php echo $this->get_field_name('showpopular'); ?>" />
                    <?php _e('Display', self::SLUG); ?> 
                    <input id="<?php echo $this->get_field_id('numpopular'); ?>" name="<?php echo $this->get_field_name('numpopular'); ?>" value="<?php echo $numpopular; ?>" style="width:30px" />
                    <?php _e('popular posts', self::SLUG); ?>
                </p>
                <!-- Thumbnail -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('pop_thumbnail'); ?>" name="<?php echo $this->get_field_name('pop_thumbnail'); ?>" <?php if($pop_thumbnail) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('pop_thumbnail'); ?>" title="<?php _e('Display thumbnail images for popular posts', self::SLUG); ?>"><?php _e('Display thumbnail images for popular posts', self::SLUG); ?></label>
                </p>
        
            </fieldset>
            
            <fieldset class="widefat advancedwidgetpack-general" style="margin-bottom:8px; overflow:hidden; padding:5px 10px 0 10px; width:200px">
        	<legend style="font-weight:bold; padding:0 1px;"><?php _e('Recent Posts', self::SLUG); ?></legend>
                <!-- Display recent -->
                <p>
                    <input class="checkbox" type="checkbox" <?php if($showrecent) { ?> checked="checked"<?php } ?> id="<?php echo $this->get_field_id('showrecent'); ?>" name="<?php echo $this->get_field_name('showrecent'); ?>" />
                    <?php _e('Display', self::SLUG); ?>  
                    <input id="<?php echo $this->get_field_id('numrecent'); ?>" name="<?php echo $this->get_field_name('numrecent'); ?>" value="<?php echo $numrecent; ?>" style="width:30px" />
                    <?php _e('recent posts', self::SLUG); ?> 
                </p>
                <!-- Thumbnail -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('rec_thumbnail'); ?>" name="<?php echo $this->get_field_name('rec_thumbnail'); ?>" <?php if($rec_thumbnail) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('rec_thumbnail'); ?>" title="<?php _e('Display thumbnail images for recent posts', self::SLUG); ?>"><?php _e('Display thumbnail images for recent posts', self::SLUG); ?></label>
                </p>
        	</fieldset>
            
            <fieldset class="widefat advancedwidgetpack-general" style="margin-bottom:8px; overflow:hidden; padding:5px 10px 0 10px; width:200px">
                <legend style="font-weight:bold; padding:0 1px;"><?php _e('Comments', self::SLUG); ?></legend>
                    <!-- Display comments -->
                    <p>
                        <input class="checkbox" type="checkbox" <?php if($showcomments) { ?> checked="checked"<?php } ?> id="<?php echo $this->get_field_id('showcomments'); ?>" name="<?php echo $this->get_field_name('showcomments'); ?>" />
                        <?php _e('Display', self::SLUG); ?> 
                        <input id="<?php echo $this->get_field_id('numcomments'); ?>" name="<?php echo $this->get_field_name('numcomments'); ?>" value="<?php echo $numcomments; ?>" style="width:30px" />
                        <?php _e('comments', self::SLUG); ?> 
                    </p>
            </fieldset>
            
            <fieldset class="widefat advancedwidgetpack-general" style="margin-bottom:8px; overflow:hidden; padding:5px 10px 0 10px; width:200px">
                <legend style="font-weight:bold; padding:0 1px;"><?php _e('Tags', 'bizstream'); ?></legend>
                    <!-- Display tags -->
                    <p>
                        <input class="checkbox" type="checkbox" <?php if($showtags) { ?> checked="checked"<?php } ?> id="<?php echo $this->get_field_id('showtags'); ?>" name="<?php echo $this->get_field_name('showtags'); ?>" />
                        <?php _e('Display', self::SLUG); ?> 
                        <input id="<?php echo $this->get_field_id('numtags'); ?>" name="<?php echo $this->get_field_name('numtags'); ?>" value="<?php echo $numtags; ?>" style="width:30px" />
                        <?php _e('tags', self::SLUG); ?> 
                    </p>
                    <!-- Tag sorting -->
                    <p>
                        <input class="radio" type="radio" <?php if($ordertags=='name') { ?>checked="checked" <?php } ?>name="<?php echo $this->get_field_name('ordertags'); ?>" value="name" />
                        <?php _e('Order tags by name', self::SLUG); ?> <br />
                        <input class="radio" type="radio" <?php if($ordertags=='count') { ?>checked="checked" <?php } ?>name="<?php echo $this->get_field_name('ordertags'); ?>" value="count" />
                        <?php _e('Order tags by post count', self::SLUG); ?> 
                    </p>
            </fieldset>
        
        </div>
        <?php
	}
	
	/**
	 * Update Widget settings and refresh data for this Widget
	 *
	 * @param Array $newInstance
	 * @param Array $oldInstance
	 * @return Array
	 */
	public function update ($newInstance, $old_instance) {
		
		$instance = $old_instance;
				
		/* Update widget settings */
		$instance = $old_instance;
		$instance['widgetwidth'] = strip_tags($newInstance['widgetwidth']);
		$instance['width_by'] = strip_tags($newInstance['width_by']);
		$instance['showpopular'] = isset($newInstance['showpopular']);
		$instance['pop_thumbnail'] = isset($newInstance['pop_thumbnail']);
		$instance['showrecent'] = isset($newInstance['showrecent']);
		$instance['rec_thumbnail'] = isset($newInstance['rec_thumbnail']);
		$instance['showcomments'] = isset($newInstance['showcomments']);
		$instance['showtags'] = isset($newInstance['showtags']);
		$instance['numpopular'] = strip_tags($newInstance['numpopular']);
		$instance['numrecent'] = strip_tags($newInstance['numrecent']);
		$instance['numcomments'] = strip_tags($newInstance['numcomments']);
		$instance['numtags'] = strip_tags($newInstance['numtags']);
		$instance['ordertags'] = strip_tags($newInstance['ordertags']);
		
		return $instance;
		
	}
	
	/**
	 * Display the actual Widget
	 *
	 * @param Array $args
	 * @param Array $instance
	 */
	public function widget($args, $instance){
		
		global $comments, $comment;
		extract($args);
		
		/* User-selected settings. */
		$widgetwidth = $instance['widgetwidth'];
		$width_by = $instance['width_by'];
		$showpopular = $instance['showpopular'];
		$pop_thumbnail = $instance['pop_thumbnail'];
		$showrecent = $instance['showrecent'];
		$rec_thumbnail = $instance['rec_thumbnail'];
		$showcomments = $instance['showcomments'];
		$showtags = $instance['showtags'];
		$numpopular = $instance['numpopular'];
		$numrecent = $instance['numrecent'];
		$numcomments = $instance['numcomments'];
		$numtags = $instance['numtags'];
		$ordertags = $instance['ordertags'];
		
		/* Before Widget HTML */
		echo $before_widget;
		
		?>
		<!-- Advanced Widget Pack: Tabbed Posts Widget - http://www.wpinsite.com -->
        <div id="awp_tabs" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
            <ul class="awp_tabnav">
				<?php if($showpopular) { ?><li><a href="#awp-tabs-popular"><?php _e('Popular',self::SLUG); ?></a></li><?php } ?>
                <?php if($showrecent) { ?><li><a href="#awp-tabs-recent"><?php _e('Recent',self::SLUG); ?></a></li><?php } ?>
                <?php if($showcomments) { ?><li><a href="#awp-tabs-comments"><?php _e('Comments',self::SLUG); ?></a></li><?php } ?>
                <?php if($showtags) { ?><li><a href="#awp-tabs-tags"><?php _e('Tags',self::SLUG); ?></a></li><?php } ?>
            </ul>
            
            <div class="awp_tabs_body">
        		<div class="inside">
        		<?php if($showpopular) { 
				
				$poploop = new WP_Query('order=DESC&orderby=comment_count&posts_per_page='.$numpopular);
				?>
                    
                    <div id="awp-tabs-popular">
                    	<?php if($poploop->have_posts()){ ?>
                            <ul>
                                <?php
                                while($poploop->have_posts()) : $poploop->the_post();
                                
                                    $post_date = get_the_date('j M Y');
                                    $post_time = get_the_time('g:i a');
									$post_title = get_the_title();
									
									echo '<li>';
										if($pop_thumbnail){
											echo $this->advanced_widget_pack->featured_image_thumb(50);
										}
										echo '<div class="awp_info">';
										echo '<div><a title="'.get_the_title().'" href="'.get_permalink().'">'.$post_title.'</a></div>';
										echo '<div class="awp_meta">'.comments_number('0 Comments', '1 Comment', '% Comments' ).'</div>';
										echo '</div>'; 
									echo '</li>'."\n";
                                    
                                endwhile;
                                ?>
                            </ul>
                            <?php		
                            wp_reset_postdata();
                        } else {
                            echo '<li>'.__('No posts available', self::SLUG).'</li>'."\n";
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    
                <?php } ?>
                
                <?php if($showrecent) { 
				
				$recloop = new WP_Query('order=DESC&orderby=date&posts_per_page='.$numrecent);
				?>
                    
                     <div id="awp-tabs-recent">
                    	<?php if($recloop->have_posts()){ ?>
                            <ul>
                                <?php
                                while($recloop->have_posts()) : $recloop->the_post();
                                
                                    $post_date = get_the_date('j M Y');
									$post_time = get_the_time('g:i a');
									$post_title = get_the_title();
									
									echo '<li>';
										if($pop_thumbnail){
											echo $this->advanced_widget_pack->featured_image_thumb(50);
										}
										echo '<div class="awp_info">';
										echo '<div><a title="'.get_the_title().'" href="'.get_permalink().'">'.$post_title.'</a></div>';
										echo '<div class="awp_meta">'.comments_number('0 Comments', '1 Comment', '% Comments' ).'</div>';
										echo '</div>'; 
									echo '</li>'."\n";
                                   
                                endwhile;
                                ?>
                            </ul>
                            <?php		
                            wp_reset_postdata();
                        } else {
                            echo '<li>'.__('No posts available', self::SLUG).'</li>'."\n";
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    
                <?php } ?>
                
                <?php if($showcomments) { ?>
                    
                    <div id="awp-tabs-comments">
                        <ul>
						<?php //get recent comments
							$com_excerpt = '';
							$out = '';
							
							$args = array(
								'status' => 'approve',
								'number' => $numcomments
							);	
							$comments = get_comments($args);
						
							if($comments){
								foreach($comments as $comment){	
									$commentcontent = strip_tags($comment->comment_content);
									if(strlen($commentcontent) > 110) {
										$com_excerpt = Advanced_Widget_Pack::snippet_text($commentcontent, 50);
									}
									$link = get_permalink($comment->comment_post_ID);
									
									$out .= '<li>';
										$out .= get_avatar($comment, 45);
										$out .= '<div class="awp_info">';
										$out .= '<a href="'.$link.'">';
										$out .= '<i>'.strip_tags($comment->comment_author).'</i>: '.strip_tags($com_excerpt).'...';
										$out .= '</a>';
										$out .= '<div class="awp_meta">'.get_comment_date('j M Y',$comment->comment_ID).' '.__('at', self::SLUG).' '.get_comment_date('g:i a',$comment->comment_ID).'</div>';
										$out .= '</div>';
									$out .= '</li>';
					
								}
							} else {
								$out .= '<li>'.__('No comments available', self::SLUG).'</li>'."\n";
							}
							
							echo $out;
                            ?>
                            
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    
                <?php } ?>
                
                <?php if($showtags) { ?>
                
                    <div id="awp-tabs-tags">
                        <?php wp_tag_cloud('smallest=8&largest=22&orderby=count&order=DESC&number='.$numtags); ?>
                        <div class="clearfix"></div>
                    </div>
                
                <?php } ?>
            </div>
            </div>
        </div>
        
        <!-- End Advanced Widget Pack: Tabbed Posts Widget -->
        <?php 
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>