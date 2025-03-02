<?php
/**
 * Advanced Widget Pack
 */

class Advanced_Widget_Pack {
	
	/* The plugins slug */
	const SLUG = 'advanced_widget_pack';
	
	/* The plugins current version number */
	const VERSION = '1.4';
	
	private $options;
	private static $instance;
	private static $name = 'Advanced_Widget_Pack';
	private static $prefix = 'advanced_widget_pack';
	private static $public_option = 'no';
	private static $textdomain = 'advanced_widget_pack';
	
	/**
	 * Create an instance of the plugin
	 */
	private function __construct(){
		self::load_text_domain();
		register_activation_hook(__FILE__, array(&$this, 'set_up_options'));
		
		 /* Set up the settings. */
		add_action('admin_init', array(&$this, 'register_settings'));
		
		 /* Set up the administration page. */
		add_action('admin_menu', array(&$this, 'set_up_admin_page'));
		
		 /* Fetch the options, and, if they haven't been set up yet, display a notice to the user. */
		$this->get_options();
		if('' == $this->options){
			add_action('admin_notices', array(&$this, 'admin_notices'));
			$this->set_up_options();
		}
				
		 /* Add our widget when widgets get intialized. */
		if($this->options['display-contact-info'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_ContactInfo");'));
		
		if($this->options['display-latest-posts'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_LatestPosts");'));
		
		if($this->options['display-popular-posts'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_PopularPosts");'));
		
		if($this->options['display-random-posts'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_RandomPosts");'));
		
		if($this->options['display-feedburner-subscribe'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Feedburner");'));
		
		if($this->options['display-flickr-feed'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Flickr");'));
		
		if($this->options['display-google-maps'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_GoogleMaps");'));
		
		if($this->options['display-video-embed'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Video");'));
		
		if($this->options['display-twitter'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Twitter");'));
		
		if($this->options['display-tabbed-posts'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_TabbedPosts");'));
		
		if($this->options['display-archives'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Archives");'));
		
		if($this->options['display-categories'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Categories");'));
		
		if($this->options['display-pages'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Pages");'));
		
		if($this->options['display-authors'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Authors");'));
		
		if($this->options['display-advertisement'] == 'true')
		add_action('widgets_init', create_function('', 'return register_widget("Advanced_Widget_Pack_Widget_Advertisements");'));
		
		/* Register Widget Stylesheets & Scripts*/
		if(is_admin()){
			
			/* Register additional styles for admin page */
			add_action('admin_enqueue_scripts', array($this, 'registerAdminStyle'));
			add_action('admin_enqueue_scripts',array( &$this, 'registerAdminScripts'));
		}	
		
		/* Register AdvancedWidgetPack widget styles & scripts */
		add_action('wp_print_styles', array($this, 'registerStyle'));
		add_action('wp_enqueue_scripts', array($this, 'add_javascripts'));
		
	}

	/**
	 * Create an instance of the plugin
	 */
	public static function get_instance(){
		if(empty(self::$instance)){
			self::$instance = new self::$name;
		}
		return self::$instance;
	}
	
	/**
	 * Enqueue the widgets admin stylesheet
	 *
	 * Will be run in "admin_print_styles-widgets.php" action and only on widgets admin page
	 */
	public function registerAdminStyle(){
		
		/* Enqueue Style */
		wp_enqueue_style(self::SLUG.'-admin', plugins_url('css/advanced-widget-pack-admin.css' , dirname(__FILE__)), array(), self::VERSION, 'screen');			
	}
	
	/**
	 * Enqueue the widgets admin scripts
	 *
	 * Will be run in "admin_print_styles-widgets.php" action and only on widgets admin page
	 */
	public function registerAdminScripts() {
		
		/* Register Script */
		wp_enqueue_script(self::SLUG . '-admin', plugins_url('js/advanced-widget-pack-admin.js', dirname(__FILE__)), array(), '', false);
	}
	
	/**
	 * Enqueue the widgets stylesheet
	 *
	 * Will be run in "wp_print_styles" action and only on frontend pages and if widget is actually used
	 */
	public function registerStyle(){
		
		/* Enqueue style */
		wp_enqueue_style(self::SLUG, plugins_url('css/advanced-widget-pack.css' , dirname(__FILE__)), array(), self::VERSION, 'screen');	
	}
	
	/**
	 * Make sure jQuery is included in plugin and include javascript file(s)
	 */
	public function add_javascripts() {

		wp_enqueue_script(self::SLUG . '-js', plugins_url('js/advanced-widget-pack.js', dirname(__FILE__)), array('jquery','jquery-ui-tabs'), '', false);
	} 
		
	/**
	 * Display Admin notices
	 */
	public function admin_notices(){
		echo '<div class="error fade">' . $this->get_admin_notices() . '</div>';
	}
	
	/**
	 * The Admin page to enable/disable specific widgets
	 */
	public function admin_page () {
		global $blog_id;	
		
		$display_latest_posts = (is_array($this->options)) ? $this->options['display-latest-posts'] : '';
		$display_popular_posts = (is_array($this->options)) ? $this->options['display-popular-posts'] : '';
		$display_random_posts = (is_array($this->options)) ? $this->options['display-random-posts'] : '';
		$display_contact_info = (is_array($this->options)) ? $this->options['display-contact-info'] : '';
		$display_feedburner_subscribe = (is_array($this->options)) ? $this->options['display-feedburner-subscribe'] : '';
		$display_flickr_feed = (is_array($this->options)) ? $this->options['display-flickr-feed'] : '';
		$display_google_maps = (is_array($this->options)) ? $this->options['display-google-maps'] : '';
		$display_video_embed = (is_array($this->options)) ? $this->options['display-video-embed'] : '';
		$display_twitter = (is_array($this->options)) ? $this->options['display-twitter'] : '';
		$display_tabbed_posts = (is_array($this->options)) ? $this->options['display-tabbed-posts'] : '';
		$display_archives = (is_array($this->options)) ? $this->options['display-archives'] : '';
		$display_categories = (is_array($this->options)) ? $this->options['display-categories'] : '';
		$display_pages = (is_array($this->options)) ? $this->options['display-pages'] : '';
		$display_authors = (is_array($this->options)) ? $this->options['display-authors'] : '';
		$display_advertisement = (is_array($this->options)) ? $this->options['display-advertisement'] : '';
		
		if(isset($_POST[self::$prefix . '_nonce'])){

			$nonce = $_POST[self::$prefix . '_nonce'];
			$nonce_key = self::$prefix . '_update_options';
			
			if(!wp_verify_nonce($nonce, $nonce_key)){
				?>
				<div class="wrap">
					<div id="icon-options-general" class="icon32">
						<br />
					</div>
					<h2>Advanced Widget Pack - Widget Settings</h2>
					<p><?php  echo __('What you\'re trying to do looks a little shady.', self::SLUG); ?></p>
				</div>
				<?php
				return false;
			}
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div>
			<h2><?php echo __('Advanced Widget Pack - Widget Settings', self::SLUG) ; ?></h2>
			<p><?php echo __('Use this page to enable and disable the individual widgets.<br /><br />If a widget is disabled it will not be displayed on the Widgets page.', self::SLUG) ?></p>
			<form action="options.php" method="post">
				<?php settings_fields(self::$prefix . '_options'); ?>
                <table class="form-table customstyle">
                    <tr valign="middle">
                        <td colspan="4">
                        	<img src="<?php echo WP_PLUGIN_URL; ?>/advanced-widget-pack/images/adm-widgets.jpg" alt="<?php _e('Advanced Widget Pack', self::SLUG); ?>" /><br />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-latest-posts"><?php _e('Display Latest Posts Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                            <div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_latest_posts == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_latest_posts == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-latest-posts" name="<?php echo self::$prefix; ?>_options[display-latest-posts]" class="plugin-switch-value" type="hidden" value="<?php echo $display_latest_posts; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-popular-posts"><?php _e('Display Popular Posts Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_popular_posts == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_popular_posts == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-popular-posts" name="<?php echo self::$prefix; ?>_options[display-popular-posts]" class="plugin-switch-value" type="hidden" value="<?php echo $display_popular_posts; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-random-posts"><?php _e('Display Random Posts Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_random_posts == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_random_posts == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-random-posts" name="<?php echo self::$prefix; ?>_options[display-random-posts]" class="plugin-switch-value" type="hidden" value="<?php echo $display_random_posts; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-contact-info"><?php _e('Display Contact Info Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_contact_info == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_contact_info == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-contact-info" name="<?php echo self::$prefix; ?>_options[display-contact-info]" class="plugin-switch-value" type="hidden" value="<?php echo $display_contact_info; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-feedburner-subscribe"><?php _e('Display Feedburner Subscription Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_feedburner_subscribe == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_feedburner_subscribe == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-feedburner-subscribe" name="<?php echo self::$prefix; ?>_options[display-feedburner-subscribe]" class="plugin-switch-value" type="hidden" value="<?php echo $display_feedburner_subscribe; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-flickr-feed"><?php _e('Display Flickr Feed Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_flickr_feed == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_flickr_feed == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-flickr-feed" name="<?php echo self::$prefix; ?>_options[display-flickr-feed]" class="plugin-switch-value" type="hidden" value="<?php echo $display_flickr_feed; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-google-maps"><?php _e('Display Google Maps Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_google_maps == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_google_maps == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-google-maps" name="<?php echo self::$prefix; ?>_options[display-google-maps]" class="plugin-switch-value" type="hidden" value="<?php echo $display_google_maps; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-video-embed"><?php _e('Display Video Embed Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_video_embed == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_video_embed == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-video-embed" name="<?php echo self::$prefix; ?>_options[display-video-embed]" class="plugin-switch-value" type="hidden" value="<?php echo $display_video_embed; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-twitter"><?php _e('Display Twitter Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_twitter == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_twitter == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-twitter" name="<?php echo self::$prefix; ?>_options[display-twitter]" class="plugin-switch-value" type="hidden" value="<?php echo $display_twitter; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-tabbed-posts"><?php _e('Display Tabbed Posts Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_tabbed_posts == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_tabbed_posts == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-tabbed-posts" name="<?php echo self::$prefix; ?>_options[display-tabbed-posts]" class="plugin-switch-value" type="hidden" value="<?php echo $display_tabbed_posts; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-archives"><?php _e('Display Archives Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_archives == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_archives == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-archives" name="<?php echo self::$prefix; ?>_options[display-archives]" class="plugin-switch-value" type="hidden" value="<?php echo $display_archives; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-categories"><?php _e('Display Categories Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_categories == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_categories == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-categories" name="<?php echo self::$prefix; ?>_options[display-categories]" class="plugin-switch-value" type="hidden" value="<?php echo $display_categories; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-pages"><?php _e('Display Pages Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_pages == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_pages == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-pages" name="<?php echo self::$prefix; ?>_options[display-pages]" class="plugin-switch-value" type="hidden" value="<?php echo $display_pages; ?>" />
                        </td>
                        <th scope="row">
                        	<label for="<?php echo self::$prefix; ?>-authors"><?php _e('Display Authors Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_authors == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_authors == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-authors" name="<?php echo self::$prefix; ?>_options[display-authors]" class="plugin-switch-value" type="hidden" value="<?php echo $display_authors; ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo self::$prefix; ?>-advertisement"><?php _e('Display Advertisement Widget:', self::SLUG); ?></label>
                        </th>
                        <td>
                        	<div class="awp-switch-link">
                                <a href="#" rel="true" class="link-true <?php echo $display_advertisement == 'true' ? 'active' : '' ; ?>"></a>
                                <a href="#" rel="false" class="link-false <?php echo $display_advertisement == 'false' ? 'active' : '' ; ?>"></a>
                            </div>
                            <input id="<?php echo self::$prefix; ?>-advertisement" name="<?php echo self::$prefix; ?>_options[display-advertisement]" class="plugin-switch-value" type="hidden" value="<?php echo $display_advertisement; ?>" />
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php echo  __('Save Changes', self::SLUG); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
	
	/**
	 * Define the Admin MailChimp API Key message
	 */
	public function get_admin_notices () {
		global $blog_id;
		$notice = '<p>';
		$notice .= __('You\'ll need to configure the Advanced Widget Pack plugin options before using it. ', self::SLUG) . __('Enable / Disable the individual widgets ', self::SLUG) . ' <a href="' . get_admin_url($blog_id) . 'options-general.php?page=advanced-widget-pack/lib/advanced_widget_pack.class.php">' . __('here', self::SLUG) . '.</a>';
		$notice .= '</p>';
		return $notice;
	}
	
	/**
	 * Return the plugin options
	 */
	public function get_options(){
		$this->options = get_option(self::$prefix . '_options');
		return $this->options;
	}
	
	/**
	 * Register and load textdomain
	 */
	public function load_text_domain(){
		load_plugin_textdomain(self::$textdomain, null, str_replace('lib', 'languages', dirname(plugin_basename(__FILE__))));
	}
	
	/**
	 * Register the plugin settings
	 */
	public function register_settings(){
		register_setting( self::$prefix . '_options', self::$prefix . '_options', array($this, 'validate_key'));
	}
	
	/**
	 * Remove the plugin settings
	 */
	public function remove_options(){
		delete_option(self::$prefix . '_options');
	}
	
	/**
	 * Register create the admin page
	 */
	public function set_up_admin_page(){
		add_submenu_page('options-general.php', 'Advanced Widget Pack Options', 'Adv. Widget Pack', 'activate_plugins', __FILE__, array(&$this, 'admin_page'));
	}

	/**
	 * Set up the plugin options
	 */
	public function set_up_options(){
		add_option(self::$prefix . '_options', '', '', self::$public_option);
		
		/* Set Defaults */
		$this->options['display-latest-posts'] = 'false';
		$this->options['display-popular-posts'] = 'false';
		$this->options['display-random-posts'] = 'false';
		$this->options['display-contact-info'] = 'false';
		$this->options['display-feedburner-subscribe'] = 'false';
		$this->options['display-flickr-feed'] = 'false';
		$this->options['display-google-maps'] = 'false';
		$this->options['display-video-embed'] = 'false';
		$this->options['display-twitter'] = 'false';
		$this->options['display-tabbed-posts'] = 'false';
		$this->options['display-archives'] = 'false';
		$this->options['display-categories'] = 'false';
		$this->options['display-pages'] = 'false';
		$this->options['display-authors'] = 'false';
		$this->options['display-advertisement'] = 'false';
	}
	
	/**
	 * Validate Plugin Key
	 */
	public function validate_key($plugin_key){
		//#TODO: Add Plugin validation logic.
		return $plugin_key;
	}
	
	/**
	 * Update plugin settings
	 */
	private function update_options($options_values) {
		$old_options_values = get_option(self::$prefix . '_options');
		$new_options_values = wp_parse_args($options_values, $old_options_values);
		update_option(self::$prefix .'_options', $new_options_values);
		$this->get_options();
	}
	
	/**
	*	Custom get posts widget
	**/
	public function awp_get_posts($query='latest', $cat='', $post_type = 'post', $count = 5, $chars = 35, $thumbnail = true) {
		
		switch($query) {
			case 'popular':
				$loop = new WP_Query('post_type='.$post_type.'&cat='.$cat.'&order=DESC&orderby=comment_count&posts_per_page='.$count);
				break;
			case 'random':
				$loop = new WP_Query('post_type='.$post_type.'&cat='.$cat.'&showposts='. $count .'&orderby=rand');
				break;
			default:
				$loop = new WP_Query('post_type='.$post_type.'&cat='.$cat.'&posts_per_page='.$count);
		} 

		if($loop->have_posts()){
			
		?>
			<ul>
				<?php
				while($loop->have_posts()) : $loop->the_post();
					
					$post_title = get_the_title();
					$post_date = get_the_date('j M Y');
					$post_time = get_the_time('g:i a');
					
					if($chars > 0) {
						$post_title = $this->snippet_text($post_title, $chars);
					}
					?>
					<li>
						<?php if($thumbnail){ ?>
							<?php echo $this->featured_image_thumb(50); ?>
						<?php } ?>
						<span class="headline">
							<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php echo $post_title; ?></a>
                            <?php 
							switch($query) {
								case 'popular':
									?>
                                    <span class="time"><?php echo comments_number('0 Comments', '1 Comment', '% Comments' ) ;?></span>
                                    <?php
									break;
								case 'random':
									?>
                                    <span class="time"><?php echo $post_date.' '.__('at', self::SLUG).' '.$post_time; ?></span>
                                    <?php
									break;
								default:
									?>
                                    <span class="time"><?php echo $post_date.' '.__('at', self::SLUG).' '.$post_time; ?></span>
                                    <?php
							} 	
							?>
						</span>
					</li>
					<?php
				endwhile;
				?>
			</ul>
			<?php		
			wp_reset_postdata();
		} else {
			echo '<li>'.__('No posts available', self::SLUG).'</li>'."\n";
		}
	}
	
	/**
	 * Retrieves the image for a post
	 *
	 * Uses the post_thumbnails if available or
	 * searches through the post and retrieves the first found image for use as thumbnails
	 */
	function featured_image_thumb($size = 50) {
		global $post;
		// If a featured image has been set, use the featured-thumbnail size that was set above with the class of 'thumb'
		if(has_post_thumbnail() ) {
			echo '<a href="'.get_permalink().'" title="'.get_the_title().'" >';
			the_post_thumbnail(array($size,$size),array('class' => 'thumb'));
			echo '</a>';
		}
		// If a featured image is not set, get the first image in the post content
		else {
			$first_img = '';
			ob_start();
			ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			$first_img = $matches[1][0];
	
			// Define a default fallback image in case a featured image is not set and there are no images in the post content
			if(empty($first_img)){
				$first_img = WP_PLUGIN_URL.'/advanced-widget-pack/images/nothumb.png';
			}
	
			// Generate the HTML code to display the image and resize the image with timthumb.php
			return '<a title="'.get_the_title().'" href="'.get_permalink().'"><img class="thumb" src="'.WP_PLUGIN_URL.'/advanced-widget-pack/timthumb.php?src=' . $first_img .'&amp;w='.$size.'&amp;h='.$size.'" alt="" /></a>';
		}
	}
	
	/**
	 * Shortens a string of text
	 *
	 * Takes the input string and returns a shortened version of the string
	 *
	 * @param string $text - The text string to shorten
	 * @param integer $length - The number of characters to strip th etext down to
	 *
	 */
	public function snippet_text($text, $length = 0) {
		if(defined('MB_OVERLOAD_STRING')) {
		  $text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
			if (mb_strlen($text) > $length) {
				return htmlentities(mb_substr($text,0,$length), ENT_COMPAT, get_option('blog_charset')).'...';
			} else {
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
			}
		} else {
			$text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
			if (strlen($text) > $length) {
				return htmlentities(substr($text,0,$length), ENT_COMPAT, get_option('blog_charset')).'...';
			} else {
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
			}
		}
	}
	
	/**
	 * Filter Twitter Tweets
	 *
	 * @param string $text Tweet to filter
	 * @return string $text Filtered tweet 
	 */
	public function awp_twitter_filter($text) {
		$text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
		$text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);    
		$text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
		$text = preg_replace("/#(\w+)/", "<a class=\"twitter-link\" href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $text);
		$text = preg_replace("/@(\w+)/", "<a class=\"twitter-link\" href=\"http://twitter.com/\\1\" target=\"_blank\">@\\1</a>", $text);
		return $text;
	}
	
	/**
	 * Recent Tweets
	 *
	 * @param int $widgetwidth The width of the widget
	 * @param string $tweet_styling The CSS styling applied to the tweets
	 * @param int $count Number of tweets to display
	 * @param string $username Twitter username to pull tweets from
	 * @param string $widget_id Unique ID for widget
	 * @param string $time Display time of tweets, yes or no
	 * @param string $exclude_replies Exclude replies, yes or no
	 * @return string $filtered_tweet Final list of tweets
	 */
	function awp_twitter($widgetwidth, $width_by, $tweet_styling, $twitter_count, $username, $widget_id, $tweet_time = 'on', $exclude_replies = 'on', $new_window = 'on', $follow_button) {		

			$filtered_message = null;
			$output = null;
			$iterations = 0;
			$style_id = null;
			$width = '';
			$window = $new_window == '' ? '' : 'target="_blank"';
			
			// Grab response from Twitter if no cache
			$response = wp_remote_get('http://api.twitter.com/1/statuses/user_timeline.xml?screen_name='.$username );
			if(!is_wp_error($response)) {
				$xml = simplexml_load_string($response['body']);
				if(empty($xml->error)) {
					if(isset($xml->status[0])) {
						$tweets = array();
						foreach($xml->status as $tweet) {
							if($iterations == $twitter_count) break;
							$text = (string) $tweet->text;
							if($exclude_replies == 'off' || ($exclude_replies == 'on' && $text[0] != "@")) {
								$iterations++;
								$tweets[] = array(
									'id' => (string)$tweet->id,
									'text' => $this->awp_twitter_filter($text),
									'created' =>  strtotime( $tweet->created_at ),
									'user' => array(
										'name' 			=> (string)$tweet->user->name,
										'screen_name' 	=> (string)$tweet->user->screen_name,
										'image' 		=> (string)$tweet->user->profile_image_url,
										'utc_offset' 	=> (int)$tweet->user->utc_offset[0],
										'follower' 		=> (int)$tweet->user->followers_count
									)
								);
							}
						}
					}
				}
			}
			
			// Start output of tweets
			if(isset($tweets[0])) {	
				foreach($tweets as $tweet) {	
					$output .= '<li class="tweet">';
					$output .= '<div class="tweet-content">'.$tweet['text'].'</div>';
					if($tweet_time == 'on') $output .= '<div class="meta"><span class="time-meta"><a href="http://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id'].'" '.$window.'>'.date_i18n(get_option('date_format')." - ".get_option('time_format'), $tweet['created'] + $tweet['user']['utc_offset']).'</a></span></div>';
					$output .= '<div class="clear"></div></li>';
				}
				if($follow_button){
					$output .= '<li class="nodash"><div class="tfsubscribelink">';
					$output .= '<a href="https://twitter.com/'.$username.'" class="twitter-follow-button" data-lang="en" data-width="90%" data-show-screen-name="false"></a>';
					$output .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
					$output .= '</div></li>';
				}
			}

			
			if($tweet_styling == 'bubbled'){
				$style_id = 'bubbled';
			} else {
				$style_id = 'normal';
			}

			if(!empty($widgetwidth)){
				$width = 'style="width:'.$widgetwidth.$width_by.' !important;"';
			} else {
				$width = '';
			}

			// Filter output
			if($output)
				$filtered_tweet = '<div id="'.$style_id.'" '.$width.'><ul class="tweets">'.$output.'</ul></div>';
			else
				$filtered_tweet = '<div id="'.$style_id.'" '.$width.'><ul class="tweets"><li>'.__('No Tweets found', self::SLUG).'</li></ul></div>';
			
			// Return the output!
			return $filtered_tweet;
	}
}
?>