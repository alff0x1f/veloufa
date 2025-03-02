<?php
/**
 * Advanced Widget Pack - Authors
 */



class Advanced_Widget_Pack_Widget_Authors extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_authors';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays a list of your site\'s authors.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Author List', self::SLUG), $widgetOptions, $controlOptions);
		
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
			'title' 		=> 'Authors',
			'format'		=> 'list',
			'count'			=> true,
			'hide_empty'	=> false,
			'sort_by'		=> 'display_name',
			'exclude'		=> '',
			'icon'			=> true
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Authors Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Authors Settings', self::SLUG); ?></legend>
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
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Select the format you would like the authors displayed', self::SLUG); ?></span>
                </p>
                <!-- Sort By -->
                <p>
                    <label for="<?php echo $this->get_field_id('sort_by'); ?>" title="<?php _e('Sort authors by:', self::SLUG); ?>"><?php _e('Sort authors by:', self::SLUG); ?></label>
                    <select name="<?php echo $this->get_field_name('sort_by'); ?>" id="<?php echo $this->get_field_id('sort_by'); ?>" class="widefat">
                        <option value="display_name" <?php if(esc_attr($sort_by) == "display_name"){ echo "selected='selected'";} ?>><?php _e('Display name', self::SLUG); ?></option>
                        <option value="user_nicename" <?php if(esc_attr($sort_by) == "user_nicename"){ echo "selected='selected'";} ?>><?php _e('Preferred name', self::SLUG); ?></option>
                        <option value="id" <?php if(esc_attr($sort_by) == "id"){ echo "selected='selected'";} ?>><?php _e('Author ID', self::SLUG); ?></option>
                    </select>
                </p>
                <!-- Exclude -->
                <p>
                    <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude Authors:', self::SLUG); ?> </label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo esc_attr($exclude); ?>" />
                	<span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Enter list of Author IDs, separated by commas.', self::SLUG); ?></span>
                </p>
                <!-- Post count -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php if($count) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('count'); ?>" title="<?php _e('Display post count next to each author name', self::SLUG); ?>"><?php _e('Display post count next to each author name', self::SLUG); ?></label>
                </p>
                <!-- Hide Empty -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" <?php if($hide_empty) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('hide_empty'); ?>" title="<?php _e('Hide authors with no posts', self::SLUG); ?>"><?php _e('Hide authors with no posts', self::SLUG); ?></label>
                </p>
                <!-- Icon -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" <?php if($icon) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('icon'); ?>" title="<?php _e('Display an image icon next to each page name', self::SLUG); ?>"><?php _e('Display an image icon next to each author name', self::SLUG); ?></label>
                	<span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('If you selected the list form you can display an icon next to each author name.', self::SLUG); ?></span>
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
		$instance['hide_empty'] = $newInstance['hide_empty'];
		$instance['sort_by'] = $newInstance['sort_by'];
		$instance['exclude'] = $newInstance['exclude'];
		$instance['icon'] = $newInstance['icon'];
		
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
		$hide_empty = $instance['hide_empty'];
		$sort_by = $instance['sort_by'];
		$exclude = $instance['exclude'];
		$icon = $instance['icon'];
		
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			
			?>
			<!-- Advanced Widget Pack: Author Widget - http://www.wpinsite.com -->
			<div class="textwidget">
            	<div class="advancedwidgetpack" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
                	<?php if($format == 'list'){ 
                    	if($icon){ ?>
                            <ul class="author-icon">
                            <?php
                                echo wp_list_authors(array('orderby' => $sort_by, 'exclude' => $exclude, 'optioncount' => $count, 'hide_empty' => $hide_empty));
                            ?>
                            </ul>
                        <?php } else { ?>
                        <ul class="author">
                        <?php
                            wp_list_authors(array('orderby' => $sort_by, 'exclude' => $exclude, 'optioncount' => $count, 'hide_empty' => $hide_empty));
                        ?>
                        </ul>
                        <?php }
                    } else { ?>
                    	<div class="author-container">
                        	<p><?php _e('Select an author from the dropdown list to view the posts by that author', self::SLUG); ?></p>
                            <?php wp_dropdown_users(array('sort_column' => $sort_by, 'show_option_none' => 'Select Author', 'exclude' => $exclude)); ?> 
                            <script type='text/javascript'>
							/* <![CDATA[ */
								var authordropdown = document.getElementById("user");
								function onPageChange() {
									if ( authordropdown.options[authordropdown.selectedIndex].value > 0 ) {
										location.href = "<?php echo home_url(); ?>/?author="+authordropdown.options[authordropdown.selectedIndex].value;
									}
								}
								authordropdown.onchange = onPageChange;
							/* ]]> */
							</script>
                        </div>
                    <?php } ?>
                </div>
			</div>
			<!-- End Advanced Widget Pack: Author Widget -->
			<?php 
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>