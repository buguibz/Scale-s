<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximus-scale=1"/>
    	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <title><?php wp_title('|',true,'right'); ?></title>
        <meta name="description" content="Designing your website. Scale-s is a company dedicated to web development, any company serving woldwde.">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/normalize.css">
        <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/main.css">
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?>">
        <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/responsive.css">       
        <!--<link rel="stylesheet" href="<?php /*bloginfo('stylesheet_url')*/ ?>/css/style.css">-->
        <script src="<?php bloginfo('template_url') ?>/js/vendor/modernizr-2.6.2.min.js"></script>
        <?php wp_head(); ?>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

        <header class="head">
            <div class="contentPanel">
                <div class="logo">
                    <a href="index.html">
                        <img src="<?php bloginfo('template_url') ?>/img/logoScale-s.png" alt="Scale-s">
                    </a>
                </div>
                <nav class="navigation">
                    <ul class="navMenu">
                        <li class="linkMenu"><a href="http://localhost/wst" class="iconHome">Home</a></li>
                        <li class="linkMenu"><a href="http://localhost/wst/services" class="iconService">Services</a></li>
                        <li class="linkMenu"><a href=" http://localhost/wst/about-us" class="iconAboutus">About us</a></li>
                        <li class="linkMenu"><a href="http://localhost/wst/contact" class="iconContact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>