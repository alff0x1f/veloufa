<?php
/*
 * @version     2.0
 * @package     J.B.Weather Widget
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      J.B.MARKET <support@jbmarket.net>
 */
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>';

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

$apikey = $_POST["apiKey"];
$location = $_POST["location"];
$curl = $_POST["curl"];

$cacheName = str_replace(",", "", $location);
$cacheName = str_replace(" ", "", $cacheName);
$cacheName = strtolower($cacheName);
$cacheTime = 3600;

if (!file_exists($cacheName) || filemtime($cacheName) <= time() - $cacheTime) {
    if ($curl == 1) {
        $contents = file_get_contents_curl("http://free.worldweatheronline.com/feed/weather.ashx?q=" . urlencode($location) . "&format=xml&num_of_days=4&key=" . $apikey);
    } else {
        $contents = file_get_contents("http://free.worldweatheronline.com/feed/weather.ashx?q=" . urlencode($location) . "&format=xml&num_of_days=4&key=" . $apikey);
    }
    file_put_contents($cacheName, $contents);
}

$xml = simplexml_load_file($cacheName);

if (!$xml->xpath("/data/current_condition"))
    die();

$current  = $xml->xpath("/data/current_condition");
$forecast = $xml->xpath("/data/weather");
$request  = $xml->xpath("/data/request");

ob_start();
?>

<response>
    <location><![CDATA[<?php echo $request[0]->query; ?>]]></location>

    <current>
        <date><![CDATA[<?php echo date('l d m Y'); ?>]]></date>

        <time><![CDATA[<?php echo $current[0]->observation_time; ?>]]></time>

        <temperature>
            <f><![CDATA[<?php echo $current[0]->temp_F; ?>]]></f>
            <c><![CDATA[<?php echo $current[0]->temp_C; ?>]]></c>
        </temperature>

        <code><![CDATA[<?php echo $current[0]->weatherCode; ?>]]></code>

        <description><![CDATA[<?php echo $current[0]->weatherDesc; ?>]]></description>

        <wind>
            <windSpeed>
                <m><![CDATA[<?php echo $current[0]->windspeedMiles; ?>]]></m>
                <k><![CDATA[<?php echo $current[0]->windspeedKmph; ?>]]></k>
            </windSpeed>

            <direction><![CDATA[<?php echo $current[0]->winddir16Point; ?>]]></direction>
        </wind>
    </current>

<?php foreach ($forecast as $day) : ?>
        <day>
            <date><![CDATA[<?php echo date('l d', strtotime($day->date)); ?>]]></date>
            <temperature>
                <max>
                <f><![CDATA[<?php echo $day->tempMaxF; ?>]]></f>
                <c><![CDATA[<?php echo $day->tempMaxC; ?>]]></c>
                </max>

                <min>
                <f><![CDATA[<?php echo $day->tempMinF; ?>]]></f>
                <c><![CDATA[<?php echo $day->tempMinC; ?>]]></c>
                </min>
            </temperature>

            <code><![CDATA[<?php echo $day->weatherCode; ?>]]></code>

            <description><![CDATA[<?php echo $day->weatherDesc; ?>]]></description>

            <wind>
                <windSpeed>
                    <m><![CDATA[<?php echo $day->windspeedMiles; ?>]]></m>
                    <k><![CDATA[<?php echo $day->windspeedKmph; ?>]]></k>
                </windSpeed>

                <direction><![CDATA[<?php echo $day->winddir16Point; ?>]]></direction>
            </wind>
        </day>
<?php endforeach; ?>

</response>

<?php ob_get_flush(); ?>