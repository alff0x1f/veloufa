<?php
/**
 * Advanced Widget Pack - Feedburner signup
 */

class Advanced_Widget_Pack_Widget_Feedburner extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_feedburner';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Display the textbox allowing visitors to subscribe to your Feedburner feed via email.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Feedburner Subscribe', self::SLUG), $widgetOptions, $controlOptions);
		
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
			'title' 		=> 'Subscribe via Email',
			'feedburner' 	=> ''
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Feedburner Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Feedburner Settings', self::SLUG); ?></legend>
                <!-- The width of the widget -->
                <p>
                    <label for="<?php echo $this->get_field_id('widgetwidth'); ?>" title="<?php _e('You can set the actual width of the widget in px (e.g 250)', self::SLUG); ?>"><?php _e('Widget Width:', self::SLUG); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('widgetwidth'); ?>" name="<?php echo $this->get_field_name('widgetwidth'); ?>" value="<?php echo esc_attr($widgetwidth); ?>" style="width:50px;" />
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
                <!-- Feedburner ID -->
                <p>
                    <label for="<?php echo $this->get_field_id('feedburner'); ?>"><?php _e('Feedburner ID:', self::SLUG); ?></label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('feedburner'); ?>" name="<?php echo $this->get_field_name('feedburner'); ?>" value="<?php echo esc_attr($feedburner); ?>" />
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
		$instance['feedburner'] = strip_tags($newInstance['feedburner']);
			
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
		$title = apply_filters('widget_title', $instance['title'] );
		$feedburner = $instance['feedburner'];
			
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			?>	
			<!-- Advanced Widget Pack: Feedburner subscribe - http://www.wpinsite.com -->
			<div class="textwidget">
				<div class="advancedwidgetpack" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
					<div class="awp_subscribe">
                    <form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedburner; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
                        <div class="feedburner"><?php _e('Enter your email address to subscribe', self::SLUG); ?></div><br/>
                        <input type="text" name="email" class="subtxt" />
                        <input type="hidden" value="<?php echo $feedburner; ?>" name="uri"/>
                        <input type="hidden" name="loc" value="en_US"/>
                        
                        <button type="submit" class="btn" id="newsletter-button" title="<?php _e('You will receive a daily email with new content from our website.', self::SLUG); ?>"><?php _e('Subscribe', self::SLUG); ?></button>
                    </form> 
                    <div class="clear" /></div>
			</div>
				</div>
			</div>
			<!-- End Advanced Widget Pack: Feedburner subscribe -->
			<?php
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>