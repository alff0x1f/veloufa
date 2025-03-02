<?php

header("Content-type: image/png");

if (isset ($_GET['image'])) 
	$image_name = $_GET['image'];
$im = imagecreatefrompng($image_name);
imageAlphaBlending($im, true);
imageSaveAlpha($im, true);
$string = '';
if (isset ($_GET['text'])) {
	$string = $_GET['text'];
	$string = substr($string,0,2);
	$string = str_replace('0','O',$string);
}
$black = imagecolorallocate($im, 0, 0, 0);
$len = strlen($string);
if($len <= 2) {
  $px = (imagesx($im) - 7 * strlen($string)) / 2 + 1;
  imagestring($im, 3, $px, 3, $string, $black);
} else {
  $px = (imagesx($im) - 7 * strlen($string)) / 2 + 2;
  imagestring($im, 2, $px, 3, $string, $black);
}

imagepng($im);
imagedestroy($im);

?>

