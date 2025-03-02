<?php
/**
 * Advanced Widget Pack - Contact Info
 */

class Advanced_Widget_Pack_Widget_Contactinfo extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_contact_info';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays the site contact info in a widget', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Display Contact Info', self::SLUG), $widgetOptions, $controlOptions);
		
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
		'title' 		=> 'Contact Details',
		'address' 		=> '',
		'phone' 		=> '',
		'fax' 			=> '',
		'email' 		=> '',
		'website' 		=> '',
		'icons' 		=> true
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Contact Info Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Contact Info Settings', self::SLUG); ?></legend>
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
                <!-- Address -->
                <p>
                    <label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address:', self::SLUG); ?></label>
                    <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>"><?php echo esc_attr($address); ?></textarea>
                    <br /><small><?php _e('Do not use HTML. Hit enter for new line', self::SLUG); ?></small>
                </p>
                <!-- Phone -->
                <p>
                    <label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo esc_attr($phone); ?>" />
                </p>
                <!-- Fax -->
                <p>
                    <label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo esc_attr($fax); ?>" />
                </p>
                <!-- Email -->
                <p>
                    <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" />
                </p>
                <!-- Website -->
                <p>
                    <label for="<?php echo $this->get_field_id('website'); ?>"><?php _e('Website URL:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('website'); ?>" name="<?php echo $this->get_field_name('website'); ?>" type="text" value="<?php echo esc_attr($website); ?>" />
                    <br /><small><?php _e('Enter the full URL. EG. http://www.wpinsite.com', self::SLUG); ?></small>
                </p>
                <!-- Icons -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('icons'); ?>" name="<?php echo $this->get_field_name('icons'); ?>" <?php if ($icons) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('icons'); ?>" title="<?php _e('Display icons next to address details', self::SLUG); ?>"><?php _e('Display icons next to address details', self::SLUG); ?></label>
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
		$instance['address'] = strip_tags($newInstance['address']);
		$instance['phone'] = strip_tags($newInstance['phone']);
		$instance['fax'] = strip_tags($newInstance['fax']);
		$instance['email'] = strip_tags($newInstance['email']);
		$instance['website'] = strip_tags($newInstance['website']);
		$instance['icons'] = strip_tags($newInstance['icons']);
			
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
		$address = $instance['address'];
		$phone = $instance['phone'];
		$fax = $instance['fax'];
		$email = $instance['email'];
		$website = $instance['website'];
		$icons = $instance['icons'];
					
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			?>	
			<!-- Advanced Widget Pack: Contact Info Widget - http://www.wpinsite.com -->
			<div class="textwidget">
				<div class="advancedwidgetpack awp_contact_info" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
					<?php if($address){ ?>
					<span <?php if($icons){?>class="address"<?php } ?>><?php echo nl2br($address); ?></span>
					<?php } ?>
					<?php if($phone){ ?>
					<span <?php if($icons){?>class="phone"<?php } ?>>Ph: <?php echo $phone; ?></span>
					<?php } ?>
					<?php if($fax){ ?>
					<span <?php if($icons){?>class="fax"<?php } ?>>Fax: <?php echo $fax; ?></span>
					<?php } ?>
					<?php if($email){ ?>
					<span <?php if($icons){?>class="mail"<?php } ?>><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span>
					<?php } ?>
					<?php if($website){ ?>
					<span <?php if($icons){?>class="website"<?php } ?>><a href="<?php echo $website; ?>"><?php echo $website; ?></a></span>
					<?php } ?>
					<div class="clear"></div>
				</div>
			</div>
			<!-- End Advanced Widget Pack: Contact Info Widget -->
			<?php
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>