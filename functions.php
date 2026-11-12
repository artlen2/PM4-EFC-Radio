<?php

require_once "includes/post-types.php";

function efc_enqueue_scripts()
{
    wp_enqueue_style("main", get_stylesheet_uri());

    // Script navigation mobile (toutes les pages)
    wp_enqueue_script(
        'efc-nav',
        get_template_directory_uri() . '/assets/scripts/carousel.js',
        [],
        wp_get_theme()->get('Version'),
        true // charger en footer
    );

    // Vue.js 
    if (is_page_template("templates/template-evenements-vue.php")) {
        wp_enqueue_script("vue-lib", "https://unpkg.com/vue@3/dist/vue.global.js");
        wp_enqueue_script("vue-main", get_template_directory_uri() . "/assets/scripts/main-vue.js", ["vue-lib"]);
        wp_localize_script("vue-main", "wpData", [
            "restUrl" => get_rest_url()
        ]);
    }
}
add_action("wp_enqueue_scripts", "efc_enqueue_scripts");

// Expose les champs SCF dans la REST API
add_filter('acf/rest/format_value_for_rest', '__return_true');

function efc_setup_scripts()
{
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");


    // Add custom header
    add_theme_support("custom-header", [
        "width" => 1920,
        "height" => 1080,
        "flex-width" => true,
        "flex-height" => true,
    ]);

    // Custom Logo
    add_theme_support("custom-logo", [
        'width'                => 512,
        'height'               => 512,
        'flex-height'          => true,
        'flex-width'           => true,
        'unlink-homepage-logo' => false,
    ]);

    // HTML5
    add_theme_support("html5", [
        "search-form",
        "style",
        "meta",
        "script",
    ]);

    // Add a menu
    register_nav_menu("main-menu", __("Menu principal"));
}

add_action("after_setup_theme", "efc_setup_scripts");

// CLEANINGGG #SqueakyClean

// Retire le lien 'rsd-link' du head
remove_action('wp_head', 'rsd_link');

// Retire l'affichage de la version de Wordpress dans le code
add_filter('the_generator', function () {
    return "";
});

// Retire le lien wlwmanifest (Anciennement utilisé par Window Live Writer)
remove_action('wp_head', 'wlwmanifest_link');

// Retire le shortlink (Anciennement, retiré depuis Wordpress 4.4)
remove_action('wp_head', 'wp_shortlink_wp_head');

// Cacher la version des assets utilisés
function mytheme_hide_assets_version($src)
{
    $parts = explode('?', $src);
    return $parts[0];
}
add_filter('script_loader_src', 'mytheme_hide_assets_version', 15, 1);
add_filter('style_loader_src', 'mytheme_hide_assets_version', 15, 1);

// Retire la découverte des liens oEmbed
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// Retire les emojis (autant dans le backend que le frontend)
function mytheme_remove_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'mytheme_remove_emojis');
