<?php
 	$dir = '/var/www/wordpress/headers/forum/';
	$arr = glob($dir.'*' . '.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
	$index = rand(0,count($arr) - 1); 
	$str = substr($arr[$index], 18);
?>
<style type="text/css">
	#section-two {background: rgba(0, 0, 0, 0) url('http://veloufa.ru<?=$str; ?>') no-repeat 0 0;}
	#page-header .headerbar .inner{background:transparent url('http://veloufa.ru<?=$str; ?>') no-repeat 0 0;}
</style>



