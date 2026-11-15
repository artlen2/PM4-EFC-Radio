<?php
/*
 * Single — Programme
 */
get_header(); ?>

<main class="programme-single">
    <?php while (have_posts()) : the_post();

        $image = get_field('image');
        $description = get_field('description');
        $jours = get_field('jour');
        $debut = get_field('heure_debut');
        $fin = get_field('heure_fin');
        $animateurs = get_field('animateurs');
    ?>

        <a href="<?php echo esc_url(home_url('/programmation/')); ?>">Retour à la programmation</a>

        <article>
            <header>
                <h1><?php the_title(); ?></h1>

                <?php if ($jours || $debut) : ?>
                    <p class="programme-horaire">
                        <?php if ($jours) : ?>
                            <?php echo esc_html(implode(', ', array_map('ucfirst', $jours))); ?>
                        <?php endif; ?>
                        <?php if ($debut) : ?>
                            <span><?php echo esc_html($debut); ?><?php echo $fin ? ' à ' . esc_html($fin) : ''; ?></span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </header>

            <?php if ($image) : ?>
                <img src="<?php echo esc_url($image['url']); ?>"
                    alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>">
            <?php endif; ?>

            <?php if ($description) : ?>
                <section>
                    <h2>Description</h2>
                    <p><?php echo nl2br(esc_html($description)); ?></p>
                </section>
            <?php endif; ?>

            <?php if ($animateurs) : ?>
                <section>
                    <h2>Animateurs</h2>
                    <ul>
                        <?php foreach ($animateurs as $animateur) :
                            $photo = get_field('photo', $animateur->ID); ?>
                            <li>
                                <?php if ($photo) : ?>
                                    <img src="<?php echo esc_url($photo['sizes']['thumbnail']); ?>"
                                        alt="<?php echo esc_attr($animateur->post_title); ?>">
                                <?php endif; ?>
                                <span><?php echo esc_html($animateur->post_title); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if (have_rows('episodes')) : ?>
                <section>
                    <h2>Épisodes précédents</h2>
                    <ul>
                        <?php while (have_rows('episodes')) : the_row();
                            $titre = get_sub_field('titre');
                            $date  = get_sub_field('date');
                            $audio = get_sub_field('audio'); ?>
                            <li>
                                <span><?php echo esc_html($date); ?></span>
                                <?php if ($audio) : ?>
                                    <a href="<?php echo esc_url($audio); ?>"><?php echo esc_html($titre); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html($titre); ?>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </section>
            <?php endif; ?>
        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>