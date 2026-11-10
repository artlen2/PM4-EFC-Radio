<?php
if (! defined('ABSPATH')) {
    exit; // Bloque l'accès si WordPress n'est pas chargé 
}

// Supports du thème 
function fmi_theme()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'width'               => 200,
        'height'              => 200,
        'flex-width'          => true,
        'flex-height'         => true,
        'unlink-homepage-logo' => true
    ]);
    add_theme_support('html5', ['search-form', 'style', 'script', 'meta']);

    register_nav_menu('main-menu', 'Menu principal');
}
add_action('after_setup_theme', 'fmi_theme');

// Fichiers CSS / JS 
function efcradio_enqueue()
{
    $css = '/style.css';
    wp_enqueue_style(
        'efcradio-tailwind',
        get_template_directory_uri() . $css,
        array(),
        file_exists(get_template_directory() . $css) ? filemtime(get_template_directory() . $css) : null
    );
}
add_action('wp_enqueue_scripts', 'efcradio_enqueue');

// if ( ! defined( 'ABSPATH' ) ) {
//     exit; // Bloque l'accès si WordPress n'est pas chargé
// }

// // Supports du thème
// function efcradio_setup() {
//     add_theme_support( 'title-tag' );
//     add_theme_support( 'post-thumbnails' );

//     add_theme_support( 'custom-logo', array(
//         'width'  => 100,
//         'height' => 100,
//     ) );

//     add_theme_support( 'custom-header', array(
//         'default-image' => get_template_directory_uri() . '/assets/images/default.jpg',
//     ) );

//     register_nav_menus( array(
//         'main-menu' => __( 'Menu principal' ),
//     ) );
// }
// add_action( 'after_setup_theme', 'efcradio_setup' );

// // Fichiers CSS / JS
// function efcradio_enqueue() {
//     $css = '/style.css';
//     wp_enqueue_style(
//         'efcradio-tailwind',
//         get_template_directory_uri() . $css,
//         array(),
//         file_exists( get_template_directory() . $css ) ? filemtime( get_template_directory() . $css ) : null // évite le cache après chaque build
//     );
// }
// add_action( 'wp_enqueue_scripts', 'efcradio_enqueue' );
