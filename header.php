<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- Accueil -->
    <div class="relative">

        <header class="absolute inset-x-0 top-0 bg-transparent z-30 text-beige">
            <nav class="flex items-center justify-between gap-4 px-4 py-3 md:px-8">

                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0 [&_img]:size-12 [&_img]:object-contain">
                    <?php
                    if (has_custom_logo()) {
                        echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'medium', false, ['alt' => get_bloginfo('name')]);
                    } elseif (has_site_icon()) {
                        echo '<img src="' . esc_url(get_site_icon_url(192)) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
                    }
                    ?>
                </a>

                <!-- Menu principal -->
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'     => 'flex flex-wrap items-center gap-4 md:gap-10 text-xl md:text-2xl font-extrabold [&_a]:block [&_a]:px-2 [&_a]:py-1 [&_a]:transition-[color] [&_a]:duration-300 [&_a]:ease-in-out [&_a:hover]:text-black [&_.current-menu-item>a]:text-purple [&_.current-menu-item>a]:-rotate-6',
                ));
                ?>

                <!-- placeholder en attendant l'icône -->
                <a href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="Rechercher"
                    class="">Icon</a>
            </nav>
        </header>



        <!-- hero -->
        <section class="relative h-svh overflow-hidden">
            <!-- bg -->
            <img class="absolute inset-0 size-full object-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/background.png'); ?>" alt="">
            <!-- titre et boule -->
            <div class="absolute left-1/2 top-[10%] z-10 aspect-[1440/900] w-[min(77vw,130svh)] -translate-x-1/2 lg:scale-115">
                <img class="size-full" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/title.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <img class="disco-swing absolute left-[80%] bottom-[20%] w-[17%] max-w-none" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/disco-ball.png'); ?>" alt="">
            </div>
            <?php if (is_front_page()) : ?>


                <!-- description page d'accueil du site -->
                <div class="absolute inset-x-0 bottom-[10%] z-10 px-4 md:pl-[21.5%] md:pr-8">
                    <div class="inter text-xl md:text-xl font-extrabold text-violet max-w-2xl">
                        <?php echo apply_filters('the_content', get_post_field('post_content', get_queried_object_id())); ?>
                    </div>
                </div>
            <?php else : ?>

                <!-- titre des autres pages -->
                <div class="absolute inset-x-0 bottom-[22%] z-10 px-4 md:pl-[20%] md:pr-8">
                    <h1 class="inter text-6xl -rotate-3 font-extrabold text-violet max-w-3xl">
                        <?php
                        if (is_singular()) {
                            echo esc_html(get_the_title());
                        } elseif (is_search()) {
                            echo 'Recherche : ' . esc_html(get_search_query());
                        } elseif (is_404()) {
                            echo 'Page introuvable';
                        } else {
                            echo esc_html(wp_strip_all_tags(get_the_archive_title()));
                        }
                        ?>
                    </h1>
                </div>
            <?php endif; ?>
        </section>
        <!-- jaune du bas de la vidéo -->
        <svg class="-mt-px block h-16 w-full rotate-180 text-[#fdbe00] md:h-24" viewBox="0 0 1440 100"
            preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor"
                d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
        </svg>


        <main class="container mx-auto px-4 py-12">