<?php
/**
 * Advanced Widget Pack - Video
 */

class Advanced_Widget_Pack_Widget_Video extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_video';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Enables you to embed a video in your site using the built-in WordPress oEmbed feature.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Video Embed', self::SLUG), $widgetOptions, $controlOptions);
		
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
		'title' => 'Latest Video',
		'video_url' => '' ,
		'vwidth' => '',
		'description' => ''
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Video Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Video Settings', self::SLUG); ?></legend>
                <!-- Title -->
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', self::SLUG); ?> </label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
                </p>
                <!-- Video URL -->
                <p>
                    <label for="<?php echo $this->get_field_id('video_url'); ?>"><?php _e('Video URL:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('video_url'); ?>" name="<?php echo $this->get_field_name('video_url'); ?>" type="text" value="<?php echo esc_attr($video_url); ?>" />
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', self::SLUG); ?> <a href="http://codex.wordpress.org/Embeds" target="_blank"><?php _e('Learn More', self::SLUG); ?></a></span>
                </p>
                <!-- Video Width -->
                <p>
                    <label for="<?php echo $this->get_field_id('vwidth'); ?>"><?php _e('Video Width in pixels:', self::SLUG); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('vwidth'); ?>" name="<?php echo $this->get_field_name('vwidth'); ?>" type="text" value="<?php echo esc_attr($vwidth); ?>" style="width:50px" /> px
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Simply add a number without the \'px\' characters. EG 250.', self::SLUG); ?></span>
                </p>
                <!-- Video Description -->
                <p>
                    <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', self::SLUG); ?></label>
                    <textarea rows="5" class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text"><?php echo stripslashes($description); ?></textarea>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Enter in any content you\'d liked displayed after the video.', self::SLUG); ?></span>
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
		$instance['title'] = strip_tags($newInstance['title']);
		$instance['video_url'] = strip_tags($newInstance['video_url']);
		$instance['vwidth'] = strip_tags($newInstance['vwidth']);
		$instance['description'] = strip_tags($newInstance['description']);
			
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
		$title = apply_filters('widget_title', $instance['title']);
		$videourl = $instance['video_url'];
		$width = $instance['vwidth'];
		$description = $instance['description'];
		
		if($videourl){	
			/* Before Widget HTML */
			echo $before_widget;
			
			/* Title of widget */
			if($title) echo $before_title.$title.$after_title;	
				
				?>
				<!-- Advanced Widget Pack: Video Embed - http://www.wpinsite.com -->
				<div class="textwidget">
					<?php
						echo wp_oembed_get($instance['video_url'], array('width'=>$instance['vwidth']));
						if($description)
							echo '<span class="video_description">'.$description.'</span>';
					?>
				</div>
				<!-- End Advanced Widget Pack: Video Embed -->
				<?php 
			
			/* After Widget HTML */
			echo $after_widget;
		}
	}
}
?>