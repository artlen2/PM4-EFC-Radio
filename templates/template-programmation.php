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

if (is_array($date)) {
    $date = implode(', ', array_map('ucfirst', $date));
}

$image_alt = get_the_title();
$figure_order = $reverse ? 'md:order-2' : 'md:order-1';
$content_order = $reverse ? 'md:order-1' : 'md:order-2';
?>
<!-- pour change l'ordre du display -->

<article class="grid grid-cols-1 overflow-hidden rounded-[2.5rem] bg-black text-white md:min-h-40 md:grid-cols-[minmax(0,1.35fr)_minmax(13rem,0.65fr)]">
    <?php if ($image) : ?>
        <figure class="<?php echo esc_attr($figure_order); ?> order-1 min-h-48 overflow-hidden md:min-h-0">
            <?php if (is_array($image) && !empty($image['ID'])) : ?>
                <?php
                $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt;
                echo wp_get_attachment_image(
                    absint($image['ID']),
                    'large',
                    false,
                    ['alt' => $image_alt, 'class' => 'block size-full object-cover']
                );
                ?>
            <?php elseif (is_numeric($image)) : ?>
                <?php
                echo wp_get_attachment_image(
                    absint($image),
                    'large',
                    false,
                    ['alt' => $image_alt, 'class' => 'block size-full object-cover']
                );
                ?>
            <?php elseif (is_array($image) && !empty($image['url'])) : ?>
                <?php $image_alt = !empty($image['alt']) ? $image['alt'] : $image_alt; ?>
                <img class="block size-full object-cover" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php elseif (is_string($image)) : ?>
                <img class="block size-full object-cover" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php endif; ?>
        </figure>
    <?php elseif (has_post_thumbnail()) : ?>
        <figure class="<?php echo esc_attr($figure_order); ?> order-1 min-h-48 overflow-hidden md:min-h-0">
            <?php the_post_thumbnail('large', ['alt' => $image_alt, 'class' => 'block size-full object-cover']); ?>
        </figure>
    <?php endif; ?>

    <div class="<?php echo esc_attr($content_order); ?> order-2 flex flex-col items-start justify-center gap-3 p-6 text-left md:p-8 <?php echo $reverse ? 'md:items-end md:text-right' : ''; ?>">
        <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
            <h2 class="font-title text-base font-black uppercase leading-tight text-white"><?php echo esc_html($title); ?></h2>
            <?php if ($date || $time_start || $time_end) : ?>
                <p class="text-xs font-bold text-orange">
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
        </div>

        <?php if ($description) : ?>
            <div class="text-xs leading-relaxed text-white/75"><?php echo wp_kses_post($description); ?></div>
        <?php endif; ?>

        <a class="inline-flex min-h-8 items-center justify-center rounded-full bg-orange px-5 py-2 text-xs font-extrabold text-white transition-colors duration-300 hover:bg-yellow hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange" href="<?php echo esc_url(get_permalink()); ?>">
            Voir plus
        </a>
    </div>
</article>
