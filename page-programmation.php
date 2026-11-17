
<?php
get_header(); ?>
<p style="color:red">TEST 1 : template chargé</p>

<main class="programmation">
<h1><?php the_title(); ?></h1>
<div class= "grille-cards">
    <?php 
    $q = new WP_Query([
        'post_type' => 'programme',
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'date',
        'order' => 'ASC'
    ]);
    echo '<p style="color:red">Cards trouvées : ' . $q->found_posts . '</p>';

    while ($q->have_posts()) : $q->the_post();
        get_template_part('templates/template-programmation');
        endwhile;
        wp_reset_postdata();
    ?>

    
</div>
</main>


<?php get_footer(); ?>

