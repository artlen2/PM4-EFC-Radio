<?php
$get_programme_field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$date = $get_programme_field('date') ?: $get_programme_field('jour');
$time_start = $get_programme_field('time_start') ?: $get_programme_field('heure_debut');
$time_end = $get_programme_field('time_end') ?: $get_programme_field('heure_fin');
$title = $get_programme_field('title') ?: get_the_title();
$description = $get_programme_field('description') ?: get_the_excerpt();
$image = $get_programme_field('image');
$reverse = !empty($args['reverse']);
$has_image = $image || has_post_thumbnail();

if (is_array($date)) {
    $date = implode(', ', array_map('ucfirst', $date));
}

$image_alt = get_the_title();
$figure_order = $reverse ? 'order-2' : 'order-1';
$content_order = $reverse ? 'order-1' : 'order-2';
$grid_columns = !$has_image
    ? 'grid-cols-1'
    : ($reverse
        ? 'grid-cols-[minmax(0,1fr)_minmax(0,1fr)] md:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]'
        : 'grid-cols-[minmax(0,1fr)_minmax(0,1fr)] md:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]');
?>

<article class="grid grid-cols-1 overflow-hidden rounded-[2.5rem] bg-black text-white md:min-h-40 <?php echo esc_attr($grid_columns); ?>">
    <?php if ($image) : ?>
        <figure class="<?php echo esc_attr($figure_order); ?> min-h-36 min-w-0 overflow-hidden p-2 md:min-h-0">
            <?php if (is_array($image) && !empty($image['ID'])) : ?>
                <?php
                $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt;
                echo wp_get_attachment_image(
                    absint($image['ID']),
                    'large',
                    false,
                    ['alt' => $image_alt, 'class' => 'block size-full rounded-[1.75rem] object-cover']
                );
                ?>
            <?php elseif (is_numeric($image)) : ?>
                <?php
                echo wp_get_attachment_image(
                    absint($image),
                    'large',
                    false,
                    ['alt' => $image_alt, 'class' => 'block size-full rounded-[1.75rem] object-cover']
                );
                ?>
            <?php elseif (is_array($image) && !empty($image['url'])) : ?>
                <?php $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt; ?>
                <img class="block size-full rounded-[1.75rem] object-cover" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php elseif (is_string($image)) : ?>
                <img class="block size-full rounded-[1.75rem] object-cover" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php endif; ?>
        </figure>
    <?php elseif (has_post_thumbnail()) : ?>
        <figure class="<?php echo esc_attr($figure_order); ?> min-h-36 min-w-0 overflow-hidden p-2 md:min-h-0">
            <?php the_post_thumbnail('large', ['alt' => $image_alt, 'class' => 'block size-full rounded-[1.75rem] object-cover']); ?>
        </figure>
    <?php endif; ?>

    <div class="<?php echo esc_attr($content_order); ?> flex min-w-0 flex-col items-start justify-center gap-2 p-3 text-left sm:gap-3 sm:p-5 md:p-8 <?php echo $reverse ? 'items-end text-right' : ''; ?>">
        <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
            <h2 class="font-title text-sm font-black uppercase leading-tight text-white md:text-base"><?php echo esc_html($title); ?></h2>
            <?php if ($date || $time_start || $time_end) : ?>
                <p class="text-xs font-bold text-orange">
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
        </div>

      <!-- partie description -->
        <?php if ($description) : ?>
            <div class="text-[0.65rem] leading-relaxed text-white/75 md:text-xs"><?php echo wp_kses_post($description); ?></div>
        <?php endif; ?>

        <!-- bouton voir plus -->
        <a class="inline-flex min-h-8 items-center justify-center rounded-full bg-orange px-3 py-1 text-[0.65rem] font-extrabold text-white transition-colors duration-300 hover:bg-yellow hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange sm:px-5 sm:py-2 sm:text-xs" href="<?php echo esc_url(get_permalink()); ?>">
            Voir plus
        </a>
    </div>
</article>
