<?php
/*
 * Single — Programme
 */
get_header();

while (have_posts()) :
    the_post();
    $title = function_exists('get_field') ? get_field('title') : '';
    $title = $title ?: get_the_title();
    $animateur = function_exists('get_field') ? get_field('animateur') : '';
    $date = function_exists('get_field') ? (get_field('date') ?: get_field('jour')) : '';
    $time_start = function_exists('get_field') ? (get_field('time_start') ?: get_field('heure_debut')) : '';
    $time_end = function_exists('get_field') ? (get_field('time_end') ?: get_field('heure_fin')) : '';
    $image = function_exists('get_field') ? get_field('image') : null;
    $description = function_exists('get_field') ? (get_field('description') ?: get_the_content()) : get_the_content();
    $previous_episodes = function_exists('get_field') ? get_field('episodes_precedents') : [];

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

    $related_items = static function ($value) {
        if (!$value) {
            return [];
        }

        if ($value instanceof WP_Post || is_numeric($value) || (is_array($value) && (isset($value['ID']) || isset($value['id'])))) {
            $value = [$value];
        }

        $items = [];
        foreach ((array) $value as $item) {
            if ($item instanceof WP_Post) {
                $items[] = $item;
                continue;
            }

            if (is_array($item)) {
                $item = $item['ID'] ?? $item['id'] ?? 0;
            }

            if (is_numeric($item)) {
                $post = get_post(absint($item));
                if ($post instanceof WP_Post) {
                    $items[] = $post;
                }
            } elseif (is_string($item) && trim($item) !== '') {
                $items[] = trim($item);
            }
        }

        return $items;
    };
    $animateurs = $related_items($animateur);
    $previous_episodes = $related_items($previous_episodes);

    $page_programmation = get_page_by_path('programmation');
    $url_programmation = $page_programmation ? get_permalink($page_programmation->ID) : home_url();
    $back_url = wp_get_referer() ?: $url_programmation;
?>

        <svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-[#fdbe00] md:h-24 bg-black"  viewBox="0 0 1440 100"
            preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor"
                d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
        </svg>
 
    <main class="relative isolate overflow-hidden bg-black px-4 py-10 text-white sm:px-6 sm:py-14">
        <div aria-hidden="true" class="pointer-events-none absolute -right-40 top-16 size-96 rounded-full bg-purple/30 blur-3xl"></div>
        <div class="relative mx-auto max-w-6xl">
            <a href="<?php echo esc_url($back_url); ?>"
                class="mb-10 inline-flex items-center gap-2 rounded-full border border-white/25 px-5 py-3 font-bold bg-orange text-white no-underline transition-colors duration-200 btn-border hover:bg-yellow hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange">
                <span aria-hidden="true">&larr;</span> Retour aux programmes
            </a>

            <article>
                <div class="grid items-center gap-10 md:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] md:gap-14">
                    <header class="relative z-10">
                        <p class="mb-5 flex items-center gap-3 text-sm font-extrabold uppercase tracking-[0.2em] text-yellow">
                            <span aria-hidden="true" class="size-2 rounded-full bg-orange motion-safe:animate-pulse"></span>
                            Au programme
                        </p>
                        <h1 class="max-w-3xl font-title text-5xl font-black uppercase leading-[1.12] text-beige sm:text-6xl lg:text-7xl rounded-[1.75rem] ">
                            <span class=" px-3 py-1 text-orange hover:text-beige transition-colors ease-in-out duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange hover:scale-110 motion-safe:transition-transform">
                                <?php echo esc_html($title); ?>
                            </span>
                        </h1>
                        <p class="mt-8 max-w-xl text-lg leading-relaxed text-beige sm:text-xl">
                            <?php echo esc_html($description); ?>
                        </p>
                    </header>

                <?php if ($image_id || $image_url) : ?>
                        <figure class="relative mx-auto w-full max-w-xl p-3">
                     
                
                            <div class="relative z-10 aspect-[4/3] overflow-hidden rounded-[1.75rem] border-4 border-violet bg-white/5">
                        <?php if ($image_id) : ?>
                            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                'alt' => $image_alt,
                                        'class' => 'block size-full transform-none object-cover object-center',
                                'loading' => 'eager',
                            ]); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($image_alt); ?>"
                                        class="block size-full object-cover object-center"
                                loading="eager">
                        <?php endif; ?>
                            </div>
                    </figure>
                <?php endif; ?>
                </div>

                <div class="mt-12 grid gap-4 sm:grid-cols-2">
                    <?php if ($date || $time_start || $time_end) : ?>
                        <section class="btn-border rounded-3xl bg-orange p-6 text-purple sm:p-8 hover:bg-yellow hover:text-black transition-colors duration-300" aria-labelledby="programme-schedule">
                            <p class=" mb-3 text-sm font-extrabold uppercase tracking-[0.18em]">Le rendez-vous</p>
                            <h2 id="programme-schedule" class="font-title text-3xl font-black uppercase sm:text-4xl">
                                <?php echo $date ? esc_html($date) : 'Horaire'; ?>
                            </h2>
                            <?php if ($time_start || $time_end) : ?>
                                <p class="mt-2 text-xl font-bold">
                                    <?php if ($time_start) : ?><?php echo esc_html($time_start); ?><?php endif; ?>
                                    <?php if ($time_start && $time_end) : ?>–<?php endif; ?>
                                    <?php if ($time_end) : ?><?php echo esc_html($time_end); ?><?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>


                    <!-- partie pour les animateurs qui font l'émission -->
                    <?php if ($animateurs) : ?>
                        <section class="rounded-3xl bg-purple p-6 sm:p-8 hover:bg-yellow hover:text-black transition-colors duration-300 btn-border" aria-labelledby="programme-hosts">
                           
                            <h2 id="programme-hosts" class="font-title text-3xl font-black uppercase sm:text-4xl">Animateurs</h2>
                            <ul class="mt-4 flex flex-wrap gap-2">
                                <?php foreach ($animateurs as $animator) : ?>
                                    <?php
                                    $animator_name = $animator instanceof WP_Post && function_exists('get_field')
                                        ? get_field('prenom_nom', $animator->ID)
                                        : '';
                                    $animator_name = $animator_name ?: ($animator instanceof WP_Post ? get_the_title($animator) : $animator);
                                    ?>
                                    <li class="rounded-full bg-violet px-4 py-2 font-bold text-beige hover:bg-black hover:text-beige transition-colors duration-300">
                                        <?php echo esc_html($animator_name); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </div>

            </article>

        </div>
    </main>

<?php endwhile; ?>

    <svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-black md:h-24 bg-transparent"  viewBox="0 0 1440 100"
            preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor"
                d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
        </svg>

<?php get_footer(); ?>
