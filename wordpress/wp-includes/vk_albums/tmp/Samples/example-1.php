<!doctype html>
    <meta charset="utf-8" />
    <style>
    html, body { font-family: monospace; }
    </style>
    
<?php

/**
 * Example 1.
 * Usage VK API without authorization.
 * Some calls are not available.
 * @link http://vk.com/developers.php VK API
 */

error_reporting(E_ALL);
require_once('../src/VK/VK.php');
require_once('../src/VK/VKException.php');

require_once('../../CurlMng_class.php');
require_once('../../VkApiMng_class.php');



try {
    $vk = new VK\VK('4562295', 'uaAqKLR8uKb4DKZR7UIs'); // Use your app_id and api_secret
    
    $users = $vk->api('users.get', array(
        'uids'   => '1234,4321',
        'fields' => 'first_name,last_name,sex'));
        
    foreach ($users['response'] as $user) {
        echo $user['first_name'] . ' ' . $user['last_name'] . ' (' .
            ($user['sex'] == 1 ? 'Girl' : 'Man') . ')<br />';
        }

$album_id = '71713142_202713550';

list($owner_id, $aid) = explode("_", $album_id);

$resp = VK.api("photos.get", { gid: gid, aid: aid }, function(result){ 	if (result.response){ 		// Список фотографий лежит в массиве result.response 		// ID владельца фотографии содержится в поле owner_id 		// ID фотографии содержится в поле pid 	}else{ 		// Не удалось получить список фотографий в альбоме 	} }); 

//$resp = $vk->api('photos.getAlbums', array('owner_id'=>'71713142','aid'=>'202713550'));


print_r($resp); 



} catch (VK\VKException $error) {
    echo $error->getMessage();
}
