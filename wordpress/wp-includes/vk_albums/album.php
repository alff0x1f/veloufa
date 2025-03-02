<?php
require('CurlMng_class.php');
require('VkApiMng_class.php');

$api_id = '4562295'; // ID приложения
$vk_id = '686407'; // ID аккаунта
$VK = new vkapi($api_id, $vk_id); // Вызываем конструктор

list($owner_id, $aid) = explode("_", $album_id);
$vkMng = new VkApiMng(array("photos"));
            $params = array('aid'=>$aid, 'extended'=>1);
            if ($owner_id<0){
                $params['gid'] = 0-$owner_id;
            }else{
                $params['uid'] = $owner_id;
            }
            

            $photos = $vkMng->apiRequest("photos.get", $params);

?>