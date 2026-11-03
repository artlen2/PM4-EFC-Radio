<?php get_header(); ?>


<section>

    <article class="flex gap-40">
        <div class="item-50">
            <h2><?php the_title(); ?></h2>
            <p><?php the_content(); ?></p>
        </div>
        <div class="item-50">
        <?php if(has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium', ['class' => 'fluid']);  ?>
        <?php endif; ?>
        </div>
        
    </article>

</section>

<?php get_footer(); ?>



