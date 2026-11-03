<?php if(have_posts()): ?>
<section class="flex wrap space-between">

    <?php while(have_posts()): the_post(); ?>

    <article class="excerpt">

        <h3><?php the_title(); ?></h3>

         <!-- NOTE :  the_excerpt(); affiche un extrait de l'article.
        Pour afficher l'article complet, utiliser the_content(); -->
        <p><?php the_excerpt();  ?></p>
       
        <!-- NOTE : the_permalink(); sert à récupérer le lien vers l'article -->
        <a href="<?php the_permalink(); ?>">Consulter l'article complet</a>
       
      
    </article>

    <?php endwhile; ?>
    </section>
<?php endif; ?>