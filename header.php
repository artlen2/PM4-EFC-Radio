<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr(get_bloginfo('charset')) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Note : La balise <title> est généré par le 
    support de  thème "title-tag". Voir functions.php -->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >
    <?php wp_body_open(); ?>

<!-- Le customizer est utilisé pour personaliser 
     l'image de la bannière (Voir functions.php) -->
    <header style="
    background-image: url(<?php header_image()?>);
    background-size: cover;
    background-position: center;
    ">
        <div class="container">
            <h1><a href="<?php echo esc_url( home_url('/') ); ?>">
                <?php echo esc_html( get_bloginfo('name')); ?></a>
            </h1>
            <p class="header-slogan"><?php echo esc_html (get_bloginfo('description')) ?></p>
        </div>
       
    </header>
    <div class="nav-container">
        <nav class="container">
            <div class="logo">
            <a href="<?php echo esc_url( home_url('/') ); ?>">
                   <?php the_custom_logo(); ?>
          </a>
            </div>
            <div class="flex menu-container">
            <?php 
                $args = array(
                    'theme_location' => 'main-menu' // Référence au menu déclaré dans functions.php
                );
                wp_nav_menu($args) 
            ?>
            <!-- NOTE : Le résultat de recherche sera affiché via le template "search.php" -->  
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <input name="s" type="text" placeholder="Rechercher">
            </form>
            </div>
        </nav>
    </div>
    <main class="container">