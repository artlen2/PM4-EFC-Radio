<?php
/*
 * Single — Concours
 */
get_header(); ?>

<main class="concours-single">
    <?php while (have_posts()) : the_post();

        $etiquette = get_field('etiquette');
        $date_fin = get_field('date_fin');
        $prix = get_field('prix');
        $partenaire = get_field('partenaire');
    ?>

        <a href="<?php echo esc_url(home_url('/communaute/')); ?>">Retour aux concours</a>

        <article>
            <header>
                <?php if ($etiquette) : ?>
                    <span class="etiquette"><?php echo esc_html($etiquette); ?></span>
                <?php endif; ?>
                <h1><?php the_title(); ?></h1>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php endif; ?>

            <div class="concours-contenu">
                <?php the_content(); ?>
            </div>

            <dl>
                <?php if ($prix) : ?>
                    <dt>Prix à gagner</dt>
                    <dd><?php echo esc_html($prix); ?></dd>
                <?php endif; ?>
                <?php if ($partenaire) : ?>
                    <dt>Offert par</dt>
                    <dd><?php echo esc_html($partenaire); ?></dd>
                <?php endif; ?>
                <?php if ($date_fin) : ?>
                    <dt>Se termine le</dt>
                    <dd><?php echo esc_html($date_fin); ?></dd>
                <?php endif; ?>
            </dl>
        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>