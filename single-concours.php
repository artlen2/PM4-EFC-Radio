<?php
/*
 * Single — Concours
 */
get_header();

while (have_posts()) :
    the_post();
    $date_debut = get_field('date_debut');
    $organizer  = get_field('organizer');
    $place_raw  = get_field('place');
    $place_name = '';

    if (is_object($place_raw) && isset($place_raw->post_title)) {
        $place_name = $place_raw->post_title;
    } elseif (is_array($place_raw) && isset($place_raw['post_title'])) {
        $place_name = $place_raw['post_title'];
    } elseif (is_numeric($place_raw)) {
        $place_name = get_the_title((int) $place_raw);
    } elseif (is_string($place_raw)) {
        $place_name = $place_raw;
    }

    $page_concours = get_page_by_path('evenements');
    $url_concours  = $page_concours ? get_permalink($page_concours->ID) : home_url();
    $back_url      = wp_get_referer() ?: $url_concours;
?>

    <article class="max-w-4xl mx-auto px-6 py-16">

        <a href="<?php echo esc_url($back_url); ?>"
            class="inline-flex items-center gap-2 text-sm font-semibold text-brown hover:underline mb-10">
            ← Retour aux concours
        </a>

        <?php if ($date_debut) : ?>
            <time datetime="<?php echo esc_attr($date_debut); ?>"
                class="text-xs font-semibold uppercase tracking-widest text-brown mb-3 block">
                <?php
                $dt = DateTime::createFromFormat('Y-m-d H:i:s', $date_debut);
                echo esc_html($dt ? date_i18n('j F Y, H\hi', $dt->getTimestamp()) : $date_debut);
                ?>
            </time>
        <?php endif; ?>

        <h1 class="text-4xl md:text-5xl font-title font-black text-title-ink leading-tight mb-6">
            <?php the_title(); ?>
        </h1>

        <?php if ($place_name || $organizer) : ?>
            <ul class="flex flex-col sm:flex-row gap-3 mb-8 list-none p-0 m-0">
                <?php if ($place_name) : ?>
                    <li class="text-sm text-ink-light">
                        <span class="font-semibold text-title-ink">Lieu :</span>
                        <?php echo esc_html($place_name); ?>
                    </li>
                <?php endif; ?>
                <?php if ($organizer) : ?>
                    <li class="text-sm text-ink-light">
                        <span class="font-semibold text-title-ink">Organisateur :</span>
                        <?php echo esc_html($organizer); ?>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <?php if (has_post_thumbnail()) : ?>
            <figure class="rounded-2xl overflow-hidden mb-10 shadow-sm border border-cream/60">
                <?php the_post_thumbnail('large', [
                    'class'   => 'w-full object-cover max-h-[520px]',
                    'loading' => 'eager',
                ]); ?>
            </figure>
        <?php endif; ?>

        <?php if (get_the_content()) : ?>
            <div class="text-ink-light text-lg leading-relaxed content">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>

    </article>
<?php endwhile; ?>

<?php get_footer(); ?>