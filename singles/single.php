<?php get_header(); ?>

<article class="flex flex-col md:flex-row gap-10">
    <div class="md:w-1/2">
        <h2 class="text-3xl font-bold mb-4"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="md:w-1/2">
            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) ); ?>
        </div>
    <?php endif; ?>
</article>

<?php get_footer(); ?>
