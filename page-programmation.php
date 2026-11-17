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

    <?php
if (current_user_can('manage_options')) {
    echo '<pre style="background:#fff;padding:1rem;">';
    echo 'Trouvés : ' . $programmes->found_posts . "\n";
    echo 'Requête : ' . esc_html($programmes->request) . "\n";
    echo 'Types enregistrés : ' . implode(', ', get_post_types(['public' => true])) . "\n";
    echo 'Template part : ' . (locate_template('templates/template-programmation.php') ?: 'INTROUVABLE');
    echo '</pre>';
}
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
