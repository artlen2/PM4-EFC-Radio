<?php
if (! defined('ABSPATH')) {
    exit; // Block direct access if WordPress isn't loaded
}

function efcradio_enqueue()
{
    // Compiled Tailwind file (styles/main.css is only the source)
    $css = '/style.css';
    wp_enqueue_style(
        'efcradio-tailwind',
        get_template_directory_uri() . $css,
        array(),
        file_exists(get_template_directory() . $css) ? filemtime(get_template_directory() . $css) : null
    );
}
add_action('wp_enqueue_scripts', 'efcradio_enqueue');


// Supports du thème 
function radio_setup_scripts()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // Add custom header
    add_theme_support("custom-header", [
        "width" => 1920,
        "height" => 1080,
        "flex-width" => true,
        "flex-height" => true,
    ]);

    // Custom Logo
    add_theme_support('custom-logo', [
        'width'               => 200,
        'height'              => 200,
        'flex-width'          => true,
        'flex-height'         => true,
        'unlink-homepage-logo' => true
    ]);

    // HTML5
    add_theme_support("html5", [
        "search-form",
        "style",
        "meta",
        "script",
    ]);

    register_nav_menu('main-menu', 'Menu principal');
}
add_action('after_setup_theme', 'radio_setup_scripts');


// Cleaning code #SqueakyClean

// Remove the 'rsd_link' from the head
remove_action('wp_head', 'rsd_link');

// Remove the WordPress version from the generated code
add_filter('the_generator', function () {
    return '';
});

// Remove the wlwmanifest link (formerly used by Windows Live Writer)
remove_action('wp_head', 'wlwmanifest_link');

// Remove the shortlink
remove_action('wp_head', 'wp_shortlink_wp_head');

// Remove oEmbed discovery links
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// Remove emojis (both backend and frontend)
function radio_remove_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'radio_remove_emojis');
