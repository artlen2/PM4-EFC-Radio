<?php
/* Template Name: Programmation */
get_header(); ?>

<section class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="mb-8 block text-4xl font-archivo font-bold text-orange"><?php the_title(); ?></h1>

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
        <div class="grid gap-8">
            <?php
            $program_index = 0;
            while ($programmes->have_posts()) :
                $programmes->the_post();
                get_template_part('templates/template-programmation', null, [
                    'reverse' => $program_index % 2 === 0,
                ]);
                $program_index++;
            endwhile;
            ?>
        </div>
    <?php else : ?>
        <p class="">Aucun programme ici à date.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</section>


<?php get_footer(); ?>
