<?php
/* Template Name: Programmation */
get_header();
?>
<main class="container mx-auto px-4 py-12 bg-beige">

    <section class="relative left-1/2 w-screen -translate-x-1/2 px-4 py-12">
        <div class="mx-auto max-w-6xl">
            <h1 class="mb-8 block text-4xl font-archivo font-bold text-orange"><?php the_title(); ?></h1>

            <?php
            $programmes = new WP_Query([
                'post_type' => 'programme',
                'posts_per_page' => -1,
                'orderby' => [
                    'menu_order' => 'ASC',
                    'date' => 'ASC',
                ],
            ]); // Query pour appeler tout les programmes et les faires apparaitres dans l'ordre et la date et du menu (lun-ven)
            ?>


            <?php if ($programmes->have_posts()) : ?>
                <div class="grid gap-12 md:gap-16">
                    <?php
                    $program_index = 0;
                    while ($programmes->have_posts()) :
                        $programmes->the_post();
                        $reverse = $program_index % 2 === 0;
                    ?>
                        <div class="relative mx-auto w-full md:w-[91%] <?php echo $reverse ? 'md:mr-auto' : 'md:ml-auto'; ?>">
                            <?php if ($program_index === 0) : ?>
                                <img class="pointer-events-none absolute -right-12 -top-12 z-10 hidden w-28 rotate-6 lg:block" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/radio.png'); ?>" alt="" aria-hidden="true">
                            <?php elseif ($program_index === 1) : ?>
                                <img class="pointer-events-none absolute -left-20 -top-10 z-10 hidden w-36 -rotate-12 lg:block" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/megaphone2 3.png'); ?>" alt="" aria-hidden="true">
                            <?php endif; ?>
                            <?php get_template_part('templates/carte-programme', null, ['reverse' => $reverse]); ?>
                        </div>
                    <?php
                        $program_index++;
                    endwhile;
                    ?>
                </div>
            <?php else : ?>
                <p class="">Aucun programme ici à date.</p>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <section class="relative left-1/2 mt-20 w-screen -translate-x-1/2 bg-violet px-4 py-14 text-white md:py-20">
        <svg class="absolute bottom-full left-0 h-12 w-full text-violet md:h-20" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 42 Q60 0 120 42 T240 42 T360 42 T480 42 T600 42 T720 42 T840 42 T960 42 T1080 42 T1200 42 T1320 42 T1440 42 V100 H0 Z" />
        </svg>

        <div class="mx-auto grid max-w-5xl items-center gap-8 md:grid-cols-2 md:gap-12">
            <div class="max-w-md">
                <h2 class="font-title text-3xl font-black uppercase text-yellow md:text-4xl">Tu as un projet?</h2>
                <p class="mt-4 text-sm leading-relaxed text-white/90">
                    Une idée d'émission, une chronique ou un événement à faire connaître? Remplis le formulaire et notre équipe te recontactera pour en jaser.
                </p>
            </div>

            <div class="rounded-3xl bg-white p-5 text-black shadow-lg md:p-7">
                <?php
                $project_status = isset($_GET['projet']) ? sanitize_key(wp_unslash($_GET['projet'])) : '';
                if ($project_status === 'envoye') :
                ?>
                    <p class="mb-4 rounded-xl bg-green-100 p-3 text-sm text-green-900" role="status" aria-live="polite">Merci! Ta proposition a bien été envoyée.</p>
                <?php elseif ($project_status === 'invalide') : ?>
                    <p class="mb-4 rounded-xl bg-red-100 p-3 text-sm text-red-900" role="alert">Vérifie les champs et l'adresse courriel, puis réessaie.</p>
                <?php elseif ($project_status === 'erreur') : ?>
                    <p class="mb-4 rounded-xl bg-red-100 p-3 text-sm text-red-900" role="alert">Le message n'a pas pu être envoyé. Réessaie plus tard ou écris-nous directement.</p>
                <?php elseif ($project_status === 'securite') : ?>
                    <p class="mb-4 rounded-xl bg-red-100 p-3 text-sm text-red-900" role="alert">Le formulaire a expiré. Recharge la page et réessaie.</p>
                <?php endif; ?>

                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="grid gap-3">
                    <input type="hidden" name="action" value="efc_submit_project">
                    <?php wp_nonce_field('efc_submit_project', 'efc_project_nonce'); ?>

                    <label class="grid gap-1 text-xs font-bold text-purple">
                        Ton nom
                        <input class="min-h-10 rounded-full bg-[#f1f1f1] px-4 text-sm font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" type="text" name="project_name" autocomplete="name" required>
                    </label>

                    <label class="grid gap-1 text-xs font-bold text-purple">
                        Ton courriel
                        <input class="min-h-10 rounded-full bg-[#f1f1f1] px-4 text-sm font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" type="email" name="project_email" autocomplete="email" required>
                    </label>

                    <label class="grid gap-1 text-xs font-bold text-purple">
                        Ton idée de projet
                        <input class="min-h-10 rounded-full bg-[#f1f1f1] px-4 text-sm font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" type="text" name="project_title" required>
                    </label>

                    <label class="grid gap-1 text-xs font-bold text-purple">
                        Parle-nous en quelques mots
                        <textarea class="min-h-24 resize-y rounded-2xl bg-[#f1f1f1] px-4 py-3 text-sm font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" name="project_message" required></textarea>
                    </label>

                    <button class="justify-self-end rounded-full bg-orange px-6 py-2 text-sm font-extrabold text-white transition-colors hover:bg-yellow hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet" type="submit">Envoyer</button>
                </form>
            </div>
        </div>

        <svg class="absolute left-0 top-full h-12 w-full text-violet md:h-20" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 0 H1440 V58 Q1380 100 1320 58 T1200 58 T1080 58 T960 58 T840 58 T720 58 T600 58 T480 58 T360 58 T240 58 T120 58 T0 58 Z" />
        </svg>
    </section>

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
    $grid_columns = $reverse
        ? 'md:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]'
        : 'md:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]';
    ?>
    <!-- pour change l'ordre du display -->

    <article class="grid grid-cols-1 overflow-hidden rounded-[2.5rem] bg-black text-white md:min-h-40 <?php echo esc_attr($grid_columns); ?>">
        <?php if ($image) : ?>
            <figure class="<?php echo esc_attr($figure_order); ?> order-1 min-h-48 overflow-hidden p-2 md:min-h-0">
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
            <figure class="<?php echo esc_attr($figure_order); ?> order-1 min-h-48 overflow-hidden p-2 md:min-h-0">
                <?php the_post_thumbnail('large', ['alt' => $image_alt, 'class' => 'block size-full rounded-[1.75rem] object-cover']); ?>
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
                                - <?php echo esc_html($time_end); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- partie description -->
            <?php if ($description) : ?>
                <div class="text-xs leading-relaxed text-white/75"><?php echo wp_kses_post($description); ?></div>
            <?php endif; ?>

            <!-- bouton voir plus -->
            <a class="inline-flex min-h-8 items-center justify-center rounded-full bg-orange px-5 py-2 text-xs font-extrabold text-white transition-colors duration-300 hover:bg-yellow hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange" href="<?php echo esc_url(get_permalink()); ?>">
                Voir plus
            </a>
        </div>
    </article>

    <?php get_footer(); ?>