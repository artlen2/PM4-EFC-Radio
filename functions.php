<?php

if (! defined('ABSPATH')) {
    exit; // Bloque l'accès si WordPress n'est pas chargé
}

// Ajouter des "supports" de thème
function basetheme_setup()
{

    // Générer automatiquement la balise "title" pour chaque page
    add_theme_support('title-tag');

    // Permettre l'ajout d'un image de premier plan pour les articles et les pages
    add_theme_support('post-thumbnails');

    // Support de logo
    add_theme_support('custom-logo', array(
        'width' => '100',
        "height" => '100'
    ));

    // Support de Header (Image de la bannière)
    add_theme_support('custom-header', array(
        'default-image' => get_template_directory_uri() . "/images/default.jpg"
    ));

    // À tester ...
    //add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );

    // Déclarer un menu (Doit être créé dans l'admin de Wordpress)
    register_nav_menus(array(
        'main-menu' => __('Menu principal')
    ));
}
add_action('after_setup_theme', 'basetheme_setup');

// Placer dans la file les fichiers à charger
function basetheme_enqueue()
{

    // Fichier compilé par Tailwind (jamais main.css, qui est la source)
    $tailwind_path = get_template_directory() . '/styles/tailwind.css';
    wp_enqueue_style(
        'tailwindcss',
        get_template_directory_uri() . '/styles/tailwind.css',
        array(),
        file_exists($tailwind_path) ? filemtime($tailwind_path) : null
    );
}
add_action('wp_enqueue_scripts', 'basetheme_enqueue');
