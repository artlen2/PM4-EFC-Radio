<?php
/*
Template Name: À propos
*/
get_header();
?>
<main class="container mx-auto px-4 py-12 bg-beige">
    <?php

    /* Requêtes */
    $query_args = [
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
    ];

    $all_animators    = get_posts($query_args + ['post_type' => 'animateur']);
    $all_team_members = get_posts($query_args + ['post_type' => 'equipe']);
    $all_partners     = get_posts($query_args + ['post_type' => 'partenaire']);

    /* Helpers */
    // image SCF (tableau, id ou url)
    if (!function_exists('radio_image')) {
        function radio_image($field, $fallback_alt = '')
        {
            if (!$field) {
                return null;
            }
            if (is_array($field)) {
                return [
                    'url' => $field['url'] ?? '',
                    'alt' => !empty($field['alt']) ? $field['alt'] : $fallback_alt,
                ];
            }
            if (is_numeric($field)) {
                return [
                    'url' => wp_get_attachment_image_url((int) $field, 'large'),
                    'alt' => get_post_meta((int) $field, '_wp_attachment_image_alt', true) ?: $fallback_alt,
                ];
            }
            return ['url' => (string) $field, 'alt' => $fallback_alt];
        }
    }

    // carte + popup
    if (!function_exists('radio_member_card')) {
        function radio_member_card($member)
        {
            $uid = 'member-' . $member['id'];
            $img = radio_image($member['photo'], $member['name']);
            $radius = $member['shape'] === 'round' ? 'rounded-full' : 'rounded-3xl';
    ?>
            <li class="group w-56 shrink-0 snap-center scale-90 opacity-50 transition duration-300 data-[active=true]:scale-100 data-[active=true]:opacity-100" data-card>
                <div class="flex h-18 flex-col justify-end">
                    <h4 class="font-title font-black uppercase leading-tight">
                        <?php echo esc_html($member['name']); ?>
                    </h4>
                    <?php if ($member['role']) : ?>
                        <p class="mt-0.5 font-semibold leading-tight text-purple">
                            <?php echo esc_html($member['role']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="relative mt-2">
                    <div class="aspect-square overflow-hidden border-[3px] border-violet bg-white
                            group-data-[active=true]:border-purple <?php echo $radius; ?>">
                        <?php if ($img && $img['url']) : ?>
                            <img src="<?php echo esc_url($img['url']); ?>"
                                alt="<?php echo esc_attr($img['alt']); ?>"
                                class="h-full w-full object-cover"
                                loading="lazy">
                        <?php else : ?>
                            <div class="flex h-full w-full items-center justify-center bg-violet/20 font-title text-purple"
                                aria-hidden="true">
                                <?php echo esc_html(mb_substr($member['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="button"
                        data-open="<?php echo esc_attr($uid); ?>"
                        class="absolute -bottom-2 right-0 translate-x-2 cursor-pointer rounded-lg border-2 border-black
                           bg-yellow px-3 py-1 font-bold text-white shadow-[2px_2px_0_0_#0F0E0F]
                           transition hover:bg-orange hover:translate-y-[2px] hover:shadow-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                        aria-label="<?php echo esc_attr(sprintf('Voir plus sur %s', $member['name'])); ?>">
                        Voir plus
                    </button>
                </div>
            </li>

            <dialog id="<?php echo esc_attr($uid); ?>"
                class="m-auto w-[min(92vw,32rem)] rounded-3xl border-[3px] border-purple bg-beige p-0 text-black
                   backdrop:bg-black/60"
                aria-labelledby="<?php echo esc_attr($uid); ?>-title">
                <div class="flex flex-col gap-4 p-6 sm:p-8">

                    <div class="flex items-start gap-4">
                        <?php if ($img && $img['url']) : ?>
                            <img src="<?php echo esc_url($img['url']); ?>"
                                alt=""
                                class="size-20 shrink-0 border-[3px] border-purple object-cover <?php echo $radius; ?>">
                        <?php endif; ?>
                        <div class="flex-1">
                            <h3 id="<?php echo esc_attr($uid); ?>-title"
                                class="font-title font-black uppercase leading-tight text-purple">
                                <?php echo esc_html($member['name']); ?>
                            </h3>
                            <?php if ($member['role']) : ?>
                                <p class="mt-1 font-semibold text-orange"><?php echo esc_html($member['role']); ?></p>
                            <?php endif; ?>
                        </div>
                        <button type="button" data-close
                            class="cursor-pointer rounded-full btn-press bg-white px-3 py-1 font-bold
                               hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                            aria-label="Fermer">
                            ✕
                        </button>
                    </div>

                    <?php if ($member['text']) : ?>
                        <div class="max-w-prose space-y-3 leading-relaxed">
                            <?php echo wp_kses_post(wpautop($member['text'])); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </dialog>
        <?php
        }
    }

    // carrousel
    if (!function_exists('radio_carousel')) {
        function radio_carousel($id, $title, $icon, $members)
        {
            if (empty($members)) {
                return;
            }
        ?>
            <section class="relative py-10" aria-labelledby="<?php echo esc_attr($id); ?>-title">

                <div class="mx-auto mb-8 flex max-w-6xl items-center gap-4 px-6">
                    <h2 id="<?php echo esc_attr($id); ?>-title"
                        class="font-title font-black uppercase text-yellow ">
                        <?php echo esc_html($title); ?>
                    </h2>
                    <?php
                    $icon_path = get_template_directory() . '/assets/images/' . $icon;
                    if (file_exists($icon_path)) : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . $icon); ?>"
                            alt="" class="h-20 w-auto" aria-hidden="true">
                    <?php endif; ?>

                    <div class="ml-auto flex gap-2">
                        <button type="button" data-scroll="-1" data-target="<?php echo esc_attr($id); ?>"
                            class="cursor-pointer rounded-full btn-press bg-white size-10 font-bold
                               hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                            aria-label="Précédent">←</button>
                        <button type="button" data-scroll="1" data-target="<?php echo esc_attr($id); ?>"
                            class="cursor-pointer rounded-full btn-press bg-white size-10 font-bold
                               hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                            aria-label="Suivant">→</button>
                    </div>
                </div>

                <ul id="<?php echo esc_attr($id); ?>" data-track
                    class="flex snap-x snap-mandatory gap-10 overflow-x-auto px-[calc(50%-7rem)] pb-6 pt-2
                       list-none m-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <?php foreach ($members as $member) {
                        radio_member_card($member);
                    } ?>
                </ul>
            </section>
    <?php
        }
    }

    /* Préparation des données */
    $animators = array_map(function ($p) {
        return [
            'id'    => $p->ID,
            'name'  => get_the_title($p),
            'role'  => get_field('role', $p->ID),
            'photo' => get_field('photo', $p->ID),
            'text'  => get_field('bio', $p->ID),
            'shape' => 'round',
        ];
    }, $all_animators);

    $team = array_map(function ($p) {
        return [
            'id'    => $p->ID,
            'name'  => get_field('prenom_nom', $p->ID) ?: get_the_title($p),
            'role'  => get_field('role', $p->ID),
            'photo' => get_field('photo', $p->ID),
            'text'  => get_field('description', $p->ID),
            'shape' => 'squircle',
        ];
    }, $all_team_members);
    ?>

    <main class="overflow-x-clip bg-beige font-sans text-black">

        <!-- En-tête de page -->
        <header class="mx-auto flex max-w-6xl justify-end px-6 pt-12">
            <h1 class="-rotate-3 font-title font-black uppercase text-orange ">
                <?php the_title(); ?>
            </h1>
        </header>

        <?php if (get_the_content()) : ?>
            <div class="mx-auto mt-6 max-w-2xl px-6 text-center ">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>

        <!-- Animateurs -->
        <?php radio_carousel('animateurs', 'Animateurs', 'icon-micro.png', $animators); ?>

        <!-- Employés -->
        <?php radio_carousel('equipe', 'Employés', 'icon-camera.png', $team); ?>

        <!-- Partenaires -->
        <?php if (!empty($all_partners)) : ?>
            <section class="mx-auto max-w-6xl px-6 py-16" aria-labelledby="partenaires-title">
                <h2 id="partenaires-title"
                    class="mb-10 font-title font-black uppercase text-yellow ">
                    Partenaires
                </h2>

                <ul class="grid list-none grid-cols-2 gap-6 p-0 m-0 sm:grid-cols-3 lg:grid-cols-4">
                    <?php foreach ($all_partners as $partner) :
                        $logo = radio_image(get_field('logo', $partner->ID), get_the_title($partner));
                        $link = get_field('link', $partner->ID);
                        if (!$logo || !$logo['url']) {
                            continue;
                        }
                    ?>
                        <li>
                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link); ?>"
                                    target="_blank" rel="noopener noreferrer"
                                    class="flex aspect-3/2 items-center justify-center rounded-2xl border-2 border-purple/20 bg-white p-6
                                       transition hover:border-purple focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                                    aria-label="<?php echo esc_attr(sprintf('Visiter le site de %s (nouvel onglet)', get_the_title($partner))); ?>">
                                <?php else : ?>
                                    <div class="flex aspect-3/2 items-center justify-center rounded-2xl border-2 border-purple/20 bg-white p-6">
                                    <?php endif; ?>

                                    <img src="<?php echo esc_url($logo['url']); ?>"
                                        alt="<?php echo esc_attr($logo['alt']); ?>"
                                        class="max-h-full max-w-full object-contain"
                                        loading="lazy">

                                    <?php echo $link ? '</a>' : '</div>'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

    </main>

    <script>
        (function() {
            // carte du milieu en avant
            document.querySelectorAll('[data-track]').forEach(function(track) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(e) {
                        e.target.dataset.active = e.isIntersecting ? 'true' : 'false';
                    });
                }, {
                    root: track,
                    rootMargin: '0px -45% 0px -45%',
                    threshold: 0
                });
                track.querySelectorAll('[data-card]').forEach(function(card) {
                    observer.observe(card);
                });
            });

            // Flèches précédent / suivant
            document.querySelectorAll('[data-scroll]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var track = document.getElementById(btn.dataset.target);
                    var step = track.querySelector('[data-card]').offsetWidth + 40;
                    track.scrollBy({
                        left: step * Number(btn.dataset.scroll),
                        behavior: 'smooth'
                    });
                });
            });

            // Fenêtres "Voir plus"
            document.querySelectorAll('[data-open]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById(btn.dataset.open).showModal();
                });
            });
            document.querySelectorAll('dialog').forEach(function(dlg) {
                dlg.querySelector('[data-close]').addEventListener('click', function() {
                    dlg.close();
                });
                dlg.addEventListener('click', function(e) { // clic sur le fond
                    if (e.target === dlg) dlg.close();
                });
            });
        })();
    </script>

    <?php get_footer(); ?>