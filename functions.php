<?php

require_once "includes/post-types.php";
require_once "includes/meteo.php";

function efc_enqueue_scripts()
{
    wp_enqueue_style("main", get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . "/style.css"));

    // Script navigation mobile (toutes les pages)
    wp_enqueue_script(
        'efc-nav',
        get_template_directory_uri() . '/assets/scripts/carousel.js',
        [],
        wp_get_theme()->get('Version'),
        true // charger en footer
    );

    // filtre pour la page programmation
    if (is_page_template('templates/template-programmation.php')) {
        $programme_filter_script = get_template_directory() . '/assets/scripts/programme-filtre.js';
        wp_enqueue_script(
            'efc-programme-filter',
            get_template_directory_uri() . '/assets/scripts/programme-filtre.js',
            [],
            filemtime($programme_filter_script),
            true
        );
    }

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


// formulaire pour déposer son projet

function efc_redirect_project_submission($status)
{
    $redirect_url = wp_get_referer() ?: home_url('/');
    wp_safe_redirect(add_query_arg('projet', $status, $redirect_url));
    exit;
}

function efc_handle_project_submission()
{
    $nonce = isset($_POST['efc_project_nonce']) ? sanitize_text_field(wp_unslash($_POST['efc_project_nonce'])) : '';
    if (!$nonce || !wp_verify_nonce($nonce, 'efc_submit_project')) {
        efc_redirect_project_submission('securite');
    }

    $name = isset($_POST['project_name']) ? sanitize_text_field(wp_unslash($_POST['project_name'])) : '';
    $email = isset($_POST['project_email']) ? sanitize_email(wp_unslash($_POST['project_email'])) : '';
    $title = isset($_POST['project_title']) ? sanitize_text_field(wp_unslash($_POST['project_title'])) : '';
    $message = isset($_POST['project_message']) ? sanitize_textarea_field(wp_unslash($_POST['project_message'])) : '';

    if (!$name || !is_email($email) || !$title || !$message) {
        efc_redirect_project_submission('invalide');
    }

    $body = "Nom : {$name}\nCourriel : {$email}\n\nProjet : {$title}\n\n{$message}";
    $sent = wp_mail(
        'courriel@radiolamatanie.ca',
        'Nouvelle proposition de projet : ' . $title,
        $body,
        ['Reply-To: ' . $email]
    );

    efc_redirect_project_submission($sent ? 'envoye' : 'erreur');
}
add_action('admin_post_efc_submit_project', 'efc_handle_project_submission');
add_action('admin_post_nopriv_efc_submit_project', 'efc_handle_project_submission');

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
