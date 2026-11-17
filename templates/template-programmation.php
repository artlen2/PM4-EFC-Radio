<?php
$get_programme_field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$date = $get_programme_field('date') ?: $get_programme_field('jour');
$time_start = $get_programme_field('time_start') ?: $get_programme_field('heure_debut');
$time_end = $get_programme_field('time_end') ?: $get_programme_field('heure_fin');
$title = $get_programme_field('title') ?: get_the_title();
$description = $get_programme_field('description') ?: get_the_excerpt();
$image = $get_programme_field('image'); // débuggage : les cartes apparaissaient que si elles avaient une date donc on a appeler tout les programmes
$has_image = $image || has_post_thumbnail();

if (is_array($date)) {
    $date = implode(', ', array_map('ucfirst', $date));
}

$image_alt = get_the_title();
?> 

<article class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 rounded-lg overflow-hidden shadow-lg bg-black <?php echo $has_image ? '' : ' programme-card--no-image'; ?>">
    <?php if ($image) : ?>
        <figure class="bg-black">
            <?php if (is_array($image) && !empty($image['ID'])) : ?>
                <?php
                $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt;
                echo wp_get_attachment_image(
                    absint($image['ID']),
                    'large',
                    false,
                    ['alt' => $image_alt]
                );
                ?>
            <?php elseif (is_numeric($image)) : ?>
                <?php
                echo wp_get_attachment_image(
                    absint($image),
                    'large',
                    false,
                    ['alt' => $image_alt]
                );
                ?>
            <?php elseif (is_array($image) && !empty($image['url'])) : ?>
                <?php $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt; ?>
                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php elseif (is_string($image)) : ?>
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php endif; ?>
        </figure>
    <?php elseif (has_post_thumbnail()) : ?>
        <figure class="grid col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-1 overflow-hidden display: block max-width: 100% height: 100% min-height: 15rem object-fit: cover; ">
            <?php the_post_thumbnail('large', ['alt' => $image_alt]); ?>
        </figure>
    <?php endif; ?>

    <div class="flex flex-col gap-2 p-4 justify-content space-between text-orange ">
        <?php if ($date || $time_start || $time_end) : ?>
            <p class="mg-0.5 text-sm text-align-left font-weight:900 text-orange font-bold;">
                <?php if ($date) : ?>
                    <?php echo esc_html($date); ?>
                <?php endif; ?>
                <?php if ($time_start) : ?>
                    <?php echo $date ? ' · ' : ''; ?>
                    <?php echo esc_html($time_start); ?>
                    <?php if ($time_end) : ?>
                        – <?php echo esc_html($time_end); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <h2 class="text-sm text-white text-align-left"><?php echo esc_html($title); ?></h2>

        <?php if ($description) : ?>
            <div class="text-sm text-white text-align-left"><?php echo wp_kses_post($description); ?></div>
        <?php endif; ?>

        <a class="margin-top-50 text-sm text-white bg-orange hover:text-black hover:bg-violet transition-colors duration-300 rounded-md py-2 px-4 transition-colors duration-300 font-weight:800" href="<?php echo esc_url(get_permalink()); ?>">
            Voir plus 
        </a>
    </div>
</article>
