<?
require 'vkapi.class.php';

$api_id = 4562295; // Insert here id of your application
$secret_key = 'uaAqKLR8uKb4DKZR7UIs'; // Insert here secret key of your application

$VK = new vkapi($api_id, $secret_key);

$resp = $VK->api('getProfiles', array('uids'=>'1,6492'));

print_r($resp);
?>
