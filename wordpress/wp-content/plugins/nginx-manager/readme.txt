=== Plugin Name ===
Contributors: hpatoio, rukbat
Tags: cache, nginx, purge, performance
Requires at least: 3.0
Tested up to: 3.9
Stable tag: trunk

Easily purge Nginx cache. Each time a post is modified clear the cached version of the page and of all the related page.

== Description ==

Each time an element of your blog is modified (post, page, media, custom post) its cached version, and of all the related elements (categories, archives and custom taxonomies) are deleted.

In this way your cache has always the latest version of the page.

Extra : 

- Two extra HTTP header are added to the response so that you can see if the page you are viewing is cached and when it was cached.
- A logging system allow you to see which page were cached and when.
- You can add personal URLs to be purged when the post is modified
- Purge of future posts is handled with an external script.
- Integration with NextGEN Gallery plugin.

Credits :

- This plugin is based on the structure of famous NextGEN Gallery plugin.


== Installation ==

*IMPORTANT*

If you are using a proxy you must modify the file wp-includes/class-http.php and delete (or comment out) lines 1346-1347 (WP 3.3) in function send_through_proxy():
			
if ( $check['host'] == 'localhost' || $check['host'] == $home['host'] )
	return false;
	
-------

1. Upload the directory `nginx-manager` to the `/wp-content/plugins/` directory
2. Configure Nginx. Install Cache Purge module http://labs.frickle.com/nginx_ngx_cache_purge/ and configure the cache
3. Activate the plugin through the 'Plugins' menu in WordPress

For questions or support post a comment here http://www.iliveinperego.com/2010/10/nginx-automatically-cache-purge/

= Scheduled posts script =

To avoid problems with scheduled posts you must set a cron job that i

1. Edit /script/future_posts_cron.php and set $_SERVER['HTTP_HOST'] to you host and ABSPATH_SCRIPT to your Wordpress installation path
2. Add a job in your crontab to schedule the script to run every minute

   For example : 
   
`   * * * * * /usr/local/bin/php /var/documentroot/wp-content/plugins/nginx-manager/script/future_posts_cron.php`

== Screenshots ==

1. Overview
2. Configuration page 
3. Log

== Changelog ==

= 1.3.4.4 (2013 01 21) =
* BUG : Remove old plugin option "custom_post_types_recognized" for future post script (thx retroriff)

= 1.3.4.3 (2012 06 19) =
* EDIT : Purge (homepage) when widgets are saved or dragged to a sidebar (thx David Scatigna & metalitalia.com crew)
* NEW : Purge post on post transition status (thx David Scatigna & metalitalia.com crew)

= 1.3.4.2 (2012 02 29) =
* BUG : Fixed cache for feed pages

= 1.3.4.1 (2012 01 04) =
* BUG : Fixed mobile regex

= 1.3.4 (2011 12 27) =
* BUG : Now PurgeUrl also consider the query_string
* NEW : Added an hook to run a function when a NGG gallery is purged. Hook name nm_ngg_gallery_purge
* NEW : Added options to choose if purge if user agent is from a mobile device and edit the regex.
* EDIT : CPT identification by options, not automatically

= 1.3.3 (2011 09 20) =
* NEW : If the request is from a mobile device disable cache
* NEW : NextGEN Gallery integration: Purge post that contains the image/thumb gallery edited

= 1.3.2 (2011 06 21) =
* NEW : Purge (homepage) when widgets are dragged to a sidebar
* NEW : Purge post type archive link
* BUG : Fixed few small bugs and typos

= 1.3.1 (2011 03 24) =
* NEW : Purge (homepage) when a term taxonomy is edited or deleted
* BUG : Default options updated with values for purge on post deleting

= 1.3 (2011 03 16) =
* NEW : Purge when a post is moved to the trash: you can choose between purge by options or purge all the blog
* BUG : Fixed purge of comments: do not purge when status is set to 'delete'

= 1.2.2 (2011 01 24) =
* BUG : Fixed purge when comments are approved/trashed/etc
* EDIT : Log file beautified (more readable)

= 1.2.1 (2011 01 19) =
* EDIT : Set default options when a new blog is installed

= 1.2 (2010 12 28) =
* NEW : You can now purge custom post types and tag archives
* NEW : When you purge a post also all related custom taxonomies are purged (if enabled in the options)
* NEW : Add constant in Overview / Debug
* NEW : Versioning manager
* EDIT : All other plugin class/functions moved in /integration folder
* EDIT : Network activation only (if multisite)
* EDIT : start_plugin() is now hooked with 'init' (priority 15)
* EDIT : Purge by options now get URL via wp functions and not by urls saved in options
* BUG : Purge comment via admin panel fixed
* BUG : Fixed NGG purge functions
* BUG : Fixed incorrect image URL retrieving in purgeOnMediaEdit() (thx Jean-Paul Horn)
* BUG : Fixed few small bugs and typos

= 1.1.1 (2010 12 15) =
* BUG : Fixed options for single site installation (thx Jean-Paul Horn)

= 1.1 (2010 11 10) =
* BUG : Fixed options to manage different blogs data in a multisite installation
* NEW : You can now purge personal URLs
* NEW : Maximum size of log file is controlled by a scheduled event every day
* NEW : Added a script to purge in case of future post and force a remote get. This script must be included in an external cron.
* NEW : Added functionality to purge a thumb when it's edited in Manage Gallery of Nextgen Gallery plugin. (You need to add 'do_action('ngg_ajax_UpdateThumb', $picture->thumbURL);' in createNewThumb function in nextgen-gallery/admin/ajax.php after this line 'if ( $thumb->save($picture->thumbPath, 100)) {', about line 128) 
* BUG : Fixed few small bugs and typos

= 1.0 (2010 10 14) =
* Switching to first major release after 10 days of testing on a production environment.

= 0.3 (2010 10 04) =
* BUG : Fixed few small bugs and typos
* NEW : When you purge a post also all related media are purged (if enabled in the options)
* NEW : You can now truncate the log
* Updated documentation

= 0.2 (2010 10 01) =
* BUG : Fixed few small bugs and typos