<?php
/* Template Name: Programmation */
get_header();
?>
<main class="container mx-auto px-4 py-12 bg-beige">

    <section class="relative left-1/2 w-screen -translate-x-1/2 px-4 py-12">
        <div class="mx-auto max-w-6xl">
            <h2 class="mb-8 block font-bold text-orange text-6xl w-110 leading-14"><?php the_title(); ?></h2>



            <?php
            $programmes = new WP_Query([
                'post_type' => 'programme',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => [
                    'menu_order' => 'ASC',
                    'date' => 'ASC',
                ],
            ]);
            ?>

            <?php if ($programmes->have_posts()) : ?>
                <?php
                $weekdays = [
                    'lundi' => 'Lundi',
                    'mardi' => 'Mardi',
                    'mercredi' => 'Mercredi',
                    'jeudi' => 'Jeudi',
                    'vendredi' => 'Vendredi',
                    'samedi' => 'Samedi',
                    'dimanche' => 'Dimanche',
                ];
                $weekday_aliases = [
                    'lundi' => 'lundi',
                    'lun' => 'lundi',
                    'monday' => 'lundi',
                    'mon' => 'lundi',
                    'mardi' => 'mardi',
                    'mar' => 'mardi',
                    'tuesday' => 'mardi',
                    'tue' => 'mardi',
                    'mercredi' => 'mercredi',
                    'mer' => 'mercredi',
                    'wednesday' => 'mercredi',
                    'wed' => 'mercredi',
                    'jeudi' => 'jeudi',
                    'jeu' => 'jeudi',
                    'thursday' => 'jeudi',
                    'thu' => 'jeudi',
                    'vendredi' => 'vendredi',
                    'ven' => 'vendredi',
                    'friday' => 'vendredi',
                    'fri' => 'vendredi',
                    'samedi' => 'samedi',
                    'sam' => 'samedi',
                    'saturday' => 'samedi',
                    'sat' => 'samedi',
                    'dimanche' => 'dimanche',
                    'dim' => 'dimanche',
                    'sunday' => 'dimanche',
                    'sun' => 'dimanche',
                ];
                ?>
                <nav class="mb-10 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" aria-label="Filtrer les programmations par jour">
                    <div class="flex min-w-max items-center gap-1 sm:gap-2" role="group">
                        <?php foreach ($weekdays as $day_slug => $day_label) : ?>
                            <button
                                class="inline-flex h-12 shrink-0 appearance-none items-center justify-center rounded-full border-2 border-solid border-transparent bg-transparent px-4 font-title font-black uppercase leading-none text-black transition-colors duration-200 hover:border-orange hover:text-orange focus-visible:border-orange focus-visible:text-orange sm:h-14 sm:px-5  aria-pressed:border-orange aria-pressed:text-orange"
                                type="button"
                                data-programme-day="<?php echo esc_attr($day_slug); ?>"
                                aria-pressed="<?php echo $day_slug === 'lundi' ? 'true' : 'false'; ?>"
                            ><?php echo esc_html($day_label); ?></button>
                        <?php endforeach; ?>
                    </div>
                </nav>

 


                <div class="grid gap-12 md:gap-16">
                    <?php
                    $program_index = 0;
                    while ($programmes->have_posts()) :
                        $programmes->the_post();
                        $reverse = $program_index % 2 === 0;
                        $programme_days = [];
                        if (function_exists('get_field')) {
                            foreach (['date', 'jour'] as $field_name) {
                                $programme_days[] = get_field($field_name);
                                $programme_days[] = get_field($field_name, false, false);
                            }
                        }

                        $day_values = $programme_days;
                        $programme_day_slugs = [];

                        while ($day_values) {
                            $day_value = array_pop($day_values);
                            if (is_array($day_value)) {
                                foreach ($day_value as $nested_day_value) {
                                    $day_values[] = $nested_day_value;
                                }
                                continue;
                            }

                            if (!is_scalar($day_value)) {
                                continue;
                            }

                            $normalized_days = preg_split('/[^a-z0-9]+/', sanitize_title((string) $day_value), -1, PREG_SPLIT_NO_EMPTY);
                            foreach ($normalized_days as $normalized_day) {
                                if (isset($weekday_aliases[$normalized_day])) {
                                    $programme_day_slugs[] = $weekday_aliases[$normalized_day];
                                }
                            }
                        }

                        $programme_day_slugs = array_values(array_unique($programme_day_slugs));
                    ?>
                        <div class="programme-card relative mx-auto w-full md:w-[91%] <?php echo $reverse ? 'md:mr-auto' : 'md:ml-auto'; ?>" data-programme-days="<?php echo esc_attr(implode(' ', $programme_day_slugs)); ?>">
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
                <p class="programme-empty-state mt-8 hidden text-center font-bold text-purple" role="status" aria-live="polite">Aucune programmation publiée pour ce jour.</p>
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
                <h2 class="font-title font-black uppercase text-yellow ">Tu as un projet?</h2>
                <p class="mt-4 leading-relaxed text-white/90">
                    Une idée d'émission, une chronique ou un événement à faire connaître? Remplis le formulaire et notre équipe te recontactera pour en jaser.
                </p>
            </div>

            <div class="rounded-3xl bg-white p-5 text-black shadow-lg md:p-7">
                <?php
                $project_status = isset($_GET['projet']) ? sanitize_key(wp_unslash($_GET['projet'])) : '';
                if ($project_status === 'envoye') :
                ?>
                    <p class="mb-4 rounded-xl bg-green-100 p-3 text-green-900" role="status" aria-live="polite">Merci! Ta proposition a bien été envoyée.</p>
                <?php elseif ($project_status === 'invalide') : ?>
                    <p class="mb-4 rounded-xl bg-red-100 p-3  text-red-900" role="alert">Vérifie les champs et l'adresse courriel, puis réessaie.</p>
                <?php elseif ($project_status === 'erreur') : ?>
                    <p class="mb-4 rounded-xl bg-red-100 p-3  text-red-900" role="alert">Le message n'a pas pu être envoyé. Réessaie plus tard ou écris-nous directement.</p>
                <?php elseif ($project_status === 'securite') : ?>
                    <p class="mb-4 rounded-xl bg-red-100 p-3  text-red-900" role="alert">Le formulaire a expiré. Recharge la page et réessaie.</p>
                <?php endif; ?>

                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="grid gap-3">
                    <input type="hidden" name="action" value="efc_submit_project">
                    <?php wp_nonce_field('efc_submit_project', 'efc_project_nonce'); ?>

                    <label class="grid gap-1 font-bold text-purple">
                        Nom
                        <input class="min-h-10 rounded-full bg-[#f1f1f1] px-4 font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" type="text" name="project_name" autocomplete="name" required>
                    </label>

                    <label class="grid gap-1  font-bold text-purple">
                        Courriel
                        <input class="min-h-10 rounded-full bg-[#f1f1f1] px-4 font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" type="email" name="project_email" autocomplete="email" required>
                    </label>

                    <label class="grid gap-1 font-bold text-purple">
                        Ton idée de projet
                        <input class="min-h-10 rounded-full bg-[#f1f1f1] px-4 font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" type="text" name="project_title" required>
                    </label>

                    <label class="grid gap-1 font-bold text-purple">
                        Parle-nous en avec quelques mots
                        <textarea class="min-h-24 resize-y rounded-2xl bg-[#f1f1f1] px-4 py-3  font-normal text-black outline-none focus-visible:ring-2 focus-visible:ring-violet" name="project_message" required></textarea>
                    </label>

                    <button class="justify-self-end rounded-full bg-orange px-6 py-2 font-extrabold text-white transition-colors hover:bg-yellow hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet" type="submit">Envoyer</button>
                </form>
            </div>
        </div>

        <svg class="absolute left-0 top-full h-12 w-full text-violet md:h-20" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 0 H1440 V58 Q1380 100 1320 58 T1200 58 T1080 58 T960 58 T840 58 T720 58 T600 58 T480 58 T360 58 T240 58 T120 58 T0 58 Z" />
        </svg>
    </section>

    <?php get_footer(); ?>