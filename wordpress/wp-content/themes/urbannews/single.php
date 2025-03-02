<?php
  $post = $wp_query->post;
 
  if (in_category('316')) {
      include(TEMPLATEPATH.'/single-d1000v2014.php');
  } else {
      include(TEMPLATEPATH.'/single-default.php');
  }
?>