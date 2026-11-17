<?php get_header(); ?>
<!-- Météo -->
<?php
$meteo_title        = get_field('meteo-title');
$meteo_text         = get_field('meteo-text');
$meteo_announcement = get_field('meteo-announcement');
$meteo_date         = get_field('meteo-date');

if ($meteo_title || $meteo_text || $meteo_announcement) : ?>
    <section>
        <div>

            <!-- titre et texte -->
            <div>
                <?php if ($meteo_title) : ?>
                    <h2><?php echo esc_html($meteo_title); ?></h2>
                <?php endif; ?>

                <?php if ($meteo_date) : ?>
                    <p><?php echo esc_html($meteo_date); ?></p>
                <?php endif; ?>

                <?php if ($meteo_text) : ?>
                    <p><?php echo nl2br(esc_html($meteo_text)); ?></p>
                <?php endif; ?>
            </div>

            <!-- annonce -->
            <div>
                <?php if ($meteo_announcement) : ?>
                    <p><?php echo nl2br(esc_html($meteo_announcement)); ?></p>
                <?php endif; ?>
            </div>

        </div>

        <!-- état des routes -->
        <div>
            <iframe width="100%" height="450"
                src="https://embed.waze.com/fr/iframe?zoom=11&amp;lat=48.8486&amp;lon=-67.5333&amp;ct=livemap"
                title="État des routes en Matanie"
                loading="lazy"
                allowfullscreen></iframe>

            <!-- lien vers les conditions routières officielles -->
            <a href="https://www.quebec511.info/fr/Carte/Default.aspx" target="_blank" rel="noopener noreferrer">
                Conditions routières officielles
            </a>

        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>