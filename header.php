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
            <?php if (is_front_page()) : ?>
                <!-- titre et boule -->
                <div class="absolute left-1/2 top-[10%] z-10 aspect-[1440/900] w-[min(77vw,130svh)] -translate-x-1/2 lg:scale-115">
                    <img class="size-full" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/title.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <img class="disco-swing absolute left-[80%] bottom-[20%] w-[17%] max-w-none" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/disco-ball.png'); ?>" alt="">
                </div>


            <?php else : ?>
                <!-- titre et boule -->
                <div class="absolute left-1/2 top-[10%] z-10 aspect-[1440/900] w-[min(77vw,130svh)] -translate-x-1/2 lg:scale-115">
                    <img class="size-full" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/title.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <img class="disco-swing absolute left-[80%] bottom-[20%] w-[17%] max-w-none" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/disco-ball.png'); ?>" alt="">
                </div>

                <!-- titre des autres pages -->
                <div class="absolute inset-x-0 bottom-[22%] z-10 px-4 md:pl-[20%] md:pr-8">
                    <h1 class="inter max-w-[min(90vw,48rem)] break-words text-4xl -rotate-3 font-extrabold leading-tight text-violet sm:text-5xl md:text-6xl">
                        <?php
                        if (is_singular()) {
                            echo esc_html(get_the_title());
                        } elseif (is_home()) {
                            echo esc_html(get_the_title(get_option('page_for_posts')));
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

    </div>