<?php
/* Template Name: Programmation */
get_header(); ?>

<main class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="font-archivo font-bold text-4xl text-align block text-orange"><?php the_title(); ?></h1>

    <?php
    $programmes = new WP_Query([
        'post_type' => 'programme',
        'posts_per_page' => -1,
        'orderby' => [
            'menu_order' => 'ASC',
            'date' => 'ASC',
        ],
    ]); // Query pour appeler tout les programmes et les faires apparaitres dans l'ordre et la date et du menu (lun-ven)
    ?>

 
    <?php if ($programmes->have_posts()) : ?>
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 size: 100% rounded-[40px] overflow-hidden justify-center items-center margin: 0 auto;">
            <?php
            while ($programmes->have_posts()) :
                $programmes->the_post();
                get_template_part('templates/template-programmation');
            endwhile;
            ?>
        </div>
    <?php else : ?>
        <p class="">Aucun programme ici à date.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</main>


<?php get_footer(); ?>
