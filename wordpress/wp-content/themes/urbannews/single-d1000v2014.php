<!doctype html>
<html>
<html>
<head>
    <!-- [page_headers] -->
    <base href="http://veloufa.ru/" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php wp_title('&laquo;', true, 'right'); ?> - День тысячи велосипедистов 2014</title>
    <!-- DESCRIPTION -->
    <meta name="description" content="<?php if(!empty($data['meta-desc'])) { ?><?php echo $data['meta-desc']; ?><?php } ?>" />
    <!-- KEYWORDS -->
    <meta name="keywords" content="<?php if(!empty($data['meta-key'])) { ?><?php echo $data['meta-key']; ?><?php } ?>" />
    <!-- FAVICON -->
    <?php if(!empty($data['custom_favicon'])) { ?><link rel="icon" type="image/png" href="<?php echo $data['custom_favicon']; ?>" /><?php } ?>

    <!--[if lte IE 8]>
    <script src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/js/libs/html5.js"></script>
    <script src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/js/libs/ie9.js"></script>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_url' ); ?>/d1000v2014/css/libs/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_url' ); ?>/d1000v2014/css/libs/typeset.css" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_url' ); ?>/d1000v2014/css/_css.css" />

    <script src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/js/libs/jquery.min.js"></script>
    <script src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/js/libs/jquery.tortSlider.js"></script>
    <script src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/js/js.js"></script>
    <!-- [/page_headers] -->
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-20669034-1', 'veloufa.ru');
  ga('send', 'pageview');
</script>
</head>
<div class="head single">
    <a class="logo" href="http://www.veloufa.ru/d1000v/"><img src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/img/logo.png" alt="День 1000 велосипедистов 2014" width="182" height="89"/></a>
    <div class="date">18 МАЯ 2014г. </div>
</div>
<div class="wrapper">
    <nav class="menu">
        <ul>
            <li><a href="/d1000v-podgotovka/">Как подготовиться</a></li>
            <!--<li><a href="/d1000v-chem-mozhno-pomoch-proektu/">Как помочь</a></li>-->
            <li><a href="/d1000v-partneram/">Партнерам</a></li>
            <li><a href="/d1000v-volonteram/">Волонтерам</a></li>
            <li><a href="/d1000v-dlya-smi/">Для СМИ</a></li>
        </ul>
    </nav>
    <div class="social">
        <a class="social-link social-vk" href="http://vk.com/d1000v"></a>
        <a class="social-link social-twi" href="https://twitter.com/veloufa"></a>
        <a class="social-link social-fb" href="https://www.facebook.com/events/666775903359938/"></a>
        <a class="register" href="/open-letter/">Петиция</a>
    </div>
    <?php while ( have_posts() ) : the_post(); ?>
        <header class="page-block header">
            <h1><?php the_title(); ?></h1>
        </header>
        <div class="page-block single-content typeset">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
