<? 
$href = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = parse_url($href);
//print_r($url);

$query = array();
if (@strpos($url['query'],'vk.com/album')) {
     $u = $url['query'];
 
} else {
     $u = 'https://vk.com/album-266064_152136951';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./slimbox/slimbox2.css"><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script><script src="js/jquery.slimbox2.js"></script><script src="js/jquery.embedvkgallery.js"></script>
</head>
<body>
<div id="vkgallery" style="width:620px;" data-width="50" class="p"></div>
<script>

    (function($) {
        $(function() {
           //$('#vkgallery').EmbedVkGallery('http://vk.com/album-71713142_196340316');
           $('#vkgallery').EmbedVkGallery('<?=$u?>');

        function scanFrame() {
                $("a[rel^='lightbox']", this.document).slimbox({/* Put custom options here */}, null, function(el) {
                        return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
                });
        }
        scanFrame.apply(window);
        $.each(window.frames, function() {
                this.onload = scanFrame;
                scanFrame.apply(this);
        });


        });

    
    })(jQuery);
</script>
</body>
</html>