<?php
/* Template Name: Programmation */
get_header(); ?>

<main class="programmation">
    <h1 class="programmation__title"><?php the_title(); ?></h1>

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
        <div class="programmation__cards">
            <?php
            while ($programmes->have_posts()) :
                $programmes->the_post();
                get_template_part('templates/template-programmation');
            endwhile;
            ?>
        </div>
    <?php else : ?>
        <p class="programmation__empty">Aucun programme ici à date.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</main>


<?php get_footer(); ?>
