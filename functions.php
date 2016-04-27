<?php
// Removes unneccessary scripts and enqueues proper ones
function theme_enqueue_scripts() {
	wp_deregister_script('wp-embed'); // Whatever that script is we don't need it
	wp_deregister_script('jquery');   // We do this to include a more recent version
	wp_enqueue_script('webfonts',   '//cdnjs.cloudflare.com/ajax/libs/webfont/1.6.22/webfontloader.js', '', '', true);
	wp_register_script('jquery',   ('//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.min.js'), false, '', true);
	wp_enqueue_script('main', get_template_directory_uri() . '/includes/main.js', array('jquery'), '', true );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

// Enqueues necessary CSS
function theme_enqueue_styles() {
	wp_enqueue_style('foundation', '//cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/foundation.min.css');
	wp_enqueue_style('main', get_template_directory_uri().'/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 11 );

// Initializes widget area
function theme_widgets_init() {
	register_sidebar(array(
		'name' => 'Widget Area 1',
		'id'   => 'widget_area_1',
		'before_widget' => '<div class="small-12 columns">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>'
	));
}
add_action('widgets_init', 'theme_widgets_init');

// Initializes menu area
function theme_menus_init() { register_nav_menu('first_menu', __('First Menu')); }
add_action('init', 'theme_menus_init');

// Edits admin footer text for reminder to purge the cache
function remove_footer_admin() { echo 'Don\'t forget to purge the cache, dumbass.'; }
add_filter('admin_footer_text', 'remove_footer_admin');

// Disables all the wpemoji shit
function disable_emojis_tinymce($plugins) {
	if(is_array($plugins)) { return array_diff($plugins, array('wpemoji')); }
	else { return array(); }
}
function disable_emojis() {
	remove_action('wp_head',             'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles',     'print_emoji_styles');
	remove_action('admin_print_styles',  'print_emoji_styles');
	remove_filter('the_content_feed',    'wp_staticize_emoji');
	remove_filter('comment_text_rss',    'wp_staticize_emoji');
	remove_filter('wp_mail',             'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins',       'disable_emojis_tinymce');
}
add_action('init', 'disable_emojis');

// Disables srcset attributes on images, it makes them blurry on large displays in 4.4+
add_filter('wp_get_attachment_image_attributes', function($attr) {
	if (isset($attr['sizes']))
		unset($attr['sizes']);
	if (isset($attr['srcset']))
		unset($attr['srcset']);
	return $attr;
}, PHP_INT_MAX);
add_filter('wp_calculate_image_sizes',  '__return_false', PHP_INT_MAX);
add_filter('wp_calculate_image_srcset', '__return_false', PHP_INT_MAX);
remove_filter('the_content', 'wp_make_content_images_responsive');

// Disables WP-JSON, Windows Live Writer, other shit
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

// Custom ajax loader for CF7, has to be GIF
// I'd love to use a CSS loader, research?
// add_filter('wpcf7_ajax_loader', 'my_wpcf7_ajax_loader');
// function my_wpcf7_ajax_loader () {
// 	return  get_bloginfo('template_directory') . '/loader.gif';
// }

// Logs DB Queries, Time Spent, and Memory Consumption
add_action( 'wp_footer', 'performance', 20 );
function performance($visible = false) {
    $stat = sprintf('%d queries in %.3f seconds, using %.2fMB memory',
        get_num_queries(),
        timer_stop(0, 3),
        memory_get_peak_usage() / 1024 / 1024
    );
    echo $visible ? $stat : "<!-- {$stat} -->\r\n";
}