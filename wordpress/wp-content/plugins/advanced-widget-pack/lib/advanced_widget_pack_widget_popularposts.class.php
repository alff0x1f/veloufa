<?php
/**
 * Advanced Widget Pack - Popular Posts
 */

class Advanced_Widget_Pack_Widget_PopularPosts extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_popular_posts';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays a custom list of popular posts including thumbnail images.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Popular Posts', self::SLUG), $widgetOptions, $controlOptions);
		
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
			'widgetwidth'	=> '',
			'width_by'		=> 'px',
			'title' 		=> 'Popular Posts',
			'post_type'		=> 'post',
			'cat' 			=> '',
			'count' 		=> 5,
			'thumbnail' 	=> true,
			'chars' 		=> '35'
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
			
		$categories_list = get_categories('hide_empty=0');		
		$categories = array();
		$categories[''] = 'All';
		foreach ($categories_list as $category) {
			$categories[$category->cat_ID] = $category->cat_name;
		}
		?>
            <div class="advancedwidgetpack-options">

            <!-- Popular Posts Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Popular Posts Settings', self::SLUG); ?></legend>
                <!-- The width of the widget -->
                <p>
                    <label for="<?php echo $this->get_field_id('widgetwidth'); ?>" title="<?php _e('You can set the actual width of the widget in px (e.g 250)', self::SLUG); ?>"><?php _e('Widget Width:', self::SLUG); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('widgetwidth'); ?>" name="<?php echo $this->get_field_name('widgetwidth'); ?>" value="<?php echo  esc_attr($widgetwidth); ?>" style="width:50px;" />
                    <select name="<?php echo $this->get_field_name('width_by'); ?>" id="<?php echo $this->get_field_id('width_by'); ?>" style="width:50px;">
                        <option value="px" <?php if(esc_attr($width_by) == "px"){ echo "selected='selected'";} ?>><?php _e('px', self::SLUG); ?></option>
                        <option value="%" <?php if(esc_attr($width_by) == "%"){ echo "selected='selected'";} ?>><?php _e('%', self::SLUG); ?></option>
                    </select>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Optimum size should be between 230px - 260px or 100%', self::SLUG); ?></span>
                </p>
                <!-- Title -->
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </p>
                <!-- Thumbnail -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" <?php if($thumbnail) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('thumbnail'); ?>" title="<?php _e('Display thumbnail images', self::SLUG); ?>"><?php _e('Display thumbnail images', self::SLUG); ?></label>
                </p>
                <!-- Post Type -->
                <p>
                    <label for="<?php echo $this->get_field_id('post_type'); ?>" title="<?php _e('Select the post type to display', self::SLUG); ?>"><?php _e('Post type:', self::SLUG); ?></label>
                    <select name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_id('post_type'); ?>">
                        <?php
						$post_types = get_post_types('','names'); 
						foreach($post_types as $cust_post_type ) {
						  $selected = ($post_type == $cust_post_type) ? ' selected="selected"' : '';
						  echo '<option value="'.$cust_post_type.'" '. $selected .'>'. $cust_post_type. '</option>';
						}
                        ?>
                    </select>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Select the custom post type to display the listings for', self::SLUG); ?></span>
                </p>
                <!-- Categories -->
                <p>
                    <label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Choose the categories:', self::SLUG); ?></label>
                    <select class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>">
                        <?php
                        foreach($categories as $id => $name){					
                            $selected = ($cat == $id) ? ' selected="selected"' : '';
                            echo '<option value="'.$id.'" '. $selected .'>'.$name.'</option>';
                        }
                        ?>
                    </select>
                </p>
                <!-- Items -->
                <p>
                    <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Posts to display:', self::SLUG); ?></label>
                    <select id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>">
                        <?php
                        for($i = 1; $i <= 10; $i++){					
                            $selected = ($count == $i) ? ' selected' : '';
                            echo '<option value="'.$i.'"'. $selected .'>'.$i.'</option>';
                        }
                        ?>
                    </select>
                </p>
                <!-- Amount characters -->
                <p>
                    <label for="<?php echo $this->get_field_id('chars'); ?>" title="<?php _e('You can set the amount of characters to display before trunicating entry (e.g 35)', self::SLUG); ?>"><?php _e('Amount of characters to display before trunicating entry:', self::SLUG); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('chars'); ?>" name="<?php echo $this->get_field_name('chars'); ?>" value="<?php echo $chars; ?>" style="width:50px;" />
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Optimum size should be 35 characters', self::SLUG); ?></span>
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
		$instance['title'] = strip_tags($newInstance['title']);
		$instance['post_type'] = strip_tags($newInstance['post_type']);
		$instance['cat'] = strip_tags($newInstance['cat']);
		$instance['count'] = strip_tags($newInstance['count']);
		$instance['thumbnail'] = strip_tags($newInstance['thumbnail']);
		$instance['chars'] = ((is_numeric($newInstance['chars'])) ? trim($newInstance['chars']) : 35);
			
		return $instance;
		
	}
	
	/**
	 * Display the actual Widget
	 *
	 * @param Array $args
	 * @param Array $instance
	 */
	public function widget($args, $instance){
		
		extract($args);
		
		/* User-selected settings. */
		$widgetwidth = $instance['widgetwidth'];
		$width_by = $instance['width_by'];
		$title = apply_filters('widget_title', $instance['title']);
		$post_type = $instance['post_type'];
		$cat = $instance['cat'];
		$count = empty($instance['count']) ? '' : apply_filters('widget_title', $instance['count']);
		$thumbnail = $instance['thumbnail'];
		$chars = $instance['chars'];
		
		if(!empty($count)){
			
			/* Before Widget HTML */
			echo $before_widget;
			
			/* Title of widget */
			if($title) echo $before_title.$title.$after_title;	
				
				?>
				<!-- Advanced Widget Pack: Popular Posts Widget - http://www.wpinsite.com -->
				<div class="textwidget">
					<div class="advancedwidgetpack" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
						<?php 
						$this->advanced_widget_pack->awp_get_posts('popular', $cat, $post_type, $count, $chars, $thumbnail == true ? true : false);
						?>
						<div class="clear"></div>
					</div>
				</div>
				<!-- End Advanced Widget Pack: Popular Posts Widget -->
				<?php
				
			/* After Widget HTML */
			echo $after_widget;
		
		}
	}
}

?>