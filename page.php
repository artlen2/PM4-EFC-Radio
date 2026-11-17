<?php get_header(); ?>

<article class="flex flex-col items-center justify-center text-center gap-4 m-20">
    <h2 class="text-3xl font-bold mb-4"><?php the_title(); ?></h2>
    <?php the_content(); ?>
</article>

<?php get_footer(); ?>
    