<?php
/**
 * Advanced Widget Pack - Advertisements 125px
 */

class Advanced_Widget_Pack_Widget_Advertisements extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_advertisements';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Allows you to display unlimited 125 x 125px advertisements.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'width' 	=> 500, 
			'height' 	=> 350,
			'id_base' 	=> self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Advertisements', self::SLUG), $widgetOptions, $controlOptions);
		
		$this->advanced_widget_pack = Advanced_Widget_Pack::get_instance();
		
	}

	/**
	 * Show the Widgets settings form
	 *
	 * @param Array $instance
	 */
	public function form($instance) {
		
		$default_ads = "http://www.wpinsite.com|".WP_PLUGIN_URL."/advanced-widget-pack/images/sample-125x125.jpg|Ad 1|nofollow\n".
					   "http://www.wpinsite.com|".WP_PLUGIN_URL."/advanced-widget-pack/images/sample-125x125.jpg|Ad 2|follow\n".
					   "http://www.wpinsite.com|".WP_PLUGIN_URL."/advanced-widget-pack/images/sample-125x125.jpg|Ad 3|nofollow\n".
					   "http://www.wpinsite.com|".WP_PLUGIN_URL."/advanced-widget-pack/images/sample-125x125.jpg|Ad 4|follow";
		
		/* Set up some default widget settings. */      
		$defaults = array(
			'widgetwidth'	=> '',
			'width_by'		=> 'px',
			'title' 		=> 'Advertisements',
			'ads' 			=> $default_ads,
			'newin' 		=> true
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Advertisements Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Advertisements Settings', self::SLUG); ?></legend>
                <p>
                    <!-- The width of the widget -->
                    <label for="<?php echo $this->get_field_id('widgetwidth'); ?>" title="<?php _e('You can set the actual width of the widget in px (e.g 250)', self::SLUG); ?>"><?php _e('Widget Width:', self::SLUG); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('widgetwidth'); ?>" name="<?php echo $this->get_field_name('widgetwidth'); ?>" value="<?php echo  esc_attr($widgetwidth); ?>" style="width:50px;" />
                    <select name="<?php echo $this->get_field_name('width_by'); ?>" id="<?php echo $this->get_field_id('width_by'); ?>" style="width:50px;">
                        <option value="px" <?php if(esc_attr($width_by) == "px"){ echo "selected='selected'";} ?>><?php _e('px', self::SLUG); ?></option>
                        <option value="%" <?php if(esc_attr($width_by) == "%"){ echo "selected='selected'";} ?>><?php _e('%', self::SLUG); ?></option>
                    </select>
                	<span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Mimimum size required is 260px or 100%', self::SLUG); ?></span>
                
                </p>
                <!-- Title -->
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', self::SLUG); ?> </label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
                </p>
                <!-- Advertisements -->
                <p>
                    <label for="<?php echo $this->get_field_id('ads'); ?>"><?php _e('Ads:', self::SLUG); ?></label>
                    <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('ads'); ?>"><?php echo $ads; ?></textarea>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Enter one ad entry per line in the following format:<br /> <code>URL|Image URL|Image Alt Text|rel</code><br /><strong>Note:</strong> You must hit your &quot;enter/return&quot; key after each ad entry.',self::SLUG); ?></span>
                </p>
                <!-- New Window -->
                <p>
                    <input type="checkbox" <?php if($newin){ ?> checked="checked"<?php } ?> id="<?php echo $this->get_field_id('newin'); ?>" name="<?php echo $this->get_field_name('newin'); ?>" />
                    <label for="<?php echo $this->get_field_id('newin'); ?>"><?php _e('Open ad links in a new window?', self::SLUG); ?></label>
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
		$instance['ads'] = strip_tags( $newInstance['ads'] );
		$instance['newin'] = $newInstance['newin'];
		
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
		$newin = $instance['newin'];

		
		// Separate the ad line items into an array
		$ads = explode("\n", $instance['ads']);

		if(sizeof($ads)>0) :
					
			/* Before Widget HTML */
			echo $before_widget;
			
			/* Title of widget */
			if($title) echo $before_title.$title.$after_title;	
				
				?>
				<!-- Advanced Widget Pack: Advertisement Widget - http://www.wpinsite.com -->
				<div class="textwidget">
                	<div <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
					<?php
					if(isset($instance['ads'])){
						if($newin) $newin = 'target="_blank"';
						?>
							<ul class="ads">
							<?php
							$alt = 1;
							foreach($ads as $ad){
								if($ad && strstr($ad, '|')) {
									$alt = $alt*-1;
									$this_ad = explode('|', $ad);
									echo '<li class="';
									if($alt==1) echo 'alt';
									echo '"><a href="'.$this_ad[0].'" '.$newin.'><img src="'.$this_ad[1].'" width="125" height="125" alt="'.$this_ad[2].'" /></a></li>';
								}
							}
							?>
							</ul>
					<?php } ?>
                    </div>
				</div>
				<!-- End Advanced Widget Pack: Advertisement Widget -->
				<?php 
			
			/* After Widget HTML */
			echo $after_widget;
		
		endif;
	}
}
?>