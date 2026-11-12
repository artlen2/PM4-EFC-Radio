<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>


    <header class="<?php echo is_front_page() ? 'absolute inset-x-0 top-0' : 'relative bg-orange'; ?> z-30 text-beige">
        <nav class="flex items-center justify-between gap-4 px-4 py-3 md:px-8">


            <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0 [&_img]:size-12">
                <?php the_custom_logo(); ?>
            </a>


            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container'      => false,
                'menu_class'     => 'flex flex-wrap items-center gap-2 md:gap-6 font-bold [&_a]:block [&_a]:px-2 [&_a]:py-1 [&_a:hover]:text-purple [&_.current-menu-item>a]:text-purple',
            ));
            ?>


            <a href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="Rechercher"
                class="block size-8 rounded-full border-2 border-dashed border-beige"></a>
        </nav>
    </header>

    <?php if (is_front_page()) : ?>


        <section class="relative h-svh overflow-hidden">
            <video class="absolute inset-0 size-full object-cover" autoplay muted loop playsinline>
                <source src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/hero-2mb.mp4" type="video/mp4">
            </video>
        </section>

        <svg class="-mt-px block h-16 w-full rotate-180 text-yellow md:h-24" viewBox="0 0 1440 100"
            preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor"
                d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
        </svg>
    <?php endif; ?>

    <main class="container mx-auto px-4 py-12">
