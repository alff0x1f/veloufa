<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'siiimple_';

global $meta_boxes;

$meta_boxes = array();

// LINK TO PAGE
$meta_boxes[] = array(
	'id' => 'link-to-page',
	'title' =>  __('Link to Page', 'siiimple'),
	'pages' => array('portfolio','gallery'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('Link to Page','siiimple'),
				"desc" => __('Add a link to your post entry.','siiimple'),
				"id" => $prefix."link_page",
				"type" => "text"
			)
	),
	
);

// LINK TO PAGE
$meta_boxes[] = array(
	'id' => 'mid-section',
	'title' =>  __('Template Page w/ Middle Section', 'siiimple'),
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		
		array( "name" => __('Mid Section Category','siiimple'),
				"desc" => __('If you have chosen the "Template Page w/ Middle Section" - you can add which category you want to show.','siiimple'),
				"id" => $prefix."mid_cat",
				"type" => "text"
			),
		
		array( "name" => __('Mid Section Number','siiimple'),
				"desc" => __('If you have chosen the "Template Page w/ Middle Section" - you can add how many you want to show.  Ex: 12','siiimple'),
				"id" => $prefix."mid_cat_num",
				"type" => "text"
			),
			
	),
	
);

// LINK TO PAGE
$meta_boxes[] = array(
	'id' => 'tab-category',
	'title' =>  __('Tab Template ONLY', 'siiimple'),
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		
		array( "name" => __('Tab Category','siiimple'),
				"desc" => __('If you have chosen the "Template Tabs" - you can add which category you want to show.','siiimple'),
				"id" => $prefix."tab_cat",
				"type" => "text"
			),
			
		array( "name" => __('Tab Category Number','siiimple'),
				"desc" => __('If you have chosen the "Template Tabs" - you can add how many posts you wish to show.  Just write -1 if you want to show all the posts or leave blank','siiimple'),
				"id" => $prefix."tab_cat_num",
				"type" => "text"
			),
			
	),
	
);

// CATEGORY
$meta_boxes[] = array(
	'id' => 'link-to-category',
	'title' =>  __('Gallery Options (For Gallery Templates Only)', 'siiimple'),
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('Gallery Category','siiimple'),
				"desc" => __('Type in the category that you want this page template to draw from.  Example:  If you have a category named "General", type in "General".  You can also use the slug.  Example:  If you have a category named "Great Photos", you can use the slug "great-photos".','siiimple'),
				"id" => $prefix."page_cat",
				"type" => "text"
			),
			
		array( "name" => __('Gallery Number','siiimple'),
				"desc" => __('Number of Gallery Items to show in Gallery on a page.  To show all images/posts within a gallery, simply write in "-1".','siiimple'),
				"id" => $prefix."gallery_number",
				"type" => "text"
			),
			
			array( "name" => __('Subtext for Gallery','siiimple'),
				"desc" => __('Add subtext to the area beneath the photo to either introduce your story or highlight an element of it.','siiimple'),
				"id" => $prefix."subtext",
				"type" => "textarea"
			),
			
			array(
            'name' => __('Show Video (Top of Page)','siiimple'),
            'desc' => __('Add a link to your video here.  This will show a video at top of page.  It will override the featured image, if you have one in place.  Be sure to use the url of the EMBED SRC URL - not the url to the video itself. <br/><br/> Example:  http://player.vimeo.com/video/41462515?title=0&amp;portrait=0&amp;color=bd0000','siiimple'),
            'id' => $prefix .'video_main',
            'type' => 'text',
            'std' => ''
        ),
        
        array(
			'name' => 'Slideshow (Top of Page)',
			'id'   => "{$prefix}page_gallery",
			'type' => 'checkbox',
			'desc' => 'Show slider as main image for this page.  To do this, add images to the Featured Image area.  Once you add multiple images to the Featured Image area, the slider will be activated at top of page (be sure to have this checked).',
			// Value can be 0 or 1
			'std' => 0,
		),
		
		
	),
	
);

// CATEGORY
$meta_boxes[] = array(
	'id' => 'basic-page-options',
	'title' =>  __('Page Options (For Pages Only)', 'siiimple'),
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
			
			array( "name" => __('Subtext for Page','siiimple'),
				"desc" => __('Add subtext to the area beneath the photo to either introduce your story or highlight an element of it.','siiimple'),
				"id" => $prefix."subtext_page",
				"type" => "textarea"
			),
			
			array(
            'name' => __('Show Video (Top of Page)','siiimple'),
            'desc' => __('Add a link to your video here.  This will show a video at top of page.  It will override the featured image, if you have one in place.  Be sure to use the url of the EMBED SRC URL - not the url to the video itself. <br/><br/> Example:  http://player.vimeo.com/video/41462515?title=0&amp;portrait=0&amp;color=bd0000','siiimple'),
            'id' => $prefix .'video_page',
            'type' => 'text',
            'std' => ''
        ),
        
        array(
			'name' => 'Slideshow (Top of Page)',
			'id'   => "{$prefix}basic_page_gallery",
			'type' => 'checkbox',
			'desc' => 'Show slider as main image for this page.  To do this, add images to the Featured Image area.  Once you add multiple images to the Featured Image area, the slider will be activated at top of page (be sure to have this checked).',
			// Value can be 0 or 1
			'std' => 0,
		),
		
		
	),
	
);

$meta_boxes[] = array(
	'id' => 'single-sub-text',
	'title' =>  __('Post Options', 'siiimple'),
	'pages' => array('post'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('Single Subtext','siiimple'),
				"desc" => __('Add subtext to the area beneath the photo to either introduce your story or highlight an element of it.','siiimple'),
				"id" => $prefix."subtext",
				"type" => "textarea"
			),
		array(
			'name' => 'Side Image',
			'desc' => 'This image appears on left side of content area.',
			"id" => $prefix."sidebar_img",
			'type' => 'image',
		),
		array(
            'name' => __('Add Video','siiimple'),
            'desc' => __('Add a link to your video here.  This will appear in the FancyBox overlay.  It will not show as the initial image - but when clicked, will show at a popup.  Be sure to use the url of the EMBED SRC URL - not the url to the video itself. <br/><br/> Example:  http://player.vimeo.com/video/41462515?title=0&amp;portrait=0&amp;color=bd0000','siiimple'),
            'id' => $prefix .'video',
            'type' => 'text',
            'std' => ''
        ),
	),
	
);


/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function YOUR_PREFIX_register_meta_boxes()
{
	global $meta_boxes;

	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'YOUR_PREFIX_register_meta_boxes' );