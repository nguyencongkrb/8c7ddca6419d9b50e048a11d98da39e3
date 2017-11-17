<!DOCTYPE html>
<!--[if IE 9 ]>
<html <?php language_attributes(); ?> class="ie9 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if IE 8 ]>
<html <?php language_attributes(); ?> class="ie8 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>"> <!--<![endif]-->
<head>
    <!-- Meta tags -->
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <!-- Meta tags -->
    <?php wp_head(); ?>

    <!-- Meta tags -->
    <meta property="fb:app_id" content="313299649147328"/>
    <meta property="fb:admins" content="100002596421790"/>

    <!--CSS Style File Link -->
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <!-- Script -->
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.11&appId=313299649147328';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109718008-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-109718008-1');
    </script>
    <!-- Navigation bar -->
</head>
<body <?php body_class(); // Body classes is added from inc/helpers-frontend.php ?>>
<!-- Facebook -->
<div id="fb-root"></div>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'flatsome'); ?></a>

<div id="wrapper">

    <?php do_action('flatsome_before_header'); ?>

    <header id="header" class="header <?php flatsome_header_classes(); ?>">
        <div class="header-wrapper">
            <?php
            get_template_part('template-parts/header/header', 'wrapper');
            ?>
        </div><!-- header-wrapper-->
    </header>

    <?php do_action('flatsome_after_header'); ?>

    <main id="main" class="<?php flatsome_main_classes(); ?>">
