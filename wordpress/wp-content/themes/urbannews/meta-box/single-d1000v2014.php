<!doctype html>
<html>
<html>
<head>
    <!-- [page_headers] -->
    <base href="http://veloufa.ru/" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>День тысячи велосипедистов 2014</title>
    <meta name="keywords" content="[[*keywords]]" />
    <meta name="description" content="[[*description]]" />

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


    <link rel="shortcut icon" href="/favicon.ico">
    <!-- [/page_headers] -->
</head>
<body>
<div class="head single">
    <a class="logo" href="/"><img src="<?php bloginfo( 'template_url' ); ?>/d1000v2014/img/logo.png" alt="День 1000 велосипедистов 2014" width="182" height="89"/></a>
    <div class="date">18 МАЯ 2014г. </div>
</div>
<div class="wrapper">
    <nav class="menu">
        <ul>
            <li><a href="">Фото</a></li>
            <li><a href="">Видео</a></li>
            <li><a href="">Спонсорам</a></li>
            <li><a href="">Партнерам</a></li>
            <li><a href="">Волонтерам</a></li>
        </ul>
    </nav>
    <div class="social">
        <a class="social-link social-vk" href=""></a>
        <a class="social-link social-twi" href=""></a>
        <a class="social-link social-fb" href=""></a>
        <a class="register" href="/">Регистрация</a>
    </div>
    <?php while ( have_posts() ) : the_post(); ?>


        <header class="page-block header">
            <h1><?php the_title(); ?></h1>
        </header>
        <div class="page-block single-content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>