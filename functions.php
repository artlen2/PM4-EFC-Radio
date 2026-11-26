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

    if (is_singular('programme')) {
        $programme_title_script = get_template_directory() . '/assets/scripts/programme-title-spotlight.js';
        wp_enqueue_script(
            'efc-programme-title-spotlight',
            get_template_directory_uri() . '/assets/scripts/programme-title-spotlight.js',
            [],
            filemtime($programme_title_script),
            true
        );
    }

    if (is_page('soutenir') || is_page_template('templates/template-soutenir.php')) {
        $soutenir_mascot_script = get_template_directory() . '/assets/scripts/soutenir-mascot.js';
        wp_enqueue_script(
            'efc-soutenir-mascot',
            get_template_directory_uri() . '/assets/scripts/soutenir-mascot.js',
            [],
            filemtime($soutenir_mascot_script),
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

/*
 * Actualités : pagination et filtres de la requête principale.
 */
add_action('pre_get_posts', function ($query) {
    if (
        is_admin() ||
        !$query->is_main_query() ||
        !$query->is_home()
    ) {
        return;
    }

    $query->set('posts_per_page', 4);
    $query->set('orderby', 'date');
    $query->set('order', 'DESC');

    $allowed_categories = [
        'communaute',
        'culturel',
        'politique',
        'sport',
    ];

    $category = (
        isset($_GET['categorie']) &&
        is_string($_GET['categorie'])
    )
        ? sanitize_key(wp_unslash($_GET['categorie']))
        : '';

    if (in_array($category, $allowed_categories, true)) {
        $query->set('category_name', $category);
    }
});

// Fromulaire benevolat
function efc_handle_benevolat_submit()
{
    $redirect_base = !empty($_POST['redirect_to'])
        ? esc_url_raw(wp_unslash($_POST['redirect_to']))
        : home_url('/');

    if (
        !isset($_POST['efc_benevolat_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['efc_benevolat_nonce'])), 'efc_benevolat_action') ||
        !empty($_POST['site_web'])
    ) {
        wp_safe_redirect(add_query_arg('benevolat', 'erreur', $redirect_base) . '#benevolat');
        exit;
    }

    $nom      = sanitize_text_field(wp_unslash($_POST['nom'] ?? ''));
    $courriel = sanitize_email(wp_unslash($_POST['courriel'] ?? ''));
    $tel      = sanitize_text_field(wp_unslash($_POST['telephone'] ?? ''));
    $interet  = sanitize_text_field(wp_unslash($_POST['interet'] ?? ''));
    $message  = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if (!$nom || !is_email($courriel) || !$message) {
        wp_safe_redirect(add_query_arg('benevolat', 'erreur', $redirect_base) . '#benevolat');
        exit;
    }

    $to      = get_option('admin_email');
    $subject = 'Nouvelle candidature bénévole — ' . $nom;
    $body    = "Nom : {$nom}\nCourriel : {$courriel}\nTéléphone : {$tel}\nIntérêt : {$interet}\n\nMessage :\n{$message}";
    $headers = ['Reply-To: ' . $nom . ' <' . $courriel . '>'];

    $sent   = wp_mail($to, $subject, $body, $headers);
    $status = $sent ? 'succes' : 'erreur';

    wp_safe_redirect(add_query_arg('benevolat', $status, $redirect_base) . '#benevolat');
    exit;
}
add_action('admin_post_nopriv_efc_benevolat_submit', 'efc_handle_benevolat_submit');
add_action('admin_post_efc_benevolat_submit', 'efc_handle_benevolat_submit');

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

/**
 * Convertit une heure en minutes depuis minuit
 * Retourne null si l'heure est vide
 */
function efc_heure_en_minutes($heure)
{
    if (!is_string($heure) || trim($heure) === '') {
        return null;
    }

    if (!preg_match('/(\d{1,2})\s*[:h]\s*(\d{2})?\s*(am|pm)?/i', $heure, $morceaux)) {
        return null;
    }

    $heures  = (int) $morceaux[1];
    $minutes = isset($morceaux[2]) && $morceaux[2] !== '' ? (int) $morceaux[2] : 0;
    $ampm    = isset($morceaux[3]) ? strtolower($morceaux[3]) : '';

    if ($ampm === 'pm' && $heures < 12) {
        $heures += 12;
    }
    if ($ampm === 'am' && $heures === 12) {
        $heures = 0;
    }

    return $heures * 60 + $minutes;
}
/**
 * Retourne le jour de la semaine d'une émission
 * à partir de son champ "date"
 * Retourne null si la date est vide 
 */
function efc_jour_emission($post_id){
    $date_texte = get_field('date', $post_id);

    if (!is_string($date_texte) || trim($date_texte) === '') {
        return null;
    }

    $date = DateTime::createFromFormat('d/m/Y', $date_texte, wp_timezone());

    if (!$date) {
        return null;
    }

    $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    return $jours[(int) $date->format('w')];
}

add_action("after_setup_theme", "efc_setup_scripts");

/* champs pages soutenir */
function efc_soutenir_admin_fields($post_type, $post)
{
    if ($post_type !== 'page' || !$post || $post->post_name !== 'soutenir') {
        return;
    }

    add_meta_box(
        'efc-soutenir-fields',
        'Contenu de la page Soutenir',
        'efc_soutenir_admin_fields_html',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'efc_soutenir_admin_fields', 10, 2);

function efc_soutenir_admin_fields_html($post)
{
    wp_nonce_field('efc_save_soutenir_fields', 'efc_soutenir_nonce');

    $fields = [
        'member_title' => 'Titre de l’article',
        'member_text' => 'Texte descriptif',
        'member_button' => 'Texte du bouton',
        'member_url' => 'URL du bouton',
        'benefit_title' => 'Avantages — titre',
        'benefit_badge' => 'Avantages — étiquette',
        'benefit_text' => 'Avantages — texte',
        'partner_title' => 'Partenaire — titre',
        'partner_text' => 'Partenaire — texte',
        'partner_button' => 'Partenaire — texte du bouton',
        'partner_url' => 'Partenaire — URL du bouton',
    ];

    echo '<p><strong>Modifiez ici le contenu des sections de la page Soutenir.</strong></p>';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, '_efc_' . $key, true);
        echo '<p><label for="efc-' . esc_attr($key) . '" style="display:block;font-weight:600;margin-bottom:4px">' . esc_html($label) . '</label>';
        $textarea = in_array($key, ['member_text', 'benefit_text', 'partner_text'], true);
        if ($textarea) {
            echo '<textarea id="efc-' . esc_attr($key) . '" name="efc_' . esc_attr($key) . '" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
        } else {
            $input_type = in_array($key, ['member_url', 'partner_url'], true) ? 'url' : 'text';
            echo '<input id="efc-' . esc_attr($key) . '" name="efc_' . esc_attr($key) . '" type="' . esc_attr($input_type) . '" value="' . esc_attr($value) . '" style="width:100%">';
        }
        echo '</p>';
    }
}

function efc_save_soutenir_fields($post_id)
{
    if (!isset($_POST['efc_soutenir_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['efc_soutenir_nonce'])), 'efc_save_soutenir_fields')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $keys = [
        'member_title',
        'member_text',
        'member_button',
        'member_url',
        'benefit_title',
        'benefit_badge',
        'benefit_text',
        'partner_title',
        'partner_text',
        'partner_button',
        'partner_url',
    ];
    $text_fields = ['member_text', 'benefit_text', 'partner_text'];
    $url_fields = ['member_url', 'partner_url'];

    foreach ($keys as $key) {
        if (!isset($_POST['efc_' . $key])) {
            continue;
        }
        $raw_value = wp_unslash($_POST['efc_' . $key]);
        if (in_array($key, $text_fields, true)) {
            $value = sanitize_textarea_field($raw_value);
        } elseif (in_array($key, $url_fields, true)) {
            $value = esc_url_raw($raw_value);
        } else {
            $value = sanitize_text_field($raw_value);
        }
        update_post_meta($post_id, '_efc_' . $key, $value);
    }
}
add_action('save_post_page', 'efc_save_soutenir_fields');

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
