<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="flex items-center h-72 md:h-96 bg-cover bg-center text-white"
            style="background-image: url(<?php header_image(); ?>);">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            </h1>
            <p><?php bloginfo( 'description' ); ?></p>
        </div>
    </header>

    <div class="bg-neutral-800 text-neutral-100">
        <nav class="container mx-auto px-4 py-2 flex flex-col md:flex-row items-center justify-between gap-4">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="[&_img]:size-9">
                <?php the_custom_logo(); ?>
            </a>
            <div class="flex flex-col md:flex-row items-center gap-4">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'     => 'flex gap-1 [&_a]:block [&_a]:px-2 [&_a]:py-2 [&_a:hover]:bg-neutral-100 [&_a:hover]:text-neutral-800',
                ) );
                ?>
                <!-- Résultats affichés par search.php -->
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                    <input name="s" type="search" placeholder="Rechercher" class="px-2 py-1 rounded bg-white text-neutral-800">
                </form>
            </div>
        </nav>
    </div>

    <main class="container mx-auto px-4 py-12">
