<?php if ( have_posts() ) : ?>
    <section class="grid md:grid-cols-2 gap-10">
        <?php while ( have_posts() ) : the_post(); ?>
            <article>
                <h3 class="text-xl font-bold mb-2"><?php the_title(); ?></h3>
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="underline">Consulter l'article complet</a>
            </article>
        <?php endwhile; ?>
    </section>
<?php else : ?>
    <p>Aucun résultat.</p>
<?php endif; ?>
