<?php

// NOTE : Cette fonction doit se retrouver dans le fichier "functions.php" ou être liée à celui-ci.


// Remplacer le nom de la fonction avec le nom de votre custom post type

function my_custom_post_type() {
    // Remplacer cpt par le nom du custom post type à créer
    $labels = array(
      'name'               => _x( 'Cpt au pluriel', 'post type general name' ),
      'singular_name'      => _x( 'Cpt au singulier', 'post type singular name' ),
      'add_new'            => _x( 'Ajouter nouveau', 'book' ),
      'add_new_item'       => __( 'cpt au singulier' ),
      'edit_item'          => __( 'Modifier pt' ),
      'new_item'           => __( 'Nouveau cpt' ),
      'all_items'          => __( 'Tous les cpt' ),
      'view_item'          => __( 'Voir cpt' ),
      'search_items'       => __( 'Rechercher cpt' ),
      'not_found'          => __( 'cpt non trouvé' ),
      'not_found_in_trash' => __( 'Pas de cpt trouvé dans la corbeille' ), 
      'parent_item_colon'  => "’",
      'menu_name'          => 'cpt pluriel avec lettre majuscule'
    );
    $args = array(
      'labels'        => $labels,// Textes apparaissant dans l'admin
      'description'   => 'Description du cpt', // Brève dexcription du cpt
      'taxonomies' => array( 'category', 'post_tag'), // Taxonomies utilisées pas le post type
      'public'        => true, // Ajuste une série d'options (Entre autres : queryable)
      'menu_position' => 5, // Position dans le menu gauche de worpdress (5 = après articles)
      'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt'), // Contrôles disponibles pour ce cpt
      'has_archive'   => true, // Disponibles via "archives" (Règles de réécriture)
      'menu_icon' => 'dashicons-open-folder' // Icone apparaissant dans l'admin (https://developer.wordpress.org/resource/dashicons/#format-quote)
    );
    register_post_type( 'cpt singulier sans caractères spéciaux', $args ); 
  }
  add_action( 'init', 'my_custom_post_type' );

?>