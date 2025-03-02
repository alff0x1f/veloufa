<?php
/**
 * Advanced Widget Pack - Flickr
 */

class Advanced_Widget_Pack_Widget_Flickr extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_flickr';
	
	/* The plugins current version number */
	const VERSION = '1.2.2';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays photos from a specified Flickr account.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Flickr Feed', self::SLUG), $widgetOptions, $controlOptions);
		
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
			'widgetwidth'		=> '',
			'width_by'			=> 'px',
			'title' 			=> 'Flickr Photos',
			'num' 				=> 9,
			'user' 				=> '',
			'include_colorbox' 	=> false
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Flickr Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Flickr Settings', self::SLUG); ?></legend>
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
                <!-- Flckr ID -->
                <p>
                    <label for="<?php echo $this->get_field_id('user'); ?>"><?php echo __('Flickr ID:', self::SLUG).' (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):'; ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($user); ?>" />
                </p>
                <!-- Number of photos -->
                <p>
                    <label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of photos:', self::SLUG); ?></label>
                    <input class="widefat" type="text" id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num'); ?>" value="<?php echo $num; ?>" style="width:40px" />
                </p>  
                <!-- Include Colorbox -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('include_colorbox'); ?>" name="<?php echo $this->get_field_name('include_colorbox'); ?>" <?php if($include_colorbox) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('include_colorbox'); ?>" title="<?php _e('Include ColorBox jQuery library', self::SLUG); ?>"><?php _e('Include ColorBox jQuery library', self::SLUG); ?></label>
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
		$instance['num'] = strip_tags($newInstance['num']);
		$instance['user'] = strip_tags($newInstance['user']);
		$instance['include_colorbox'] = strip_tags($newInstance['include_colorbox']);
			
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
		$num = $instance['num'];
		$user = $instance['user'];
		$include_colorbox = $instance['include_colorbox'];
		
		if($include_colorbox){
			
			/* Enqueue Script */
			wp_enqueue_script(self::SLUG . '-colorbox', plugins_url('js/jquery.colorbox-min.js', dirname(__FILE__)), array('jquery'), '', false);
		}
		
		/* Display Flickr Photos */
        if($num) { 
			
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			?>
			<!-- Advanced Widget Pack: Flickr Feed - http://www.wpinsite.com -->
			<div class="textwidget">
				<div class="advancedwidgetpack" <?php echo $widgetwidth == '' ? '' : 'style="width:'.$widgetwidth.$width_by.' !important"';?>>
                        <script type="text/javascript">
                            <!--
                            (function($) {
								$(document).ready( function() {                
									$('#flickr').jflickrfeed({
										limit: <?php echo $num; ?>,
										qstrings: {
											id: '<?php echo $user; ?>'
										},
										itemTemplate: '<li>'+
														'<a rel="colorbox" href="{{image}}" title="{{title}}">' +
															'<img src="{{image_s}}" alt="{{title}}" />' +
														'</a>' +
													  '</li>'
									}, function(data) {
										<?php if($include_colorbox){ ?>
										$('#flickr a').colorbox();		
										<?php } ?>	
									});
                            	});
							})(jQuery);
                            // -->
                        </script>
                        
                        <div class="flickr"> 
                            <ul id="flickr" class="flickr-thumbs"></ul>
                            <div class="flickrmore"></div>
                            <a href="http://www.flickr.com/photos/<?php echo $user; ?>" target="_blank"><?php _e('View more photos', self::SLUG); ?> &raquo;</a>
                        </div>
				</div>
			</div>
			<!-- End Advanced Widget Pack: Flickr Feed -->
            <?php 
		
		/* After Widget HTML */
		echo $after_widget;
		}
	}
}
?>