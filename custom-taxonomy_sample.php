<?php

// NOTE : Cette fonction doit se retrouver dans le fichier "functions.php" ou être liée à celui-ci.

// Remplacer le nom de la fonction avec le nom de votre custom taxonomy

function my_custom_taxonomy() {
    // Remplacer taxo par le nom de la taxonomie à créer
    $labels = array(
    
            'name'              => _x( 'Taxo au pluriel', 'taxonomy general name' ),
            'singular_name'     => _x( 'Taxo au singulier', 'taxonomy singular name' ),
            'search_items'      => __( 'Rechercher taxos' ),
            'all_items'         => __( 'Toutes les taxos' ),
            'parent_item'       => __( 'Taxo parent' ),
            'parent_item_colon' => __( 'Taxo parent:' ),
            'edit_item'         => __( 'Modifier taxo' ), 
            'update_item'       => __( 'Mettre à jour taxo' ),
            'add_new_item'      => __( 'Ajouter nouveau taxo' ),
            'new_item_name'     => __( 'Nouveau taxo' ),
            'menu_name'         => __( 'Taxo' ),
            );
    $args = array(
    'labels' => $labels, // Textes apparaissant dans l'admin
    'hierarchical' => true, // Pour que la taxonomie agisse comme "Catégorie"
    'public' => true
    );

register_taxonomy( 'taxo sans caractères spéciaux', array('post-types utilisant cette taxo'), $args );

}

add_action( 'init', 'my_custom_taxonomy', 0 );


?>