<?php

add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options(){
	
//Access the WordPress Categories via an Array
$of_categories = array();  
$of_categories_obj = get_categories('hide_empty=0');
foreach ($of_categories_obj as $of_cat) {
    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
$categories_tmp = array_unshift($of_categories, "");    
       
//Access the WordPress Pages via an Array
$of_pages = array();
$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($of_pages_obj as $of_page) {
$of_pages[$of_page->ID] = $of_page->post_name; }
$of_pages_tmp = array_unshift($of_pages, "Select a page:");       

//RESPONSIVE ENABLE/DISABLE
$enable_disable = array('enable','disable'); 
$disable_enable = array('disable','enable'); 

//HOMEPAGE BLOCKS
$of_options_homepage_blocks = array(
	"enabled" => array (
		"placebo" => "placebo", //REQUIRED!
		"home_tagline" => "Tagline",
		
		
	),
	"disabled" => array (
		"placebo" => "placebo", //REQUIRED!
				
	),
);

//STYLESHEET 
$alt_stylesheet_path = LAYOUT_PATH;
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//Background Images Reader
$bg_images_path = get_stylesheet_directory(). '/framework/images/bg/'; // change this to where you store your bg images
$bg_images_url = get_template_directory_uri().'/framework/images/bg/'; // change this to where you store your bg images
$bg_images = array();

if ( is_dir($bg_images_path) ) {
    if ($bg_images_dir = opendir($bg_images_path) ) { 
        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
                $bg_images[] = $bg_images_url . $bg_images_file;
            }
        }    
    }
}


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

///////////////////////////////////////////////////////////////////////////////GENERAL	

$of_options[] = array( "name" => __('General', 'siiimple'),
					"type" => "heading");
				
$of_options[] = array( "name" => __('Main Logo Upload', 'siiimple'),
					"desc" => __('Upload your custom logo using the native media uploader, or define the URL directly', 'siiimple'),
					"id" => "custom_logo",
					"std" => "",
					"type" => "media");
					
$url =  ADMIN_DIR . 'assets/images/';
$of_options[] = array( "name" => "Main Layout",
					"desc" => "Select a main layout.  First option has no left sidebar.  Second option does.  The demo is showing the second option.",
					"id" => "layout",
					"std" => "left-sidebar",
					"type" => "images",
					"options" => array(
						'no-left-sidebar' => $url . '2cr.png',
						'left-sidebar' => $url . '3cm.png')
					);
										
$of_options[] = array( "name" => __('Enable or Disable Responsive Layout', 'siiimple'),
					"desc" => __('Select to enable/disable the responsive layout', 'siiimple'),
					"id" => "disable_responsive",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
										
$of_options[] = array( "name" => __('Custom Favicon', 'siiimple'),
					"desc" => __('Upload or past the URL for your custom favicon.', 'siiimple'),
					"id" => "custom_favicon",
					"std" => "",
					"type" => "upload");
					
$of_options[] = array( "name" => __('Header Advert', 'siiimple'),
					"desc" => __('Add an Advert.  The dimensions are 728x90, a standard size for horizontal advertising areas.  Be sure to add your link below.', 'siiimple'),
					"id" => "horizontal_ad",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => __('Header Advert Link', 'siiimple'),
					"desc" => __('Paste your link to the ad display (header section only)', 'siiimple'),
					"id" => "horizontal_ad_link",
					"std" => "",
					"type" => "textarea");
					
$of_options[] = array( "name" => __('Tracking Code (Header)', 'siiimple'),
					"desc" => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'siiimple'),
					"id" => "tracking_header",
					"std" => "",
					"type" => "textarea");
					
///////////////////////////////////////////////////////////////////////////////STYLING					

$of_options[] = array( "name" => __('Styling', 'siiimple'),
					"type" => "heading");

$of_options[] = array( "name" => __('General Color Scheme', 'siiimple'),
					"desc" => __('You can set a general color scheme that is different than the demo red.', 'siiimple'),
					"id" => "general_color",
					"std" => "#ff9100",
					"type" => "color");
					
$of_options[] = array( "name" => __('Body Border Top', 'siiimple'),
					"desc" => __('Change the color of the border at top of page.', 'siiimple'),
					"id" => "body_border_color",
					"std" => "#343434",
					"type" => "color");
					
