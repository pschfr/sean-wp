<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<meta name="description" content="" />
<meta property="og:url" content="<?php echo site_url(); ?>/" />
<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/screenshot.png" />
<meta property="og:title" content="<?php bloginfo('name'); ?>, <?php is_front_page() ? bloginfo('description') : wp_title(''); ?>" />
<meta property="og:description" content="" />
<title><?php bloginfo('name'); ?>, <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header id="header" class="row">
<h1 class="small-12 columns"><?php bloginfo('name') ?></h1>
<h4 class="small-12 columns">&middot;<?php bloginfo('description') ?>&middot;</h4>
<?php wp_nav_menu(array(
	'theme_location' => 'first_menu',
	'menu_class'     => 'small-12 columns',
	'container'      => 'nav',
)); ?>
<hr>
</header>
