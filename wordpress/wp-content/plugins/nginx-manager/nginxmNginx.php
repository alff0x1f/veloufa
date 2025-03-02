<?php 

if (!class_exists('nginxmNginx')) {
	
	class nginxmNginx {
		
		function correctExpires($http_headers) {
			
			global $nginxm;
			
			$cache_ttl = $nginxm->global_options['cache_ttl'];
			
			// If the user is logged in
			foreach($_COOKIE as $key => $value){
				if ( preg_match($nginxm->global_options['cookie_regexp'], $key) ) {
					$cache_ttl = 0;
				}
			}
			
			// If the request is from a mobile device disable cache
			if ( $nginxm->global_options['mobile_uncache'] ) {
				$_mobile_regex = ( isset($nginxm->global_options['mobile_regexp']) && ($nginxm->global_options['mobile_regexp'] != '' ) ) ? $nginxm->global_options['mobile_regexp'] : '#2.0 MMP|240x320|400X240|AvantGo|BlackBerry|Blazer|Cellphone|Danger|DoCoMo|Elaine/3.0|EudoraWeb|Googlebot-Mobile|hiptop|IEMobile|KYOCERA/WX310K|LG/U990|MIDP-2.|MMEF20|MOT-V|NetFront|Newt|Nintendo Wii|Nitro|Nokia|Opera Mini|Palm|PlayStation Portable|portalmmm|Proxinet|ProxiNet|SHARP-TQ-GX10|SHG-i900|Small|SonyEricsson|Symbian OS|SymbianOS|TS21i-10|UP.Browser|UP.Link|webOS|Windows CE|WinWAP|YahooSeeker/M1A1-R2D2|NF-Browser|iPhone|iPod|Android|BlackBerry9530|G-TU915 Obigo|LGE VX|webOS|Nokia5800#';
				if ( preg_match( $_mobile_regex, $_SERVER['HTTP_USER_AGENT'] ) ) {
					$cache_ttl = 0;
				}
			}
			
			$http_headers['X-Accel-Expires'] = $cache_ttl;
			
			if ($cache_ttl == 0) {
				
				$http_headers['X-NGINX-CACHED']  = "NO";
				
			} else {
				
				$http_headers['X-NGINX-CACHED']  	= "YES - ".$cache_ttl." secs";
				$http_headers['X-NGINX-CACHED-AT']  = date('c');
				
			}
			
			return $http_headers;
			
		}
		
		/**
		 * Runs when a comment is saved in the database or when the status of the comment changes.
		 * Retrieves post id and call purgePost().
		 * 
		 * @param $_comment_id
		 * @param $_comment_status
		 * @since Nginx Manager 1.2.2
		 */
		function purgePostOnComment( $_comment_id, $_comment_status = '' ) {
			
			global $blog_id;
			
			if ( $_comment_status != 'delete' ) {
				
				// Retrieves post ID.
				$_comment = get_comment( $_comment_id );
				$_post_id = $_comment->comment_post_ID;
				
				$this->log( "* * * * *" );
				$this->log( "* Blog :: ".addslashes( get_bloginfo('name') )." ($blog_id)." );
				$this->log( "* Post :: ".get_the_title($_post_id)." ($_post_id)." );
				$this->log( "* Comment :: $_comment_id." );
				
				switch ( current_filter() ) {
					case 'comment_post':
						$this->log( "* New comment ($_comment_id) saved in the database. Let's purge the post ($_post_id)." );
						break;
					case 'wp_set_comment_status':
						$this->log( "* Comment ($_comment_id) set to status '$_comment_status'. Let's purge the post ($_post_id)." );
						break;
				}
				
				$this->log( "* * * * *" );
				
				// Calls purgePost().
				$this->purgePost( $_post_id );
				
			}
			
		}

		function purgePost($_ID) {
			
			global $nginxm, $blog_id;
			
			switch ( current_filter() ) {
				case 'publish_post':
					$this->log( "* * * * *" );
					$this->log( "* Blog :: ".addslashes( get_bloginfo('name') )." ($blog_id)." );
					$this->log( "* Post :: ".get_the_title($_ID)." ($_ID)." );
					$this->log( "* Post ($_ID) published or edited and its status is published" );
					$this->log( "* * * * *" );
					break;
				case 'publish_page':
					$this->log( "* * * * *" );
					$this->log( "* Blog :: ".addslashes( get_bloginfo('name') )." ($blog_id)." );
					$this->log( "* Page :: ".get_the_title($_ID)." ($_ID)." );
					$this->log( "* Page ($_ID) published or edited and its status is published" );
					$this->log( "* * * * *" );
					break;
				case 'comment_post':
				case 'wp_set_comment_status':
					break;
				default:
					// It's a custom post type.
					$_post_type = get_post_type($_ID);
					$this->log( "* * * * *" );
					$this->log( "* Blog :: ".addslashes( get_bloginfo('name') )." ($blog_id)." );
					$this->log( "* Custom post type '$_post_type' :: ".get_the_title($_ID)." ($_ID)." );
					$this->log( "* CPT '$_post_type' ($_ID) published or edited and its status is published" );
					$this->log( "* * * * *" );
					break;
			}
			
			$this->log( "Function purgePost BEGIN ===" );
			
			/* Homepage */
			if ($nginxm->options['automatically_purge_homepage'] == 1) {
				
				$homepage_url = trailingslashit(get_option('siteurl'));
				
				$this->log( "Purging homepage '$homepage_url'" );
				$this->purgeUrl($homepage_url);
				
			}
			
			/* Personal URLs */
			if ($nginxm->options['automatically_purge_purls'] == 1) {
				
				$this->log( "Purging personal urls" );
				
				if (isset($nginxm->options['purgeable_url']['urls'])) {
					foreach ($nginxm->options['purgeable_url']['urls'] as $u) {
						$this->purgeUrl($u, false);
					}
				} else {
					$this->log( "- ".__( "No personal urls available", "nginxm" ) );
				}
			}
			
			/* Post or comment */
			if ( current_filter() == 'comment_post' || current_filter() == 'wp_set_comment_status' ) {
				
				/* When a comment is published/edited */
				$this->_purge_by_options($_ID, $blog_id, $nginxm->options['automatically_purge_comment_page'], $nginxm->options['automatically_purge_comment_archive'], $nginxm->options['automatically_purge_comment_custom_taxa']);
				
			} else {
				
				/* When a post/page/custom post type is modified */
				$this->_purge_by_options($_ID, $blog_id, $nginxm->options['automatically_purge_page'], $nginxm->options['automatically_purge_page_archive'], $nginxm->options['automatically_purge_page_custom_taxa']);
			}
			
			$this->log( "Function purgePost END ^^^" );
			
		}
		
		
		private function _purge_by_options($_post_ID, $blog_id, $_purge_page, $_purge_archive, $_purge_custom_taxa) {
			
			global $nginxm;
			
			$_post_type = get_post_type( $_post_ID );
			
			/* Purge cache for the related page/post */
			if ($_purge_page) {
				
				if ( $_post_type == 'post' || $_post_type == 'page' ) {
					$this->log( "Purging $_post_type (id $_post_ID, blog id $blog_id)" );
				} else {
					$this->log( "Purging custom post type '$_post_type' (id $_post_ID, blog id $blog_id)" );
				}
				
				$this->purgeUrl( get_permalink( $_post_ID ) );
			}
			
			/* Purge cache for archive related to the page (author, category, date) */
			if ($_purge_archive) {
				
				/* custom post type archive, @since Nginx Manager 1.3.2 */
				if ( function_exists( 'get_post_type_archive_link' ) && ( $_post_type_archive_link = get_post_type_archive_link( $_post_type ) ) ) {
					$this->log( "Purging post type archive (".$_post_type.")" );
					$this->purgeUrl( $_post_type_archive_link );
				}
				
				/* date */
				if ( $_post_type == 'post' ) {
					
					$this->log( "Purging date" );
					
					$day 	= get_the_time('d', $_post_ID);
					$month 	= get_the_time('m', $_post_ID);
					$year 	= get_the_time('Y', $_post_ID);
					
					if ( $year ) {
						$this->purgeUrl( get_year_link( $year ) );
						if ( $month ) {
							$this->purgeUrl( get_month_link( $year, $month ) );
							if ( $day )
								$this->purgeUrl( get_day_link( $year, $month, $day ) );
						}
					}
				}
				
				/* category */
				if ( $categories = wp_get_post_categories( $_post_ID ) ) {
					$this->log( "Purging category archives" );
					foreach ( $categories as $category_id ) {
						$this->log( "Purging category ".$category_id );
						$this->purgeUrl( get_category_link( $category_id ) );
					}
				}
				
				/* tags, @since Nginx Manager 1.2 */
				if ( $tags = get_the_tags( $_post_ID ) ) {
					$this->log( "Purging tag archives" );
					foreach ( $tags as $tag ) {
						$this->log( "Purging tag ".$tag->term_id );
						$this->purgeUrl( get_tag_link( $tag->term_id ) );
					}
				}
				
				/* author */
				if ( $author_id = get_post($_post_ID)->post_author ) {
					$this->log( "Purging author archive" );
					$this->purgeUrl( get_author_posts_url( $author_id ) );
				}
				
			}
			
			/* Purge related custom terms, @since Nginx Manager 1.2 */
			if ( $_purge_custom_taxa ) {
				if ( $custom_taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ) ) ) {
					$this->log( "Purging custom taxonomies related" );
					foreach ( $custom_taxonomies as $taxon ) {
						// Extra check to get rid of the standard taxonomies
						if ( !in_array( $taxon, array( 'category', 'post_tag', 'link_category' ) ) ) {
							if ( $terms = get_the_terms( $_post_ID, $taxon ) ) {
								foreach ( $terms as $term ) {
									$this->purgeUrl( get_term_link( $term, $taxon ) );
								}
							}
						} else {
							$this->log( "Your built-in taxonomy '".$taxon."' has param '_builtin' set to false.", "WARNING" );
						}
					}
				}
			}
			
		}
		
		/**
		 * Purge the URL, if 'feed' then purge related feed page also.
		 * Used in the external script to purge the homepage.
		 * 
		 * @param $url
		 * @param $feed
		 * @return unknown_type
		 */
		function purgeUrl($url, $feed = true) {
			
			$this->log( "- Purging URL | ".$url );
			
			$parse 		= parse_url($url);
			
			$_url_purge = $parse['scheme'].'://'.$parse['host'].'/purge'.$parse['path'];
			if ( isset($parse['query']) && $parse['query'] != '' ) {
				$_url_purge .= '?'.$parse['query'];
			}
			
			$this->_do_remote_get( $_url_purge );
			
			if ($feed) {
				$feed_string = (substr($parse['path'], -1) != '/') ? "/feed/" : "feed/";
				$this->_do_remote_get($parse['scheme'].'://'.$parse['host'].'/purge'.$parse['path'].$feed_string);
			}
			
		}
		
		private function _do_remote_get($url) {
			
			$response = wp_remote_get($url);
			
			if( is_wp_error( $response ) ) {
				$_errors_str = implode(" - ",$response->get_error_messages());
				$this->log( "Error while purging URL. ".$_errors_str, "ERROR" );
			} else {
				if ( $response['response']['code'] ) {
					switch ( $response['response']['code'] ) {
						case 200:
							$this->log( "- - ".$url." *** PURGED ***" );
							break;
						case 404:
							$this->log( "- - ".$url." is currently not cached" );
							break;
						default:
							$this->log( "- - ".$url." not found (".$response['response']['code'].")", "WARNING" );
					}
				}
			}			
			
		}
		
		function checkHttpConnection() {
			
			$purgeURL = plugins_url("nginx-manager/check-proxy.php");
			$response = wp_remote_get($purgeURL);
			
			if( !is_wp_error( $response ) && ($response['body'] == 'HTTP Connection OK')) {
				return "OK";
			}
			
			return "KO";
			
		}
		
		function log( $msg, $level='INFO' ) {
			
			global $nginxm;
			
			$log_levels = array("INFO" => 0, "WARNING" => 1, "ERROR" => 2, "NONE" => 3);
			
			if ($log_levels[$level] >= $log_levels[$nginxm->global_options['log_level']]) {
				if ($fp = fopen(NGINXM_ABSPATH .'log/current.log',"a+")) {
					fwrite($fp, "\n".gmdate("Y-m-d H:i:s ")." | ".$level." | ".$msg);
					fclose($fp);
				}
			}
			
			return true;
			
		}
		
		function checkAndTruncateLogFile() {
			
			global $nginxm;
			
			$maxSizeAllowed = (is_numeric($nginxm->global_options['log_filesize'])) ? $nginxm->global_options['log_filesize']*1048576 : 5242880;
			
			$fileSize = filesize(NGINXM_ABSPATH .'log/current.log');
			
			if ($fileSize > $maxSizeAllowed) {
				
				$offset = $fileSize - $maxSizeAllowed;
				
				if ($file_content = file_get_contents(NGINXM_ABSPATH .'log/current.log', NULL, NULL, $offset)) {
					
					if ($file_content = strstr($file_content, "\n")) {
						
						if ($fp = fopen( NGINXM_ABSPATH .'log/current.log', "w+" )) {
							fwrite($fp, $file_content);
							fclose($fp);
						}
					}
				}
			}
		}
		
		function purgeImageOnEdit($attachment_id) {
			
			$this->log( "Purging media on edit BEGIN ===" );
			
			// check if is an image
			if ( wp_attachment_is_image( $attachment_id ) ) {
				
				// 1. purge image "main" URL
				$this->purgeUrl( wp_get_attachment_url( $attachment_id ), false );
				
				$attachment = wp_get_attachment_metadata( $attachment_id );
				
				// 2. purge image other sizes
				if ( $attachment['sizes'] ) {
					foreach ( $attachment['sizes'] as $size_name => $size ) {
						
						$resize_image = wp_get_attachment_image_src( $attachment_id, $size_name );
						if ( $resize_image )
							$this->purgeUrl( $resize_image[0], false );
					}
				}
				
			} else {
				$this->log( "Media (id $attachment_id) edited: no image", "WARNING" );
			}
			
			$this->log( "Purging media on edit END ^^^" );
			
		}
		
		/**
		 * Purge URLs when a post is moved to the Trash.
		 * Hooked by 'transition_post_status'.
		 * 
		 * @param $new_status
		 * @param $old_status
		 * @param $post
		 * @return Boolean
		 * 
		 * @since Nginx Manager 1.3
		 */
		function purge_on_post_moved_to_trash( $new_status, $old_status, $post ) {
			
			global $nginxm, $blog_id;
			
			if ($new_status == 'trash') {
				
				$this->log( "# # # # #" );
				$this->log( "# Post '$post->post_title' (id $post->ID) moved to the trash." );
				$this->log( "# # # # #" );
				
				$this->log( "Function purge_on_post_moved_to_trash (post id $post->ID) BEGIN ===" );
				
				if ($nginxm->options['automatically_purge_all_on_delete']) {
					
					// Let's purge all the blog
					$this->_purge_them_all();
					
				} else {
					
					/* Homepage */
					if ( $nginxm->options['automatically_purge_homepage'] == 1 ) {
						$this->_purge_homepage();
					}
					
					/* Personal URLs */
					if ( $nginxm->options['automatically_purge_purls'] == 1 ) {
						$this->_purge_personal_urls();
					}
					
					// Purge archives and custom taxa by options
					$this->_purge_by_options( $post->ID, $blog_id, false, $nginxm->options['automatically_purge_archives_on_delete'], $nginxm->options['automatically_purge_customtaxa_on_delete'] );
					
				}
				
				$this->log( "Function purge_on_post_moved_to_trash (post id $post->ID) END ===" );
				
			}
			
			return true;
			
		}
		
		/**
		 * Purge blog homepage
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_homepage() {
			
			$homepage_url = trailingslashit( get_option( 'siteurl' ) );
			
			$this->log( sprintf( __( "Purging homepage '%s'", "nginxm" ), $homepage_url ) );
			$this->purgeUrl( $homepage_url );
			
			return true;
			
		}
		
		/**
		 * Purge the personal URLs
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_personal_urls() {
			
			global $nginxm;
			
			$this->log( __( "Purging personal urls", "nginxm" ) );
			
			if (isset($nginxm->options['purgeable_url']['urls'])) {
				
				foreach ($nginxm->options['purgeable_url']['urls'] as $u) {
					$this->purgeUrl($u, false);
				}
				
			} else {
				$this->log( "- ".__( "No personal urls available", "nginxm" ) );
			}
			
			return true;
			
		}
		
		/**
		 * Purge the categories archives post-related
		 * 
		 * @param $_post_id
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_post_categories( $_post_id ) {
			
			$this->log( __( "Purging category archives", "nginxm" ) );
			
			if ( $categories = wp_get_post_categories( $_post_id ) ) {
				foreach ( $categories as $category_id ) {
					$this->log( sprintf( __( "Purging category '%d'", "nginxm" ), $category_id ) );
					$this->purgeUrl( get_category_link( $category_id ) );
				}
			}
			
			return true;
			
		}
		
		/**
		 * Purge the post tags archives post-related
		 * 
		 * @param $_post_id
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_post_tags( $_post_id ) {
			
			$this->log( __( "Purging tags archives", "nginxm" ) );
			
			if ( $tags = get_the_tags( $_post_id ) ) {
				foreach ( $tags as $tag ) {
					$this->log( sprintf( __( "Purging tag '%s' (id %d)", "nginxm" ), $tag->name, $tag->term_id ) );
					$this->purgeUrl( get_tag_link( $tag->term_id ) );
				}
			}
			
			return true;
			
		}
		
		/**
		 * Purge the custom taxonomies terms post-related
		 * 
		 * @param $_post_id
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_post_custom_taxa( $_post_id ) {
			
			$this->log( __( "Purging post custom taxonomies related", "nginxm" ) );
			
			if ( $custom_taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ) ) ) {
				
				foreach ( $custom_taxonomies as $taxon ) {
					$this->log( sprintf( "+ ".__( "Purging custom taxonomy '%s'", "nginxm" ), $taxon ) );
					
					// Extra check to get rid of the standard taxonomies
					if ( !in_array( $taxon, array( 'category', 'post_tag', 'link_category' ) ) ) {
						
						if ( $terms = get_the_terms( $_post_id, $taxon ) ) {
							foreach ( $terms as $term ) {
								$this->purgeUrl( get_term_link( $term, $taxon ) );
							}
						}
						
					} else {
						$this->log( sprintf( "- ".__( "Your built-in taxonomy '%s' has param '_builtin' set to false.", "nginxm" ), $taxon ), "WARNING" );
					}
				}
				
			} else {
				$this->log( "- ".__( "No custom taxonomies", "nginxm" ) );
			}
			
			return true;
			
		}
		
		/**
		 * Purge all the categories archives URL
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_categories() {
			
			$this->log( __( "Purging all categories", "nginxm" ) );
			
			if ( $_categories = get_categories() ) {
				
				foreach ( $_categories as $c ) {
					$this->log( sprintf( __( "Purging category '%s' (id %d)", "nginxm" ), $c->name, $c->term_id ) );
					$this->purgeUrl( get_category_link( $c->term_id ) );
				}
				
			} else {
				$this->log( __( "No categories archives", "nginxm" ) );
			}
			
			return true;
			
		}
		
		/**
		 * Purge all the post tags archives URL
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_posttags() {
			
			$this->log( __( "Purging all tags", "nginxm" ) );
			
			if ( $_posttags = get_tags() ) {
				
				foreach ( $_posttags as $t ) {
					$this->log( sprintf( __( "Purging tag '%s' (id %d)", "nginxm" ), $t->name, $t->term_id ) );
					$this->purgeUrl( get_tag_link( $t->term_id ) );
				}
				
			} else {
				$this->log( __( "No tags archives", "nginxm" ) );
			}
			
			return true;
			
		}
		
		/**
		 * Purge all the custom taxonomies terms URL
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_customtaxa() {
			
			$this->log( __( "Purging all custom taxonomies", "nginxm" ) );
			
			if ( $custom_taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ) ) ) {
				
				foreach ( $custom_taxonomies as $taxon ) {
					$this->log( sprintf( "+ ".__( "Purging custom taxonomy '%s'", "nginxm" ), $taxon ) );
					
					// Extra check to get rid of the standard taxonomies
					if ( !in_array( $taxon, array( 'category', 'post_tag', 'link_category' ) ) ) {
						
						if ( $terms = get_terms( $taxon ) ) {
							foreach ( $terms as $term ) {
								$this->purgeUrl( get_term_link( $term, $taxon ) );
							}
						}
						
					} else {
						$this->log( sprintf( "- ".__( "Your built-in taxonomy '%s' has param '_builtin' set to false.", "nginxm" ), $taxon ), "WARNING" );
					}
				}
				
			} else {
				$this->log( "- ".__( "No custom taxonomies", "nginxm" ) );
			}
			
			return true;
			
		}
		
		/**
		 * Purge all the taxonomies: categories, post tags and custom taxonomies
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_taxonomies() {
			
			$this->_purge_all_categories();
			$this->_purge_all_posttags();
			$this->_purge_all_customtaxa();
			
			return true;
		}
		
		/**
		 * Purge all posts, pages and custom post types.
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_posts() {
			
			$this->log( __( "Purging all posts, pages and custom post types.", "nginxm" ) );
			
			$args = array(
				'numberposts'     => 0,
				'post_type'       => 'any',
				'post_status'     => 'publish' );
			
			if ( $_posts = get_posts($args) ) {
				
				foreach ( $_posts as $p ) {
					$this->log( sprintf(  "+ ".__( "Purging post id '%d' (post type '%s')", "nginxm" ), $p->ID, $p->post_type ) );
					$this->purgeUrl( get_permalink( $p->ID ) );
				}
				
			} else {
				$this->log( "- ".__( "No posts", "nginxm" ) );
			}
			
			return true;
			
		}
		
		
		/**
		 * Purge all date-based archives: daily, monthly and yearly.
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_date_archives() {
			
			$this->log( __( "Purging all date-based archives.", "nginxm" ) );
			
			$this->_purge_all_daily_archives();
			
			$this->_purge_all_monthly_archives();
			
			$this->_purge_all_yearly_archives();
			
			return true;
			
		}
		
		/**
		 * Purge all daily archives
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_daily_archives() {
			
			global $wpdb;
			
			$this->log( __( "Purging all daily archives.", "nginxm" ) );
			
			/* Daily archives */
			$_query_daily_archives = $wpdb->prepare(
				"SELECT YEAR(post_date) AS 'year', MONTH(post_date) AS 'month', DAYOFMONTH(post_date) AS 'dayofmonth', count(ID) as posts 
				FROM $wpdb->posts 
				WHERE post_type = 'post' AND post_status = 'publish' 
				GROUP BY YEAR(post_date), MONTH(post_date), DAYOFMONTH(post_date) 
				ORDER BY post_date DESC"
			);
			
			if ( $_daily_archives = $wpdb->get_results( $_query_daily_archives ) ) {
				
				foreach( $_daily_archives as $_da ) {
					$this->log( sprintf( "+ ".__( "Purging daily archive '%s/%s/%s'", "nginxm" ), $_da->year, $_da->month, $_da->dayofmonth ) );
					$this->purgeUrl( get_day_link( $_da->year, $_da->month, $_da->dayofmonth ) );
				}
				
			} else {
				$this->log( "- ".__( "No daily archives", "nginxm" ) );
			}
			
		}
		
		/**
		 * Purge all monthly archives
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_monthly_archives() {
			
			global $wpdb;
			
			$this->log( __( "Purging all monthly archives.", "nginxm" ) );
			
			/* Monthly archives */
			$_query_monthly_archives = $wpdb->prepare(
				"SELECT YEAR(post_date) AS 'year', MONTH(post_date) AS 'month', count(ID) as posts 
				FROM $wpdb->posts 
				WHERE post_type = 'post' AND post_status = 'publish' 
				GROUP BY YEAR(post_date), MONTH(post_date) 
				ORDER BY post_date DESC"
			);
			
			if ( $_monthly_archives = $wpdb->get_results( $_query_monthly_archives ) ) {
				
				foreach( $_monthly_archives as $_ma ) {
					$this->log( sprintf( "+ ".__( "Purging monthly archive '%s/%s'", "nginxm" ), $_ma->year, $_ma->month ) );
					$this->purgeUrl( get_month_link( $_ma->year, $_ma->month ) );
				}
				
			} else {
				$this->log( "- ".__( "No monthly archives", "nginxm" ) );
			}
			
		}
		
		/**
		 * Purge all yearly archives
		 * @since Nginx Manager 1.3
		 */
		private function _purge_all_yearly_archives() {
			
			global $wpdb;
			
			$this->log( __( "Purging all yearly archives.", "nginxm" ) );
			
			/* Yearly archives */
			$_query_yearly_archives = $wpdb->prepare(
				"SELECT YEAR(post_date) AS 'year', count(ID) as posts 
				FROM $wpdb->posts 
				WHERE post_type = 'post' AND post_status = 'publish' 
				GROUP BY YEAR(post_date) 
				ORDER BY post_date DESC"
			);
			
			if ( $_yearly_archives = $wpdb->get_results( $_query_yearly_archives ) ) {
				
				foreach( $_yearly_archives as $_ya ) {
					$this->log( sprintf( "+ ".__( "Purging yearly archive '%s'", "nginxm" ), $_ya->year ) );
					$this->purgeUrl( get_year_link( $_ya->year ) );
				}
				
			} else {
				$this->log( "- ".__( "No yearly archives", "nginxm" ) );
			}
			
		}
		
		/**
		 * Purge all the blog: homepage, personal urls, posts/pages/custom post types, taxonomies, date-based archives.
		 * 
		 * @return Boolean
		 * @since Nginx Manager 1.3
		 */
		private function _purge_them_all() {
			
			$this->log( __( "LET'S PURGE ALL THE BLOG.", "nginxm" ) );
			
			$this->_purge_homepage();
			
			$this->_purge_personal_urls();
			
			// posts, pages, custom post types
			$this->_purge_all_posts();
			
			// categories, post tags, custom taxonomies
			$this->_purge_all_taxonomies();
			
			// daily, monthly and yearly archives
			$this->_purge_all_date_archives();
			
			return true;
			
		}
		
		/**
		 * Purge homepage when a term taxonomy is edited or deleted
		 * 
		 * @param $tt_id : term taxonomy id
		 * @param $taxon : taxonomy
		 * @return Boolean
		 * 
		 * @since Nginx Manager 1.3.1
		 */
		function purge_on_term_taxonomy_edited( $term_id, $tt_id, $taxon ) {
			
			$this->log( __( "Term taxonomy edited or deleted", "nginxm" ) );
			
			if ( current_filter() == 'edit_term' && $term = get_term( $term_id, $taxon ) ) {
				
				$this->log( sprintf( __( "Term taxonomy '%s' edited, (tt_id '%d', term_id '%d', taxonomy '%s')", "nginxm" ) , $term->name, $tt_id, $term_id, $taxon ) );
				
			} else if ( current_filter() == 'delete_term' ) {
				
				$this->log( sprintf( __( "A term taxonomy has been deleted from taxonomy '%s', (tt_id '%d', term_id '%d')", "nginxm" ), $taxon, $term_id, $tt_id ) );
				
			}
			
			// Purge homepage
			$this->_purge_homepage();
			
			return true;
			
		}
		
		/**
		 * Purge homepage when check_ajax_referer action is triggered.
		 * 
		 * @param string $action
		 * @param string $result
		 * @return boolean
		 */
		function purge_on_check_ajax_referer($action, $result) {
			
			switch ($action) {
				
				case 'save-sidebar-widgets' :
					
					$this->log( __( "Widget saved, moved or removed in a sidebar", "nginxm" ) );
					
					// Purge homepage
					$this->_purge_homepage();

					break;
					
				default :
					break;
					
			}
			
			return true;
			
		}
		
	}
	
}