$of_options[] = array( "name" => __('Color of Icons on Pages/Posts', 'siiimple'),
					"desc" => __('Change colors of your icons on the pages.', 'siiimple'),
					"id" => "icon_colors",
					"std" => "#343434",
					"type" => "color");
					
$of_options[] = array( "name" => __('Size of Icons on Pages/Posts', 'siiimple'),
                    "desc" => __('Change the size of icons on the pages.  It is measured in pixels.  Ex: 12px', 'siiimple'),
                    "id" => "icon_size",
                    "std" => "",
                    "type" => "text");


$of_options[] = array( "name" => __('Custom CSS', 'siiimple'),
                    "desc" => __('Quickly add some CSS to your theme by adding it to this block.', 'siiimple'),
                    "id" => "custom_css",
                    "std" => "",
                    "type" => "textarea");
                    
///////////////////////////////////////////////////////////////////////////////TOP CAROUSEL
                    
$of_options[] = array( "name" => __('Top Carousel', 'siiimple'),
					"type" => "heading");     
					
$of_options[] = array( "name" => "Very Top Carousel",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Very Top Slider','siiimple')."</h3>
						".__('', 'siiimple')."This is the section that automatically slides down on page load.  It's the very top of the home page.  You can disable it, change the category, and also choose the number of slides you wish to show.",
						"icon" => true,
						"type" => "info");               
	
$of_options[] = array( "name" => __('Enable or Disable Top Carousel', 'siiimple'),
					"desc" => __('Select to enable/disable the top carousel that slides down on page load.', 'siiimple'),
					"id" => "disable_topSlider",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);	
					
$of_options[] = array( "name" => __('Top Carousel Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display in the top slider.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "top_carousel",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Top Carousel Number', 'siiimple'),
                    "desc" => __('How many posts in this section.  Leave blank for unlimited.', 'siiimple'),
                    "id" => "top_carousel_num",
                    "std" => "",
                    "type" => "text");	
                    
$of_options[] = array( "name" => __('Panel Background Color', 'siiimple'),
					"desc" => __('You can set a background color to the slider panel.', 'siiimple'),
					"id" => "panel_bg_color",
					"std" => "#343434",
					"type" => "color");	
					
$of_options[] = array( "name" => __('Panel Button Background Color', 'siiimple'),
					"desc" => __('You can set a background color to the panel button.', 'siiimple'),
					"id" => "panel_btn_color",
					"std" => "#ff9100",
					"type" => "color");	
					
$of_options[] = array( "name" => __('Panel Item Border Color (Left)', 'siiimple'),
					"desc" => __('You can set the color of the border that divides the carousel items.  The effect of a groove is made by setting one color darker than the other.  In the demo the right border is set to #222, whereas the left border is set to #444.  It gives a nice effect.', 'siiimple'),
					"id" => "panel_btn_color_left",
					"std" => "",
					"type" => "color");
					
$of_options[] = array( "name" => __('Panel Item Border Color (Right)', 'siiimple'),
					"desc" => __('You can set the color of the border that divides the carousel items.  The effect of a groove is made by setting one color darker than the other.  In the demo the right border is set to #222, whereas the left border is set to #444.  It gives a nice effect.', 'siiimple'),
					"id" => "panel_btn_color_right",
					"std" => "",
					"type" => "color");
					
///////////////////////////////////////////////////////////////////////////////SIDEBAR OPTIONS

