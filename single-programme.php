<?php
/*
 * Single — Programme
 */
get_header();

while (have_posts()) :
    the_post();
    $title = function_exists('get_field') ? get_field('title') : '';
    $title = $title ?: get_the_title();
    $image = function_exists('get_field') ? get_field('image') : null;
    $description = function_exists('get_field') ? get_field('description') : '';

    $image_url = '';
    $image_id = 0;
    $image_alt = get_the_title();

    if (is_array($image)) {
        $image_id = !empty($image['ID']) ? absint($image['ID']) : 0;
        $image_url = !empty($image['url']) ? $image['url'] : '';
        $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt;
    } elseif (is_numeric($image)) {
        $image_id = absint($image);
    } elseif (is_string($image)) {
        $image_url = $image;
    }

    $page_programmation = get_page_by_path('programmation');
    $url_programmation = $page_programmation ? get_permalink($page_programmation->ID) : home_url();
    $back_url = wp_get_referer() ?: $url_programmation;
?>
    <article class="mx-auto max-w-4xl px-6 py-16">
        <a href="<?php echo esc_url($back_url); ?>"
            class="bg-orange text-black font-bold no-underline text-center p-4 rounded-[30px] btn-press margin-bottom-10">
            Retour aux programmes
        </a>

        <h1 class="mb-8 text-left font-title font-orange leading-tight text-title-ink margin-top-15 text-4xl md:text-5xl">
            <?php echo esc_html($title); ?>
        </h1>

        <?php if ($image_id || $image_url) : ?>
            <figure class="mb-10 overflow-hidden rounded-2xl  shadow-sm">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'large', false, [
                        'alt' => $image_alt,
                        'class' => 'max-h-130 w-full object-cover',
                        'loading' => 'eager',
                    ]); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        class="max-h-130 w-full object-cover"
                        loading="eager">
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <?php if ($description) : ?>
            <div class="text-lg leading-relaxed text-ink-light">
                <?php echo wp_kses_post($description); ?>
            </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>
