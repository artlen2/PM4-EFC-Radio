<?php
/*
 * Single — Programme
 */
get_header();

while (have_posts()) :
    the_post();
    $title = function_exists('get_field') ? get_field('title') : '';
    $title = $title ?: get_the_title();
    $date = function_exists('get_field') ? (get_field('date') ?: get_field('jour')) : '';
    $time_start = function_exists('get_field') ? (get_field('time_start') ?: get_field('heure_debut')) : '';
    $time_end = function_exists('get_field') ? (get_field('time_end') ?: get_field('heure_fin')) : '';
    $image = function_exists('get_field') ? get_field('image') : null;
    $description = function_exists('get_field') ? get_field('description') : '';

    if (is_array($date)) {
        $date = implode(', ', array_map('ucfirst', $date));
    }

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
    <main class="min-h-[60vh] bg-black px-4 py-12 text-white sm:px-6">
        <div class="mx-auto max-w-5xl">
            <a href="<?php echo esc_url($back_url); ?>"
                style="display: inline-flex; margin-bottom: 2rem;"
                class="bg-orange text-white font-bold no-underline text-center p-4 rounded-[30px] hover:bg-yellow hover:text-black hover:scale-105 ease-in-out duration-300">
                Retour aux programmes
            </a>

            <article class="grid grid-cols-1 overflow-hidden text-white md:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]">
                <?php if ($image_id || $image_url) : ?>
                    <figure class="min-h-56 min-w-0 overflow-hidden p-2 md:min-h-full">
                        <?php if ($image_id) : ?>
                            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                'alt' => $image_alt,
                                'class' => 'block size-full min-h-56 transform-none rounded-[1.75rem] object-cover object-center md:min-h-80',
                                'loading' => 'eager',
                            ]); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($image_alt); ?>"
                                class="block size-full min-h-56 transform-none rounded-[1.75rem] object-cover object-center md:min-h-80"
                                loading="eager">
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <div class="flex min-w-0 flex-col items-start justify-center gap-3 p-5 text-left sm:p-8">
                    <h1 class="font-title text-3xl font-black uppercase leading-tight text-white sm:text-4xl">
                        <?php echo esc_html($title); ?>
                    </h1>

                    <?php if ($date || $time_start || $time_end) : ?>
                        <p class="font-bold text-orange">
                            <?php if ($date) : ?>
                                <?php echo esc_html($date); ?>
                            <?php endif; ?>
                            <?php if ($time_start) : ?>
                                <?php echo $date ? ' · ' : ''; ?>
                                <?php echo esc_html($time_start); ?>
                                <?php if ($time_end) : ?>
                                    - <?php echo esc_html($time_end); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <div class="leading-relaxed text-white/75">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
