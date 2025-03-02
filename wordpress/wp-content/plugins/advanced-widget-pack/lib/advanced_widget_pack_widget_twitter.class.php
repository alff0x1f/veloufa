<?php
/**
 * Advanced Widget Pack - Twitter
 */



class Advanced_Widget_Pack_Widget_Twitter extends WP_Widget {
	
	/* The plugins slug */
	const SLUG = 'awp_twitter';
	
	/* The plugins current version number */
	const VERSION = '1.2.1';
	
	/**
	 * Create a widget instance and set the base infos
	 */
	public function __construct(){
		
		/* Widget settings */
		$widgetOptions = array(
			'classname' => self::SLUG,
			'description' => __('Displays a Twitter Widget which displays the recent tweets from a Twitter account.', self::SLUG)
		);
		
		/* Widget control settings */
		$controlOptions = array(
			'id_base' => self::SLUG
		);
			
		/* Create the widget */
		$this->WP_Widget(self::SLUG, __('AWP - Latest Tweets', self::SLUG), $widgetOptions, $controlOptions);
		
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
		'title' 			=> 'Latest Tweets',
		'tweet_styling'		=> 'normal',
		'new_window'		=> true,
		'twitter_count' 	=> '3',
		'twitter_username' 	=> '',
		'exclude_replies' 	=> true,
		'tweet_time' 		=> true,
		'follow_button'		=> true
		);
		$vars = wp_parse_args($instance, $defaults);
		
		extract($vars);
		?>
            <div class="advancedwidgetpack-options">

            <!-- Twitter Settings -->
            <fieldset class="widefat advancedwidgetpack-general">
                
                <legend><?php _e('Twitter Settings', self::SLUG); ?></legend>
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
                <!-- Tweet Styling -->
                <p>
                    <label for="<?php echo $this->get_field_id('tweet_styling'); ?>"><?php _e('Tweet Styling:', self::SLUG); ?> </label>
                    <select class="widefat" id="<?php echo $this->get_field_id('tweet_styling'); ?>" name="<?php echo $this->get_field_name('tweet_styling'); ?>">
                        <option value="normal" <?php if(esc_attr($tweet_styling) == "normal"){ echo "selected='selected'";} ?>><?php _e('Normal', self::SLUG); ?></option>
                        <option value="bubbled" <?php if(esc_attr($tweet_styling) == "bubbled"){ echo "selected='selected'";} ?>><?php _e('Bubbled', self::SLUG); ?></option>
                    </select>
                    <span style="display:block; padding:5px 0; color:#666; font-size:9px; font-style:italic;"><?php _e('Select the styling you wish to be applied to the Tweets', self::SLUG); ?></span>
                </p>
                <!-- Open Twitter Links in New Window -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('new_window'); ?>" name="<?php echo $this->get_field_name('new_window'); ?>" <?php if($new_window) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('new_window'); ?>" title="<?php _e('When tweets clicked, forces timestamp links to be opened in a new tab/window', self::SLUG); ?>"><?php _e('Open timestamp links in new tab/window', self::SLUG); ?></label>
                </p>
                <!-- Twitter Username -->
                <p>
                    <label for="<?php echo $this->get_field_id('twitter_username'); ?>" title="<?php _e('Twitter Account Username (e.g wpinsite)', self::SLUG); ?>"><?php _e('Twitter Username:', self::SLUG); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('twitter_username'); ?>" name="<?php echo $this->get_field_name('twitter_username'); ?>" value="<?php echo esc_attr($twitter_username); ?>" class="widefat"  />
                </p>
                <!-- Number of tweets -->
                <p>
                    <label for="<?php echo $this->get_field_id('twitter_count'); ?>"><?php _e('Number of Tweets to display:', self::SLUG); ?></label>
                    <select class="widefat" id="<?php echo $this->get_field_id('twitter_count'); ?>" name="<?php echo $this->get_field_name('twitter_count'); ?>">
                        <?php for($i = 1; $i <= 25; $i += 1) { ?>
                        <option value="<?php echo $i; ?>" <?php if(esc_attr($twitter_count) == $i){ echo "selected='selected'";} ?>><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </p>
                <!-- Exclude Replies -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('exclude_replies'); ?>" name="<?php echo $this->get_field_name('exclude_replies'); ?>" <?php if ($exclude_replies) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('exclude_replies'); ?>" title="<?php _e('Exclude @replies', self::SLUG); ?>"><?php _e('Exclude @replies', self::SLUG); ?></label>
                </p>
                <!-- Tweet Time -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('tweet_time'); ?>" name="<?php echo $this->get_field_name('tweet_time'); ?>" <?php if ($tweet_time) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('tweet_time'); ?>" title="<?php _e('Display time of tweet', self::SLUG); ?>"><?php _e('Display time of tweet', self::SLUG); ?></label>
                </p>
                <!-- Follow Button -->
                <p>
                    <input type="checkbox" id="<?php echo $this->get_field_id('follow_button'); ?>" name="<?php echo $this->get_field_name('follow_button'); ?>" <?php if ($follow_button) echo 'checked="checked"'; ?> class="checkbox" />
                    <label for="<?php echo $this->get_field_id('follow_button'); ?>" title="<?php _e('Display Twitter Follow Button', self::SLUG); ?>"><?php _e('Display Twitter Follow Button', self::SLUG); ?></label>
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
		$instance['tweet_styling'] = $newInstance['tweet_styling'];
		$instance['new_window'] = $newInstance['new_window'];
        $instance['twitter_count'] = $newInstance['twitter_count'];
        $instance['twitter_username'] = strip_tags($newInstance['twitter_username']);
        $instance['exclude_replies'] = $newInstance['exclude_replies'];
        $instance['tweet_time'] = $newInstance['tweet_time'];
		$instance['follow_button'] = $newInstance['follow_button'];
		
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
		$tweet_styling = $instance['tweet_styling'];
		$new_window = $instance['new_window'];
		$twitter_count = $instance['twitter_count'];
		$twitter_username = $instance['twitter_username'];
		$exclude_replies = $instance['exclude_replies'];
		$tweet_time = $instance['tweet_time'];
		$follow_button = $instance['follow_button'];
		
		/* Before Widget HTML */
		echo $before_widget;
		
		/* Title of widget */
		if($title) echo $before_title.$title.$after_title;	
			
			?>
			<!-- Advanced Widget Pack: Twitter Widget - http://www.wpinsite.com -->
			<div class="textwidget">
				<?php
					echo $this->advanced_widget_pack->awp_twitter($widgetwidth, $width_by, $tweet_styling, $twitter_count, $twitter_username, $widget_id, $tweet_time, $exclude_replies, $new_window, $follow_button);
				?>
			</div>
			<!-- End Advanced Widget Pack: Twitter Widget -->
			<?php 
		
		/* After Widget HTML */
		echo $after_widget;
	}
}
?>