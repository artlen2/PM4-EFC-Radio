<?php
/*
 * Single — Programme
 */
get_header();

while (have_posts()) :
    the_post();
    $category = get_field('category');
    $image    = get_field('image');
    $extrait  = get_field('extrait');

    $img_url = '';
    $img_alt = '';
    if ($image) {
        $img_url = is_array($image) ? $image['url'] : $image;
        $img_alt = is_array($image) && !empty($image['alt']) ? $image['alt'] : get_the_title();
    }

    $page_programmes = get_page_by_path('attraits');
    $url_programmes  = $page_programmes ? get_permalink($page_programmes->ID) : home_url();
    $back_url        = wp_get_referer() ?: $url_programmes;
?>

    <article class="max-w-4xl mx-auto px-6 py-16">

        <a href="<?php echo esc_url($back_url); ?>"
            class="inline-flex items-center gap-2 text-sm font-semibold text-brown hover:underline mb-10">
            ← Retour aux programmes
        </a>

        <?php if ($category) : ?>
            <p class="text-xs font-semibold uppercase tracking-widest text-brown mb-3">
                <?php echo esc_html($category); ?>
            </p>
        <?php endif; ?>

        <h1 class="text-4xl md:text-5xl font-title font-black text-title-ink leading-tight mb-8">
            <?php the_title(); ?>
        </h1>

        <?php if ($img_url) : ?>
            <figure class="rounded-2xl overflow-hidden mb-10 shadow-sm border border-cream/60">
                <img src="<?php echo esc_url($img_url); ?>"
                    alt="<?php echo esc_attr($img_alt); ?>"
                    class="w-full object-cover max-h-130"
                    loading="eager">
            </figure>
        <?php endif; ?>

        <?php if ($extrait) : ?>
            <div class="text-ink-light text-lg leading-relaxed">
                <?php echo nl2br(esc_html($extrait)); ?>
            </div>
        <?php endif; ?>

    </article>
<?php endwhile; ?>

<?php get_footer(); ?>