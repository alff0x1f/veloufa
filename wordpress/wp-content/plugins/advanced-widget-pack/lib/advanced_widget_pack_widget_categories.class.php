<?php
/**
 * Advanced Widget Pack - Categories
 */



class Advanced_Widget_Pack_Widget_Categories extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_categories';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays a list of your site\'s categories.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Category List', self::SLUG), $widgetOptions, $controlOptions);
		
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
			'title' 		=> 'Categories',
			'format'		=> 'list',
			'count'			=> true,
			'icon'			=> true,
			'display_empty' => true
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Category Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Category Settings', self::SLUG); ?></legend>
                <p>
                    <!-- The width of the widget -->
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
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', self::SLUG); ?> </label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
                </p>
                <!-- Format -->
                <p>
                    <label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format:', self::SLUG); ?> </label>
                    <select class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>">
                        <option value="list" <?php if(esc_attr($format) == "list"){ echo "selected='selected'";} ?>><?php _e('List', self::SLUG); ?></option>
                        <option value="dropdown" <?php if(esc_attr($format) == "dropdown"){ echo "selected='selected'";} ?>><?php _e('Dropdown', self::SLUG); ?></option>
                    </select>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Select the format you would like the categories displayed', self::SLUG); ?></span>
                </p>
                <!-- Post count -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php if($count) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('count'); ?>" title="<?php _e('Display post count next to each category name', self::SLUG); ?>"><?php _e('Display post count next to each category name', self::SLUG); ?></label>
                </p>
                <!-- Icon -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" <?php if($icon) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('icon'); ?>" title="<?php _e('Display an image icon next to each category name', self::SLUG); ?>"><?php _e('Display an image icon next to each category name', self::SLUG); ?></label>
                	<span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('If you selected the list form you can display an icon next to each category name.', self::SLUG); ?></span>
                </p>
                <!-- Hide Empty -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('display_empty'); ?>" name="<?php echo $this->get_field_name('display_empty'); ?>" <?php if($display_empty) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('display_empty'); ?>" title="<?php _e('Display empty categories', self::SLUG); ?>"><?php _e('Display empty categories', self::SLUG); ?></label>
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
		$instance['format'] = strip_tags($newInstance['format']);
		$instance['count'] = $newInstance['count'];
		$instance['icon'] = $newInstance['icon'];
		$instance['display_empty'] = $newInstance['display_empty'];
		
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
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$format = $instance['format'];
		$count = $instance['count'];
		$icon = $instance['icon'];
		$display_empty = $instance['display_empty'];
		
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			
			?>
			<!-- Advanced Widget Pack: Categories Widget - http://www.wpinsite.com -->
			<div class="textwidget">
            	<div class="advancedwidgetpack" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
                	<?php if($format == 'list'){ 
                    	if($icon){ ?>
                            <ul class="categories-icon">
                            <?php
                                wp_list_categories(array('show_count' => $count, 'hide_empty' => $display_empty, 'title_li' => ''));
                            ?>
                            </ul>
                        <?php } else { ?>
                        <ul class="categories">
                        <?php
                            wp_list_categories(array('show_count' => $count, 'hide_empty' => $display_empty, 'title_li' => ''));
                        ?>
                        </ul>
                        <?php }
                    } else { ?>
                    	<div class="categories-container">
                        	<p><?php _e('Select a category from the dropdown list to view the posts for that category', self::SLUG); ?></p>
                            <?php wp_dropdown_categories(array('show_count' => $count, 'show_option_none' => 'Select Category')); ?> 
                            <script type="text/javascript">/* <![CDATA[ */
							var dropdown = document.getElementById("cat");
							function onCatChange() {
								if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
									location.href = "<?php echo get_option('home');
									?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
								}
							}
							dropdown.onchange = onCatChange;
						/* ]]> */</script>
                        </div>
                    <?php } ?>
                </div>
			</div>
			<!-- End Advanced Widget Pack: Categories Widget -->
			<?php 
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>