$of_options[] = array( "name" => __('Sidebar Options', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => "Sidebar Info",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Sidebar Info','siiimple')."</h3>
						".__('', 'siiimple')."The sidebar is mostly a collection of widgets that are controlled in the Widgets area.  You can add or delete any widgets by going to Appearance > Widgets and controlling each widget there.  However, you also have a few extra options that you can see below.  The Search area can be added.  Also, the social media options below.",
						"icon" => true,
						"type" => "info"); 
					
$of_options[] = array( "name" => __('Enable or Disable Search in Sidebar', 'siiimple'),
					"desc" => __('Select to enable/disable the search in sidebar.', 'siiimple'),
					"id" => "disable_search",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Enable or Disable Social Media Area', 'siiimple'),
					"desc" => __('Select to enable/disable the social media in sidebar.', 'siiimple'),
					"id" => "disable_social",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Enable or Disable Facebook Social Area', 'siiimple'),
					"desc" => __('Select to enable/disable the social media in sidebar.', 'siiimple'),
					"id" => "disable_facebook_sidebar",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
					
$of_options[] = array( "name" => __('Facebook Text', 'siiimple'),
					"desc" => __('Text for Facebook Social Media (Home Page)', 'siiimple'),
					"id" => "facebook_text",
					"std" => "Like us on Facebook",
					"type" => "text");
					
$of_options[] = array( "name" => __('Facebook ID', 'siiimple'),
					"desc" => __('Text for Facebook Social Media ID (Home Page).  It will be a number like this:  174221319304233', 'siiimple'),
					"id" => "facebook_id",
					"std" => "174221319304233",
					"type" => "text");
					
$of_options[] = array( "name" => __('Facebook URL', 'siiimple'),
					"desc" => __('Add your Facebook url here. (Home Page)', 'siiimple'),
					"id" => "facebook_link",
					"std" => "",
					"type" => "text");
					
$of_options[] = array( "name" => __('Twitter Text', 'siiimple'),
					"desc" => __('Text for Twitter Social Media (Home Page)', 'siiimple'),
					"id" => "twitter_text",
					"std" => "Follow us on Twitter",
					"type" => "text");
					
$of_options[] = array( "name" => __('Twitter Username', 'siiimple'),
					"desc" => __('Text for Twitter Username (Home Page)', 'siiimple'),
					"id" => "twitter_id",
					"std" => "Siiimple",
					"type" => "text");
					
$of_options[] = array( "name" => __('Twitter URL', 'siiimple'),
					"desc" => __('Link for twitter page (Home Page)', 'siiimple'),
					"id" => "twitter_link",
					"std" => "",
					"type" => "text");
					
$of_options[] = array( "name" => __('RSS Text', 'siiimple'),
					"desc" => __('Text for RSS Social Media (Home Page)', 'siiimple'),
					"id" => "rss_text",
					"std" => "Join our RSS Feed",
					"type" => "text");
					
$of_options[] = array( "name" => __('RSS URL', 'siiimple'),
					"desc" => __('Add your rss feed here. (Home Page).  Example:  It will look something like this http://www.[your site address here].com/feed.  Fairly simple.', 'siiimple'),
					"id" => "rss_link",
					"std" => "",
					"type" => "text");
					
				 

///////////////////////////////////////////////////////////////////////////////TICKER

$of_options[] = array( "name" => __('News Ticker', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Enable or Disable Ticker', 'siiimple'),
					"desc" => __('Select to enable/disable the news ticker', 'siiimple'),
					"id" => "disable_ticker",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Breaking News Text', 'siiimple'),
					"desc" => __('Paste your link to the ad display (header section only)', 'siiimple'),
					"id" => "breaking_news",
					"std" => "Breaking News",
					"type" => "text");
					
$of_options[] = array( "name" => __('Ticker Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display in the ticker.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "ticker",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Color of Ticker Titles', 'siiimple'),
					"desc" => __('Choose a color background for ticker titles.', 'siiimple'),
					"id" => "ticker_color",
					"std" => "#ff9100",
					"type" => "color");
					
///////////////////////////////////////////////////////////////////////////////BLOG TEMPLATE
					
$of_options[] = array( "name" => __('Blog Template', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => "Blog Template Info",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Blog Template Info','siiimple')."</h3>
						".__('', 'siiimple')."The Blog template is just another page template.  When you create a new page, choose Template Blog in the Page Template option area.  For the header text to show, simply add text in the content area.  You can choose your category below.  All posts will draw from this category.",
						"icon" => true,
						"type" => "info");
						
$of_options[] = array( "name" => __('Blog Category', 'siiimple'),
					"desc" => __('Choose your blog template category.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "blog_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
///////////////////////////////////////////////////////////////////////////////SECTION 1

$of_options[] = array( "name" => __('Section 1', 'siiimple'),
					"type" => "heading");	
					
$of_options[] = array( "name" => __('Show Left Sidebar Message', 'siiimple'),
					"desc" => __('Select to enable/disable the sidebar message here.', 'siiimple'),
					"id" => "disable_message",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Left Sidebar Header Introduction', 'siiimple'),
					"desc" => __('Add a short title to introduce your site here.', 'siiimple'),
					"id" => "left-sidebar-header-message",
					"std" => "UrbanNews is a premium WordPress magazine theme.",
					"type" => "text");
					
$of_options[] = array( "name" => __('Left Sidebar Text Introduction', 'siiimple'),
					"desc" => __('Add a short message to introduce your site here.', 'siiimple'),
					"id" => "left-sidebar-message",
					"std" => "Here you can add an introduction to your site or simply leave it blank.  Every section is packed with options and can be configured to suit your taste.",
					"type" => "textarea");
					
$of_options[] = array( "name" => __('Section 1 Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the popular post area.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "popular",
					"std" => "Section 1 Name Here",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Section 1 Number', 'siiimple'),
                    "desc" => __('How many posts in this section.', 'siiimple'),
                    "id" => "popular_num",
                    "std" => "3",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Enable or Disable Ratings', 'siiimple'),
					"desc" => __('Select to enable/disable ratings for this section', 'siiimple'),
					"id" => "disable_ratings",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);


///////////////////////////////////////////////////////////////////////////////SECTION2

$of_options[] = array( "name" => __('Section 2', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => "Section 2 Info",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Section 2 Info','siiimple')."</h3>
						".__('', 'siiimple')."Section 2 is comprised of four subsections:  Featured Gallery, Photo Carousel, Video Carousel, and the Quote Carousel.  You can refer to the demo or the documentation for reference of how it should appear.",
						"icon" => true,
						"type" => "info");
						
$of_options[] = array( "name" => "Featured Gallery",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Featured Gallery','siiimple')."</h3>
						".__('', 'siiimple')."Featured Gallery Options.",
						"icon" => true,
						"type" => "info");
					
$of_options[] = array( "name" => __('Featured Gallery', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the photo area.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "featured_gallery_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Featured Image Gallery Number', 'siiimple'),
                    "desc" => __('How many posts in this section.  Leave blank if you want them all to show.', 'siiimple'),
                    "id" => "featured_gallery_num",
                    "std" => "",
                    "type" => "text");
                    
/////////////////////////////////////////////////////////////////////////////// Photo Carousel   


$of_options[] = array( "name" => "Photo Carousel",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Photo Carousel','siiimple')."</h3>
						".__('', 'siiimple')."Photo Carousel Options.",
						"icon" => true,
						"type" => "info");        
						
$of_options[] = array( "name" => __('Enable or Disable Photo Carousel', 'siiimple'),
					"desc" => __('Select to enable/disable the photo carousel.', 'siiimple'),
					"id" => "disable_section_photo",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);	         
					
$of_options[] = array( "name" => __('Select Photo Carousel Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the photo area.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "photo_carousel",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);

$of_options[] = array( "name" => __('Photo Carousel Header', 'siiimple'),
                    "desc" => __('Name of this section.', 'siiimple'),
                    "id" => "photo_carousel_header",
                    "std" => "Latest Photos Section",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Photo Carousel Header Link', 'siiimple'),
                    "desc" => __('Add link to page for this section.', 'siiimple'),
                    "id" => "photo_carousel_header_link",
                    "std" => "",
                    "type" => "text");
					

					
/////////////////////////////////////////////////////////////////////////////// VIDEO Carousel 

$of_options[] = array( "name" => "Video Carousel",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Video Carousel','siiimple')."</h3>
						".__('', 'siiimple')."Video Carousel Options.  You can also show images in this section.",
						"icon" => true,
						"type" => "info"); 
						
$of_options[] = array( "name" => __('Enable or Disable Video/Photo Carousel', 'siiimple'),
					"desc" => __('Select to enable/disable the video/photo carousel.', 'siiimple'),
					"id" => "disable_section_video",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Select Featured Videos Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the featured videos.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "featured_videos",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Featured Videos Header', 'siiimple'),
                    "desc" => __('Name of this section.', 'siiimple'),
                    "id" => "featured_video_header",
                    "std" => "Featured Video",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Featured Videos Header Link', 'siiimple'),
                    "desc" => __('Add link to page for this section.', 'siiimple'),
                    "id" => "featured_video_header_link",
                    "std" => "Photos & Video Section",
                    "type" => "text");	
                    
$of_options[] = array( "name" => __('Featured Videos Number', 'siiimple'),
                    "desc" => __('How many posts in this section.', 'siiimple'),
                    "id" => "featured_video_num",
                    "std" => "",
                    "type" => "text");				
					
	
					
/////////////////////////////////////////////////////////////////////////QUOTE CAROUSEL

$of_options[] = array( "name" => "Quote Carousel",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Quote Carousel','siiimple')."</h3>
						".__('', 'siiimple')."Quote Carousel Options.",
						"icon" => true,
						"type" => "info"); 
						
						
$of_options[] = array( "name" => __('Enable or Disable Quote Carousel', 'siiimple'),
					"desc" => __('Select to enable/disable the quote carousel.', 'siiimple'),
					"id" => "disable_section_quote",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Select Quotes Slider Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the quotes.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "quote_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
                   
$of_options[] = array( "name" => __('How many quotes?', 'siiimple'),
                    "desc" => __('How many quotes do you want to show?', 'siiimple'),
                    "id" => "quote_num",
                    "std" => "3",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Quotes Header', 'siiimple'),
                    "desc" => __('Name of this section.', 'siiimple'),
                    "id" => "quotes_header",
                    "std" => "Popular Quotes",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Quotes Header Link', 'siiimple'),
                    "desc" => __('Add link to page for this section.', 'siiimple'),
                    "id" => "quotes_header_link",
                    "std" => "",
                    "type" => "text");
                    
					
				
                    
/////////////////////////////////////////////////////////////////////////SECTION 3

$of_options[] = array( "name" => __('Section 3', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Enable or Disable Section 3', 'siiimple'),
					"desc" => __('Select to enable/disable this section', 'siiimple'),
					"id" => "disable_section3",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Section 3 Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the carousel.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "section3_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Section 3 Header', 'siiimple'),
                    "desc" => __('Name of this section', 'siiimple'),
                    "id" => "section3_header",
                    "std" => "Section 3 Name Here",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Section 3 Link', 'siiimple'),
                    "desc" => __('Add link to page for this section.', 'siiimple'),
                    "id" => "section3_link",
                    "std" => "",
                    "type" => "text");
					

					
/////////////////////////////////////////////////////////////////////////SECTION 4

$of_options[] = array( "name" => __('Section 4', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Enable or Disable Section 4', 'siiimple'),
					"desc" => __('Select to enable/disable this section', 'siiimple'),
					"id" => "disable_section4",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Section 4 Header', 'siiimple'),
                    "desc" => __('Name of this section', 'siiimple'),
                    "id" => "section7_header",
                    "std" => "Section 4 Name Here",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Section 4 Link', 'siiimple'),
                    "desc" => __('Add link to page for this section.', 'siiimple'),
                    "id" => "section7_link",
                    "std" => "",
                    "type" => "text");

                    
$of_options[] = array( "name" => __('Section 4 Category', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the carousel.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "section7_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
///////////////////////////////////////////////////////////////////////////////SECTION5

$of_options[] = array( "name" => __('Section 5', 'siiimple'),
					"type" => "heading");
				
					
$of_options[] = array( "name" => "Secondary Featured Gallery",
						"desc" => "",
						"id" => "menu-options",
						"std" => "<h3 style=\"margin: 0 0 10px;\">".__('Secondary Featured Gallery','siiimple')."</h3>
						".__('', 'siiimple')."Featured Gallery Options.",
						"icon" => true,
						"type" => "info");
						
$of_options[] = array( "name" => __('Enable or Disable Section 5', 'siiimple'),
					"desc" => __('Select to enable/disable this section', 'siiimple'),
					"id" => "disable_section5",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
						
$of_options[] = array( "name" => __('Section 5 Header', 'siiimple'),
                    "desc" => __('Name of this section.', 'siiimple'),
                    "id" => "section5_header",
                    "std" => "Section 5 Name Here",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Section 5 Header Link', 'siiimple'),
                    "desc" => __('Name of this section.', 'siiimple'),
                    "id" => "section5_link",
                    "std" => "",
                    "type" => "text");
					
$of_options[] = array( "name" => __('Secondary Featured Gallery', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the photo area.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "section5_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
$of_options[] = array( "name" => __('Secondary Gallery Number', 'siiimple'),
                    "desc" => __('How many posts in this section.  Leave blank if you want them all to show.', 'siiimple'),
                    "id" => "section5_num",
                    "std" => "",
                    "type" => "text");
                                        
/////////////////////////////////////////////////////////////////////////SECTION 6
                    

$of_options[] = array( "name" => __('Section 6', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Enable or Disable Section 6', 'siiimple'),
					"desc" => __('Select to enable/disable this section', 'siiimple'),
					"id" => "disable_section6",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Section 6 Header', 'siiimple'),
                    "desc" => __('Name of this section.', 'siiimple'),
                    "id" => "section6_header",
                    "std" => "Section 6 Name Here",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('Section 6 Link', 'siiimple'),
                    "desc" => __('Add link to page for this section.', 'siiimple'),
                    "id" => "section6_link",
                    "std" => "",
                    "type" => "text");
					
$of_options[] = array( "name" => __('How many items', 'siiimple'),
                    "desc" => __('How many carousel items do you want to show?', 'siiimple'),
                    "id" => "section6_num",
                    "std" => "",
                    "type" => "text");
             
                    
$of_options[] = array( "name" => __('Choose category for Section 5', 'siiimple'),
					"desc" => __('Choose the category you want to use to display the carousel.  To show all categories choose the top (blank) option.', 'siiimple'),
					"id" => "section6_cat",
					"std" => "",
					"type" => "select",
					"options" => $of_categories);
					
/////////////////////////////////////////////////////////////////////////SOCIAL

$of_options[] = array( "name" => __('Social', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Share Post Text', 'siiimple'),
                    "desc" => __('Change name of the wording before the share options.', 'siiimple'),
                    "id" => "share_text",
                    "std" => "Share Post",
                    "type" => "text");
					
$of_options[] = array( "name" => __('Share Bar', 'siiimple'),
					"desc" => __('Select to enable/disable entire share bar at bottom of posts.', 'siiimple'),
					"id" => "disable_share",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Share Twitter', 'siiimple'),
					"desc" => __('Select to enable/disable the twitter share button at end of posts.', 'siiimple'),
					"id" => "disable_share_twitter",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Share Digg', 'siiimple'),
					"desc" => __('Select to enable/disable the Digg share button at end of posts.', 'siiimple'),
					"id" => "disable_share_digg",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Share LinkedIn', 'siiimple'),
					"desc" => __('Select to enable/disable the LinkedIn share button at end of posts.', 'siiimple'),
					"id" => "disable_share_linkedin",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Share Facebook', 'siiimple'),
					"desc" => __('Select to enable/disable the Facebook share button at end of posts.', 'siiimple'),
					"id" => "disable_share_facebook",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('Share Comments Number', 'siiimple'),
					"desc" => __('Select to enable/disable the Comments number at end of posts.', 'siiimple'),
					"id" => "disable_share_comments",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
                    
/////////////////////////////////////////////////////////////////////////SEO
                   
$of_options[] = array( "name" => __('SEO', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Apple Touch Icon 57x57', 'siiimple'),
					"desc" => __('Upload or past the URL for your Apple Touch Icon.  There are three sizes to upload.  This size should be 57x57 of your logo.  This will help SEO value.  <span style="color:red">*Important:  The name of this image should be "apple-touch-icon.png"</span>', 'siiimple'),
					"id" => "custom_apple_touch_icon_1",
					"std" => "",
					"type" => "upload");
					
$of_options[] = array( "name" => __('Apple Touch Icon 72x72', 'siiimple'),
					"desc" => __('Upload or past the URL for your Apple Touch Icon.  There are three sizes to upload.  This size should be 72x72 of your logo.  This will help SEO value.  <span style="color:red">*Important:  The name of this image should be "apple-touch-icon-72x72.png"</span>', 'siiimple'),
					"id" => "custom_apple_touch_icon_2",
					"std" => "",
					"type" => "upload");
					 
$of_options[] = array( "name" => __('Apple Touch Icon 114x114', 'siiimple'),
					"desc" => __('Upload or past the URL for your Apple Touch Icon.  There are three sizes to upload.  This size should be 114x114 of your logo.  This will help SEO value.  <span style="color:red">*Important:  The name of this image should be "apple-touch-icon-114x114.png"</span>', 'siiimple'),
					"id" => "custom_apple_touch_icon_3",
					"std" => "",
					"type" => "upload");
					
$of_options[] = array( "name" => __('Meta Description', 'siiimple'),
                    "desc" => __('Add a meta description.  This will appear in your header for better SEO value.  Be carefully descriptive.', 'siiimple'),
                    "id" => "meta-desc",
                    "std" => "",
                    "type" => "textarea");
                    
$of_options[] = array( "name" => __('Meta Keywords', 'siiimple'),
                    "desc" => __('Add a meta keywords.  This will appear in your header for better SEO value.  Provide a list of keywords that will allow your site to attract better SEO value.', 'siiimple'),
                    "id" => "meta-key",
                    "std" => "Business, Theme, ThemeForest, Minimalist, Minimal, Premium Business, Corporate, Clean",
                    "type" => "textarea");
                    
/////////////////////////////////////////////////////////////////////////404
					
$of_options[] = array( "name" => __('Error Page', 'siiimple'),
					"type" => "heading");
                    
$of_options[] = array( "name" => __('Add an Image', 'siiimple'),
					"desc" => __('Upload your image.', 'siiimple'),
					"id" => "404_img",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => __('404 Header', 'siiimple'),
                    "desc" => __('Add some custom 404 header.', 'siiimple'),
                    "id" => "404_header",
                    "std" => "",
                    "type" => "text");
					
$of_options[] = array( "name" => __('404 Text', 'siiimple'),
                    "desc" => __('Add some custom 404 text.', 'siiimple'),
                    "id" => "404_text",
                    "std" => "",
                    "type" => "textarea");

/////////////////////////////////////////////////////////////////////////COUNTDOWN
					
$of_options[] = array( "name" => __('Under Construction', 'siiimple'),
					"type" => "heading");                    
                    
$of_options[] = array( "name" => __('Enable or Disable Countdown Script', 'siiimple'),
					"desc" => __('Select to enable/disable countdown script.  You will want this enabled if you are referring your page to a 503.php page, if your site is under construction.', 'siiimple'),
					"id" => "disable_countdown",
					"std" => "enable",
					"type" => "select",
					"options" => $enable_disable);
					
$of_options[] = array( "name" => __('503 Header', 'siiimple'),
                    "desc" => __('Add some custom 503 header.', 'siiimple'),
                    "id" => "503_header",
                    "std" => "",
                    "type" => "text");
					
$of_options[] = array( "name" => __('503 Text', 'siiimple'),
                    "desc" => __('Add some custom 503 text.', 'siiimple'),
                    "id" => "503_text",
                    "std" => "",
                    "type" => "textarea");
                    
$of_options[] = array( "name" => __('503 Year', 'siiimple'),
                    "desc" => __('Add the year of the timer. Use numbers.  Example: 2012', 'siiimple'),
                    "id" => "503_year",
                    "std" => "",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('503 Month', 'siiimple'),
                    "desc" => __('Add the month of the timer. Use numbers.  Example: July = 7', 'siiimple'),
                    "id" => "503_month",
                    "std" => "",
                    "type" => "text");
                    
$of_options[] = array( "name" => __('503 Day', 'siiimple'),
                    "desc" => __('Add the day of the timer.  Use numbers.  Example: 4', 'siiimple'),
                    "id" => "503_day",
                    "std" => "",
                    "type" => "text");
                   
					
/////////////////////////////////////////////////////////////////////////FOOTER
					
$of_options[] = array( "name" => __('Footer', 'siiimple'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Footer Advert', 'siiimple'),
					"desc" => __('Upload your footer ad here.', 'siiimple'),
					"id" => "footer_ad",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => __('Footer Ad Link', 'siiimple'),
                    "desc" => __('Add your footer ad link.', 'siiimple'),
                    "id" => "footer_ad_link",
                    "std" => "",
                    "type" => "textarea");
					
$of_options[] = array( "name" => __('Custom Copyright', 'siiimple'),
                    "desc" => __('Add your own custom text/html for copyright region.', 'siiimple'),
                    "id" => "footer_left",
                    "std" => "Some Text Here. &copy; 2012 UrbanNews",
                    "type" => "textarea");
                    
$of_options[] = array( "name" => __('Tracking Code (Footer)', 'siiimple'),
					"desc" => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'siiimple'),
					"id" => "tracking_footer",
					"std" => "",
					"type" => "textarea");
                    
/////////////////////////////////////////////////////////////////////////BACKUP OPTIONS

$of_options[] = array( "name" => "Backup Options",
					"type" => "heading");
					
$of_options[] = array( "name" => "Backup and Restore Options",
                    "id" => "of_backup",
                    "std" => "",
                    "type" => "backup",
					"desc" => 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
					);
					
$of_options[] = array( "name" => "Transfer Theme Options Data",
                    "id" => "of_transfer",
                    "std" => "",
                    "type" => "transfer",
					"desc" => 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".
						',
					);
					
	}
}
?>