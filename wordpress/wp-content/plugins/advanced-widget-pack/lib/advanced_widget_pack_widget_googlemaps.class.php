<?php
/**
 * Advanced Widget Pack - Flickr
 */

class Advanced_Widget_Pack_Widget_GoogleMaps extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_googlemaps';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays a custom Google maps widget.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Google Maps', self::SLUG), $widgetOptions, $controlOptions);
		
		$this->advanced_widget_pack = Advanced_Widget_Pack::get_instance();
		
		/* Enqueue Script */
		wp_enqueue_script(self::SLUG . '-gmapi', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'));
		wp_enqueue_script(self::SLUG . '-gm', plugins_url('advanced-widget-pack/js/jquery.gmap.min.js'), array('jquery','jquery-ui-tabs'));
	}
	
	/**
	 * Show the Widgets settings form
	 *
	 * @param Array $instance
	 */
	public function form($instance) {
		
		/* Set up some default widget settings. */      
		$defaults = array(
			'title' 	=> 'Google Maps',
			'width' 	=> 200,
			'height' 	=> 200,
			'lat' 		=> '',
			'long' 		=> '' 
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);

		?>
            <div class="advancedwidgetpack-options">

            <!-- Google Maps Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Google Maps Settings', self::SLUG); ?></legend>
                <!-- Title -->
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', self::SLUG); ?> </label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
                </p>
                <!-- Width -->
                <p>
                    <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" style="width:50px;" /> px
                </p>
                <!-- Height -->
                <p>
                    <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" style="width:50px;" /> px
                </p>	
                <!-- Latitude -->
                <p>
                    <label for="<?php echo $this->get_field_id('lat'); ?>"><?php _e('Latitude:', self::SLUG); ?> (<a href="http://www.tech-recipes.com/rx/5519/the-easy-way-to-find-latitude-and-longitude-values-in-google-maps/" target="_blank">Find here</a>)</label>
                    <input class="widefat" id="<?php echo $this->get_field_id('lat'); ?>" name="<?php echo $this->get_field_name('lat'); ?>" type="text" value="<?php echo esc_attr($lat); ?>" />
                </p>
                <!-- Longitude -->
                <p>
                    <label for="<?php echo $this->get_field_id('long'); ?>"><?php _e('Longitude:', self::SLUG); ?> (<a href="http://www.tech-recipes.com/rx/5519/the-easy-way-to-find-latitude-and-longitude-values-in-google-maps/" target="_blank">Find here</a>)</label>
                    <input class="widefat" id="<?php echo $this->get_field_id('long'); ?>" name="<?php echo $this->get_field_name('long'); ?>" type="text" value="<?php echo esc_attr($long); ?>" />
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
		$instance['title'] = strip_tags($newInstance['title'] );
		$instance['width'] = strip_tags($newInstance['width']);
		$instance['height'] = strip_tags($newInstance['height']);
		$instance['lat'] = strip_tags($newInstance['lat']);
		$instance['long'] = strip_tags($newInstance['long']);
		
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
		$title = apply_filters('widget_title', $instance['title'] );
		$width = empty($instance['width']) ? 240 : $instance['width'];
		$height = empty($instance['height']) ? 240 : $instance['height'];
		$lat = empty($instance['lat']) ? 0 : $instance['lat'];
		$long = empty($instance['long']) ? 0 : $instance['long'];
		$custom_id = time().rand();
			
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			
			?>
			<!-- Advanced Widget Pack: Google Maps - http://www.wpinsite.com -->
			<div class="textwidget">
                <div id="map<?php echo $custom_id; ?>" style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;margin-bottom:15px"></div>
                <script type="text/javascript">
                (function($) {
					$(document).ready( function() {
						
						$("#map<?php echo $custom_id; ?>").gMap({
							latitude: <?php echo $lat; ?>,
							longitude: <?php echo $long; ?>,
							maptype: 'ROADMAP', // 'HYBRID', 'SATELLITE', 'ROADMAP' or 'TERRAIN'
							zoom: 12,
							markers: [
								{
									latitude: <?php echo $lat; ?>,
									longitude: <?php echo $long; ?>
								}
							],
							controls: {
								panControl: true,
								zoomControl: true,
								mapTypeControl: true,
								scaleControl: true,
								streetViewControl: false,
								overviewMapControl: false
							}
						});
						
                	});
				})(jQuery);
                </script>
			</div>
			<!-- End Advanced Widget Pack: Google Maps -->
            <?php 
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>