<?php

if (!class_exists('NginxmNextgengallery')) {
	
	/**
	 * Integration between Nginx Manager and NextGEN Gallery
	 * 
	 * @since Nginx Manager 1.2
	 */
	class NginxmNextgengallery {
		
		/**
		 * Purge URL when NGG saves an image
		 * 
		 * @param $thumb_path: NGG hooks return image path, we need URL
		 * @since Nginx Manager 1.1
		 */
		function ngg_purge_url_on_saving_images($thumb_path) {
			
			global $wpdb, $nginxmNginx;
			
			$nginxmNginx->log( "NGG image saved BEGIN ===".$gallery_id );
			
			$thumb_path = home_url().substr(strstr($thumb_path, "/wp-content"), 0);
			$nginxmNginx->purgeUrl($thumb_path, false);
			
			$nginxmNginx->log( "NGG image saved END ^^^" );

			// Get the picture id from the thumb path
			
			$gallery_id = $wpdb->get_var( 
				$wpdb->prepare( "SELECT galleryid FROM $wpdb->nggpictures
					WHERE filename = '%s'", substr($thumb_path, strpos($thumb_path, 'thumbs_') + 7)), 0, 0 );
				
			// Purge post that contains the image/thumb gallery 
			
			$this->ngg_purge_post_on_editing_galleries($gallery_id);
			
		}
		
		/**
		 * Purge posts/pages that cointain a specific gallery
		 * 
		 * @param $gallery_id
		 * @since Nginx Manager 1.1
		 */
		function ngg_purge_post_on_editing_galleries($gallery_id) {
			
			global $wpdb, $blog_id, $nginxmNginx;
			
			$nginxmNginx->log( "NGG gallery $gallery_id edited (blog $blog_id) BEGIN ===" );
			
			$post_ids = $wpdb->get_col( 
				$wpdb->prepare( 
					"SELECT ID 
					FROM $wpdb->posts 
					WHERE post_status = 'publish' 
						AND post_content LIKE '%%[nggallery id=%d]%%'", $gallery_id ) );
			
			if ($post_ids) {
				foreach ($post_ids as $post_id) {
					$nginxmNginx->log( "NGG gallery $gallery_id edited (blog $blog_id): purge post $post_id" );
					$nginxmNginx->purgePost($post_id);
				}
			} else {
				$nginxmNginx->log( "NGG gallery $gallery_id edited (blog $blog_id): no posts/pages with this gallery" );
			}
			
			$nginxmNginx->log( "NGG gallery $gallery_id edited (blog $blog_id) END ^^^" );
			
			// Use this hook to run a function when a gallery is purged, @since Nginx Manager 1.3.4
			do_action('nm_ngg_gallery_purge', $gallery_id);
			
		}
		
	}
	
}

?>