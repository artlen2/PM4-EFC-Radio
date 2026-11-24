<?php
/*
 * Single — Programme
 */
get_header();

while (have_posts()) :
    the_post();
    $title = get_field('title') ?: get_the_title();
    $image    = get_field('image');
    $description  = get_field('description');

    $img_url = '';
    $img_alt = '';
    if ($image) {
        $img_url = is_array($image) ? $image['url'] : $image;
        $img_alt = is_array($image) && !empty($image['alt']) ? $image['alt'] : get_the_title();
    }

    $page_programmation = get_page_by_path('programmation');
    $url_programmation  = $page_programmation ? get_permalink($page_programmation->ID) : home_url();
    $back_url        = wp_get_referer() ?: $url_programmation;
?>

    <article class="max-w-4xl mx-auto px-6 py-16">

        <a href="<?php echo esc_url($back_url); ?>"
            class="inline-flex items-center gap-2 font-semibold text-brown hover:underline mb-10">
             Retour aux programmes
        </a>

        <h1 class=" font-title font-black text-title-ink leading-tight mb-8 text-left">
            <?php echo esc_html($title); ?>
        </h1>

        <?php if ($img_url) : ?>
            <figure class="rounded-2xl overflow-hidden mb-10 shadow-sm border border-cream/60">
                <img src="<?php echo esc_url($img_url); ?>"
                    alt="<?php echo esc_attr($img_alt); ?>"
                    class="w-full object-cover max-h-130"
                    loading="eager">
            </figure>
        <?php endif; ?>

        <?php if ($description) : ?>
            <div class="text-ink-light text-lg leading-relaxed">
                <?php echo wp_kses_post($description); ?>
            </div>
        <?php endif; ?>

    </article>
<?php endwhile; ?>

<?php get_footer(); ?>