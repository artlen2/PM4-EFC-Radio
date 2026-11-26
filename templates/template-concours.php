<?php
/*
 * Template Name: Communauté (Concours, Bingo, Vitrine & Bénévolat)
 */
get_header();

// Helper sécuritaire SCF/ACF
$get_acf = static function (string $key, $post_id = false, $default = '', bool $format = true) {
    if (!function_exists('get_field')) {
        return $default;
    }
    $val = get_field($key, $post_id, $format);
    return ($val !== null && $val !== '' && $val !== false) ? $val : $default;
};

// Parse les dates SCF ("29/11/2026 11:00 pm" ou "2026-11-29 23:00:00")
$parse_scf_datetime = static function ($raw): ?DateTimeImmutable {
    if (!$raw || !is_string($raw)) {
        return null;
    }
    $tz = wp_timezone();
    $formats = ['d/m/Y g:i a', 'd/m/Y h:i a', 'd/m/Y H:i:s', 'd/m/Y H:i', 'Y-m-d H:i:s', 'Y-m-d H:i'];
    foreach ($formats as $fmt) {
        $dt = DateTimeImmutable::createFromFormat($fmt, trim($raw), $tz);
        if ($dt instanceof DateTimeImmutable) {
            return $dt;
        }
    }
    $ts = strtotime($raw);
    return $ts ? (new DateTimeImmutable('@' . $ts))->setTimezone($tz) : null;
};

// Normalise le champ Checkbox "vitrine_categorie" en liste de slugs
$extract_vitrine_slugs = static function ($raw): array {
    if (empty($raw)) {
        return [];
    }
    $items = is_array($raw) ? $raw : [$raw];
    $slugs = [];
    foreach ($items as $item) {
        $val  = is_array($item) ? ($item['value'] ?? ($item['label'] ?? '')) : (string) $item;
        $slug = sanitize_title($val);
        if ($slug === 'avis-de-deces') {
            $slug = 'avis-deces';
        }
        if ($slug !== '') {
            $slugs[] = $slug;
        }
    }
    return array_unique($slugs);
};

// Récupère l'URL d'un fichier dans le thème (gère les accents comme photo-équipe.png)
$theme_asset = static function (string $relative_path): string {
    $full_path = get_theme_file_path($relative_path);
    if (!file_exists($full_path)) {
        return '';
    }
    $parts = explode('/', $relative_path);
    $file  = array_pop($parts);
    return get_theme_file_uri(implode('/', $parts) . '/' . rawurlencode($file));
};

// Vos vrais fichiers dans assets/objets/
$boombox_url   = $theme_asset('assets/objets/Personnage.png');
$cassette_url  = $theme_asset('assets/objets/Personnage2.png');
$star_mascot   = $theme_asset('assets/objets/Personnage3.png');
$micro_url     = $theme_asset('assets/objets/micro.png');
$megaphone_url = $theme_asset('assets/objets/megaphone23.png');
$studio_def    = $theme_asset('assets/objets/photo-équipe.png');
$star_orange   = $theme_asset('assets/objets/Star2.png');
$star_yellow   = $theme_asset('assets/objets/Star5.png');
$ellipse_yel   = $theme_asset('assets/objets/Ellipse8.png');
$ellipse_purp  = $theme_asset('assets/objets/Ellipse9.png');
$paper_tex_url = $theme_asset('assets/objets/overlay-texturepapier.png');

// Onglets Vitrine locale
$vitrine_tabs = [
    'commerces'     => 'Commerces',
    'avis-deces'    => 'Avis de décès',
    'anniversaires' => 'Anniversaires',
    'emplois'       => 'Emplois',
    'annonces'      => 'Annonces',
];

$active_vitrine = isset($_GET['vitrine']) ? sanitize_key(wp_unslash($_GET['vitrine'])) : 'commerces';
if (!isset($vitrine_tabs[$active_vitrine])) {
    $active_vitrine = 'commerces';
}

$benevolat_status = isset($_GET['benevolat']) ? sanitize_key(wp_unslash($_GET['benevolat'])) : '';
?>

<!-- Vague supérieure jaune -->
<svg class="hero-wave relative z-10 -mt-px -mb-px block h-16 w-full rotate-180 bg-transparent text-yellow md:h-24"
    viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
    <path fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<main class="container bg-beige font-sans text-black">

    <!-- ============================================================
         1. CONCOURS EN COURS (Beige + Boombox + Cartes décalées)
         ============================================================ -->
    <?php
    $concours_bg     = $get_acf('concours_bg_image');
    $concours_bg_url = is_array($concours_bg) ? ($concours_bg['url'] ?? '') : $concours_bg;
    $now_ts          = current_time('timestamp');

    $concours_raw = get_posts([
        'post_type'      => 'concours',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ]);

    $concours_actifs = [];
    foreach ($concours_raw as $c_post) {
        $start_dt = $parse_scf_datetime($get_acf('concours_date_time_start', $c_post->ID));
        $end_dt   = $parse_scf_datetime($get_acf('concours_date_time_end', $c_post->ID));

        $start_ts = $start_dt ? $start_dt->getTimestamp() : null;
        $end_ts   = $end_dt ? $end_dt->getTimestamp() : null;

        if ($start_ts && $now_ts < $start_ts) {
            continue;
        }
        if ($end_ts && $now_ts > $end_ts) {
            continue;
        }

        $concours_actifs[] = [
            'post'     => $c_post,
            'start_dt' => $start_dt,
            'end_dt'   => $end_dt,
            'end_ts'   => $end_ts ?? PHP_INT_MAX,
        ];
    }

    usort($concours_actifs, static fn($a, $b) => $a['end_ts'] <=> $b['end_ts']);

    $featured_concours = array_slice($concours_actifs, 0, 2);
    $extra_concours    = array_slice($concours_actifs, 2);
    ?>

    <section id="concours" class="full-bleed relative -mt-16 overflow-hidden bg-beige pt-24 pb-16 md:-mt-24 md:pt-32 md:pb-24">

        <?php if ($concours_bg_url) : ?>
            <div class="pointer-events-none absolute inset-y-0 left-0 hidden w-2/5 lg:block" aria-hidden="true">
                <img src="<?php echo esc_url($concours_bg_url); ?>" alt=""
                    class="h-full w-full object-cover opacity-85"
                    style="-webkit-mask-image: linear-gradient(to right, #000 40%, transparent 100%); mask-image: linear-gradient(to right, #000 40%, transparent 100%);">
            </div>
        <?php endif; ?>

        <div class="relative z-10 mx-auto max-w-6xl px-4">
            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12">

                <!-- Mascotte Boombox (Personnage.png) -->
                <div class="flex justify-center lg:col-span-5 lg:justify-start">
                    <?php if ($boombox_url) : ?>
                        <img src="<?php echo esc_url($boombox_url); ?>"
                            alt="Mascotte boombox de la radio"
                            class="w-56 max-w-full drop-shadow-md sm:w-72 lg:w-80"
                            loading="eager">
                    <?php endif; ?>
                </div>

                <!-- Titre + Éclats violets + Intro + Cartes décalées -->
                <div class="lg:col-span-7">
                    <div class="relative mb-4 inline-block">
                        <svg class="pointer-events-none absolute -top-1 -left-8 hidden h-9 w-6 text-violet sm:block" viewBox="0 0 28 40" fill="none" aria-hidden="true">
                            <path d="M24 6L6 2M22 18L2 18M24 30L6 36" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" />
                        </svg>
                        <h1 class="block max-w-full wrap-break-word font-title text-4xl font-black uppercase leading-tight text-orange sm:text-5xl lg:text-6xl">
                            Concours en cours
                        </h1>
                        <svg class="pointer-events-none absolute -top-1 -right-8 hidden h-9 w-6 text-violet sm:block" viewBox="0 0 28 40" fill="none" aria-hidden="true">
                            <path d="M4 6L22 2M6 18L26 18M4 30L22 36" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" />
                        </svg>
                    </div>

                    <p class="mb-10 max-w-xl text-base leading-relaxed text-black/85 sm:text-lg">
                        <?php echo esc_html($get_acf('concours_intro', false, 'Tentez votre chance au Radio Bingo en direct sur nos ondes et participez aux concours offerts par nos partenaires ! De beaux prix vous attendent, et chaque participation aide à faire vivre votre radio communautaire.')); ?>
                    </p>

                    <?php if (!empty($featured_concours)) : ?>
                        <div class="flex flex-col gap-10">
                            <?php foreach ($featured_concours as $idx => $item) :
                                $c_post    = $item['post'];
                                $end_dt    = $item['end_dt'];
                                $offset    = ($idx % 2 === 0) ? 'md:ml-10' : 'md:mr-10';
                                $excerpt   = wp_trim_words(wp_strip_all_tags(strip_shortcodes($c_post->post_content)), 24, '…');
                                $ends_soon = $end_dt && ($end_dt->getTimestamp() - $now_ts) <= 7 * DAY_IN_SECONDS;
                            ?>
                                <article class="relative rounded-2xl border-2 border-black/10 bg-white p-4 shadow-sm sm:p-5 <?php echo esc_attr($offset); ?>">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                        <?php if (has_post_thumbnail($c_post->ID)) : ?>
                                            <div class="h-44 w-full shrink-0 overflow-hidden rounded-xl sm:h-28 sm:w-40">
                                                <?php echo get_the_post_thumbnail($c_post->ID, 'medium', [
                                                    'class'   => 'h-full w-full object-cover',
                                                    'loading' => 'lazy',
                                                ]); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="min-w-0 flex-1 pb-2 sm:pb-0">
                                            <div class="mb-1.5 flex flex-wrap items-center gap-2">
                                                <span class="inline-block rounded-full bg-orange px-3 py-0.5 text-[11px] font-bold uppercase tracking-wider text-white">
                                                    En cours
                                                </span>
                                                <?php if ($end_dt) : ?>
                                                    <span class="text-xs font-semibold <?php echo $ends_soon ? 'text-purple' : 'text-black/60'; ?>">
                                                        Fin : <?php echo esc_html(wp_date('j M Y · H \h i', $end_dt->getTimestamp())); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <h2 class="mb-1 font-title text-lg font-black uppercase leading-snug text-black sm:text-xl">
                                                <?php echo esc_html(get_the_title($c_post->ID)); ?>
                                            </h2>

                                            <?php if ($excerpt) : ?>
                                                <p class="text-xs leading-relaxed text-black/80 sm:text-sm">
                                                    <?php echo esc_html($excerpt); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex justify-end sm:-mb-8 sm:mt-1">
                                        <a href="<?php echo esc_url(get_permalink($c_post->ID)); ?>"
                                            class="btn-press inline-flex items-center justify-center rounded-full bg-yellow px-7 py-2.5 text-sm font-bold leading-none text-black hover:bg-orange hover:text-white">
                                            Voir plus
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="rounded-2xl border-2 border-black bg-white p-6">
                            <p class="text-base font-semibold text-black">
                                Aucun concours en cours pour le moment. Restez à l’écoute !
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Grille supplémentaire si plus de 2 concours actifs -->
            <?php if (!empty($extra_concours)) : ?>
                <div class="mt-20 border-t-2 border-black/10 pt-12">
                    <h2 class="mb-6 font-title text-2xl font-black uppercase text-purple sm:text-3xl">
                        Autres concours en cours
                    </h2>

                    <ul class="grid list-none grid-cols-1 gap-8 p-0 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($extra_concours as $item) :
                            $c_post   = $item['post'];
                            $start_dt = $item['start_dt'];
                            $end_dt   = $item['end_dt'];
                            $excerpt  = wp_trim_words(wp_strip_all_tags(strip_shortcodes($c_post->post_content)), 18, '…');
                        ?>
                            <li class="min-w-0">
                                <article class="news-card flex h-full flex-col overflow-hidden rounded-[2rem] border-[3px] border-purple bg-white p-5">
                                    <?php if (has_post_thumbnail($c_post->ID)) : ?>
                                        <div class="mb-4 aspect-video overflow-hidden rounded-2xl">
                                            <?php echo get_the_post_thumbnail($c_post->ID, 'medium_large', [
                                                'class'   => 'news-card__image h-full w-full object-cover',
                                                'loading' => 'lazy',
                                            ]); ?>
                                        </div>
                                    <?php endif; ?>

                                    <h3 class="mb-2 font-title text-lg font-black uppercase leading-snug text-orange">
                                        <?php echo esc_html(get_the_title($c_post->ID)); ?>
                                    </h3>

                                    <?php if ($excerpt) : ?>
                                        <p class="mb-4 text-sm leading-relaxed text-black/80">
                                            <?php echo esc_html($excerpt); ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if ($start_dt || $end_dt) : ?>
                                        <dl class="mb-5 mt-auto space-y-1.5 border-y border-black/10 py-3 text-xs sm:text-sm">
                                            <?php if ($start_dt) : ?>
                                                <div class="flex justify-between gap-2">
                                                    <dt class="font-bold text-black/65">Début :</dt>
                                                    <dd class="text-right text-black"><?php echo esc_html(wp_date('j M Y · H \h i', $start_dt->getTimestamp())); ?></dd>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($end_dt) : ?>
                                                <div class="flex justify-between gap-2">
                                                    <dt class="font-bold text-black/65">Expire le :</dt>
                                                    <dd class="text-right font-bold text-purple"><?php echo esc_html(wp_date('j M Y · H \h i', $end_dt->getTimestamp())); ?></dd>
                                                </div>
                                            <?php endif; ?>
                                        </dl>
                                    <?php endif; ?>

                                    <a href="<?php echo esc_url(get_permalink($c_post->ID)); ?>"
                                        class="btn-press mt-auto inline-flex w-fit items-center justify-center rounded-full bg-yellow px-5 py-2.5 text-sm font-bold leading-none text-black hover:bg-orange hover:text-white">
                                        Participer →
                                    </a>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Vague Beige -> Violet -->
    <svg class="full-bleed -mt-px -mb-px block h-12 w-full bg-violet text-beige md:h-20"
        viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor"
            d="M0 0 H1440 V58 Q1380 100 1320 58 T1200 58 T1080 58 T960 58 T840 58 T720 58 T600 58 T480 58 T360 58 T240 58 T120 58 T0 58 Z" />
    </svg>

    <!-- ============================================================
         2. RADIO BINGO (Violet quadrillé + Horaires + Étoile + Bulle)
         ============================================================ -->
    <?php
    $pub_img      = $get_acf('bingo_pub_image');
    $pub_img_url  = is_array($pub_img) ? ($pub_img['url'] ?? '') : $pub_img;
    $pub_url      = $get_acf('bingo_pub_lien');

    $bingo_jour   = $get_acf('bingo_jour', false, 'Mardi');
    $bingo_date   = $get_acf('bingo_date', false, 'Chaque semaine');
    $bingo_heure  = $get_acf('bingo_heure', false, '18H30');
    $bingo_regles = $get_acf('bingo_reglements');
    $bingo_pdf    = $get_acf('bingo_reglements_pdf');
    $pdf_url      = is_array($bingo_pdf) ? ($bingo_pdf['url'] ?? '') : $bingo_pdf;
    $star_beige   = $theme_asset('assets/objets/Star.png');
    ?>

    <section id="radio-bingo"
        class="full-bleed relative overflow-hidden bg-violet bg-[linear-gradient(to_right,rgba(255,255,255,0.08)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.08)_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] px-4 py-14 text-white md:py-20">

        <div class="relative z-10 mx-auto max-w-6xl">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-start">

                <?php if ($pub_img_url) : ?>
                    <aside class="hidden w-36 shrink-0 lg:block" aria-label="Commanditaire">
                        <a href="<?php echo esc_url($pub_url ?: '#'); ?>" target="_blank" rel="noopener noreferrer"
                            class="block overflow-hidden rounded-xl border-2 border-black bg-white shadow-md">
                            <img src="<?php echo esc_url($pub_img_url); ?>" alt="Commanditaire Radio Bingo" class="h-auto w-full object-cover" loading="lazy">
                        </a>
                    </aside>
                <?php endif; ?>

                <div class="min-w-0 flex-1">
                    <h2 class="mb-3 block max-w-full wrap-break-word font-title text-4xl font-black uppercase leading-tight text-white sm:text-5xl lg:text-6xl">
                        Radio Bingo
                    </h2>
                    <p class="mb-10 max-w-xl text-base leading-relaxed text-white/90 sm:text-lg lg:mb-14">
                        <?php echo esc_html($get_acf('bingo_intro', false, 'Une partie hebdomadaire en direct sur nos ondes. Vérifiez l’horaire et les règlements avant le tirage !')); ?>
                    </p>

                    <div class="grid grid-cols-1 items-end gap-12 lg:grid-cols-12">

                        <!-- Carte Calendrier HORAIRES + micro.png + Star.png -->
                        <div class="relative lg:col-span-5">
                            <div class="overflow-hidden rounded-2xl border-[3px] border-black bg-yellow shadow-[0_6px_0_#0f0e0f]">
                                <div class="border-b-[3px] border-black bg-white py-3 text-center">
                                    <h3 class="font-title text-xl font-black uppercase tracking-wide text-black">
                                        Horaires
                                    </h3>
                                </div>
                                <div class="relative flex flex-col items-center px-6 py-10 text-center">
                                    <?php if ($star_beige) : ?>
                                        <img src="<?php echo esc_url($star_beige); ?>" alt=""
                                            class="pointer-events-none absolute top-3 right-3 w-11 sm:w-12"
                                            aria-hidden="true">
                                    <?php endif; ?>

                                    <p class="font-title text-4xl font-black uppercase leading-none text-white sm:text-5xl">
                                        <?php echo esc_html($bingo_jour); ?>
                                    </p>
                                    <p class="mt-2 font-title text-lg font-extrabold uppercase tracking-wide text-white sm:text-xl">
                                        <?php echo esc_html($bingo_date); ?>
                                    </p>

                                    <div class="mt-6 rounded-xl bg-purple px-8 py-3 font-title text-lg font-black uppercase text-white shadow-sm">
                                        <?php echo esc_html($bingo_heure); ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($micro_url) : ?>
                                <img src="<?php echo esc_url($micro_url); ?>" alt=""
                                    class="pointer-events-none absolute -bottom-7 -left-7 z-20 w-24 sm:-left-10 sm:w-28"
                                    aria-hidden="true">
                            <?php endif; ?>
                        </div>

                        <!-- Bulle blanche RÈGLEMENTS + Personnage3.png bien dégagé au-dessus -->
                        <div class="relative pt-24 lg:col-span-7 lg:pt-28">
                            <?php if ($ellipse_purp) : ?>
                                <img src="<?php echo esc_url($ellipse_purp); ?>" alt=""
                                    class="pointer-events-none absolute top-4 right-4 z-0 w-32 sm:right-6 sm:w-36"
                                    aria-hidden="true">
                            <?php endif; ?>

                            <?php if ($star_mascot) : ?>
                                <img src="<?php echo esc_url($star_mascot); ?>" alt=""
                                    class="pointer-events-none absolute -top-2 right-10 z-0 w-44 sm:right-14 sm:w-52"
                                    aria-hidden="true">
                            <?php endif; ?>

                            <?php if ($star_orange) : ?>
                                <img src="<?php echo esc_url($star_orange); ?>" alt=""
                                    class="pointer-events-none absolute top-14 -right-6 z-0 hidden w-24 sm:block"
                                    aria-hidden="true">
                            <?php endif; ?>

                            <div class="relative z-10 rounded-[2rem] border-[3px] border-black bg-white p-6 text-black shadow-[0_6px_0_#0f0e0f] sm:p-8">
                                <span class="mb-3 inline-block rounded-full bg-orange px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-white">
                                    En cours
                                </span>
                                <h3 class="mb-3 font-title text-2xl font-black uppercase text-black sm:text-3xl">
                                    Règlements
                                </h3>

                                <?php if ($bingo_regles) : ?>
                                    <div class="space-y-2.5 text-sm leading-relaxed text-black/85 sm:text-base">
                                        <?php echo wp_kses_post($bingo_regles); ?>
                                    </div>
                                <?php else : ?>
                                    <ul class="list-disc space-y-2 pl-5 text-sm leading-relaxed text-black/85 sm:text-base">
                                        <li>Jeu réservé aux personnes de 18 ans et plus.</li>
                                        <li>Conservez votre numéro de carte et votre preuve d’achat.</li>
                                        <li>Lorsqu’une carte est gagnante, téléphonez immédiatement en studio au numéro annoncé en ondes.</li>
                                    </ul>
                                <?php endif; ?>

                                <?php if ($pdf_url) : ?>
                                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener noreferrer"
                                        class="btn-press mt-6 inline-flex items-center rounded-full bg-yellow px-5 py-2.5 text-sm font-bold leading-none text-black hover:bg-orange hover:text-white">
                                        Télécharger le règlement (PDF) ↗
                                    </a>
                                <?php endif; ?>

                                <!-- Pointe de bulle BD avec bordure noire -->
                                <svg class="pointer-events-none absolute right-10 -bottom-5 h-5 w-8" viewBox="0 0 32 20" fill="none" aria-hidden="true">
                                    <path d="M2 0H30L18 18L2 0Z" fill="#fefdfd" />
                                    <path d="M2 0L18 18L30 0" stroke="#0f0e0f" stroke-width="3" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Vague Violet -> Orange -->
    <svg class="full-bleed -mt-px -mb-px block h-12 w-full bg-orange text-violet md:h-20"
        viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor"
            d="M0 0 H1440 V58 Q1380 100 1320 58 T1200 58 T1080 58 T960 58 T840 58 T720 58 T600 58 T480 58 T360 58 T240 58 T120 58 T0 58 Z" />
    </svg>

    <!-- ============================================================
         3. VENTE DE CARTES EN LIGNE (Orange + photo-équipe + megaphone23 + Personnage2)
         ============================================================ -->
    <?php
    $studio_img      = $get_acf('bingo_studio_image');
    $studio_img_url  = (is_array($studio_img) ? ($studio_img['url'] ?? '') : $studio_img) ?: $studio_def;
    $achat_shortcode = $get_acf('bingo_shortcode_achat');
    $achat_url       = $get_acf('bingo_lien_achat');
    $woo_product_id  = (int) $get_acf('bingo_produit_id', false, 0);

    if (!$achat_url && $woo_product_id && function_exists('wc_get_cart_url')) {
        $achat_url = add_query_arg('add-to-cart', $woo_product_id, wc_get_cart_url());
    }
    ?>

    <section id="achat-cartes" class="full-bleed relative overflow-hidden bg-orange px-4 py-14 text-white md:py-20">
        <div class="relative mx-auto max-w-6xl">
            <!-- Cercle décoratif derrière le titre (comme sur communauté.png) -->
            <div class="pointer-events-none absolute -top-10 left-1/2 hidden h-44 w-44 rounded-full bg-white/15 lg:block" aria-hidden="true"></div>

            <div class="relative z-10 grid grid-cols-1 items-center gap-10 lg:grid-cols-12">

                <!-- Photo studio (photo-équipe.png) + Star5.png + megaphone23.png -->
                <div class="relative flex justify-center lg:col-span-4">
                    <div class="relative w-full max-w-xs">
                        <?php if ($star_yellow) : ?>
                            <img src="<?php echo esc_url($star_yellow); ?>" alt=""
                                class="pointer-events-none absolute -bottom-8 -left-8 z-0 w-36"
                                aria-hidden="true">
                        <?php endif; ?>

                        <?php if ($megaphone_url) : ?>
                            <img src="<?php echo esc_url($megaphone_url); ?>" alt=""
                                class="pointer-events-none absolute -top-10 -left-10 z-20 w-32 sm:w-36"
                                aria-hidden="true">
                        <?php endif; ?>

                        <?php if ($studio_img_url) : ?>
                            <figure class="relative z-10 m-0 aspect-square overflow-hidden rounded-3xl border-2 border-black/20 shadow-md">
                                <img src="<?php echo esc_url($studio_img_url); ?>"
                                    alt="Équipe en studio à la Radio Communautaire de La Matanie"
                                    class="h-full w-full object-cover"
                                    loading="lazy">
                            </figure>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Titre + Texte + Bouton Acheter -->
                <div class="lg:col-span-5">
                    <h2 class="mb-4 block max-w-full wrap-break-word font-title text-4xl font-black uppercase leading-tight text-white sm:text-5xl">
                        Vente de cartes en ligne
                    </h2>
                    <p class="mb-8 max-w-xl text-base leading-relaxed text-white/95 sm:text-lg">
                        <?php echo esc_html($get_acf('bingo_achat_intro', false, 'Nous soutenir, c’est contribuer à la culture québécoise ! Procurez-vous vos cartes de Radio Bingo en ligne pour le prochain tirage.')); ?>
                    </p>

                    <?php if ($achat_shortcode) : ?>
                        <div class="rounded-3xl border-2 border-black bg-white p-6 text-black">
                            <?php echo do_shortcode($achat_shortcode); ?>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($achat_url ?: '#'); ?>"
                            class="btn-press inline-flex items-center justify-center rounded-full bg-purple px-10 py-3.5 text-base font-bold leading-none text-white hover:bg-yellow hover:text-black">
                            Acheter
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mascotte Cassette (Personnage2.png) -->
                <div class="flex justify-center lg:col-span-3 lg:justify-end">
                    <?php if ($cassette_url) : ?>
                        <img src="<?php echo esc_url($cassette_url); ?>"
                            alt="Mascotte cassette audio"
                            class="w-44 max-w-full sm:w-52"
                            loading="lazy">
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Vague Orange -> Fond papier texturé -->
    <svg class="full-bleed -mt-px -mb-px block h-12 w-full bg-[#f8f7f3] bg-[linear-gradient(to_right,rgba(15,14,15,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(15,14,15,0.04)_1px,transparent_1px)] bg-[size:2.75rem_2.75rem] text-orange md:h-20"
        viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor"
            d="M0 0 H1440 V58 Q1380 100 1320 58 T1200 58 T1080 58 T960 58 T840 58 T720 58 T600 58 T480 58 T360 58 T240 58 T120 58 T0 58 Z" />
    </svg>

    <!-- ============================================================
         4. VITRINE LOCALE (Fond papier texturé)
         ============================================================ -->
    <?php
    $vitrine_posts = get_posts([
        'post_type'      => 'vitrine',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    $vitrine_grouped = array_fill_keys(array_keys($vitrine_tabs), []);
    foreach ($vitrine_posts as $v_post) {
        $raw_cat = $get_acf('vitrine_categorie', $v_post->ID);
        foreach ($extract_vitrine_slugs($raw_cat) as $cat_slug) {
            if (isset($vitrine_grouped[$cat_slug])) {
                $vitrine_grouped[$cat_slug][] = $v_post;
            }
        }
    }
    ?>

    <section id="vitrine-locale"
        class="full-bleed relative bg-[#f8f7f3] bg-[linear-gradient(to_right,rgba(15,14,15,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(15,14,15,0.04)_1px,transparent_1px)] bg-[size:2.75rem_2.75rem] px-4 pt-14 pb-12 md:pt-20 md:pb-16"
        <?php echo $paper_tex_url ? 'style="background-image: url(' . esc_url($paper_tex_url) . '); background-size: cover;"' : ''; ?>>

        <div class="relative z-10 mx-auto max-w-6xl">
            <h2 class="mb-3 block max-w-full wrap-break-word font-title text-4xl font-black uppercase leading-tight text-purple sm:text-5xl lg:text-6xl">
                Vitrine locale
            </h2>
            <p class="mb-8 max-w-2xl text-base leading-relaxed text-black/80 sm:text-lg">
                <?php echo esc_html($get_acf('vitrine_intro', false, 'Commerces d’ici, avis de décès, anniversaires, emplois et petites annonces de La Matanie.')); ?>
            </p>

            <!-- Onglets -->
            <div role="tablist" aria-label="Catégories de la vitrine locale" class="mb-10 flex flex-wrap gap-3">
                <?php foreach ($vitrine_tabs as $slug => $label) :
                    $is_active = ($active_vitrine === $slug);
                    $count     = count($vitrine_grouped[$slug]);
                ?>
                    <button type="button"
                        role="tab"
                        id="tab-<?php echo esc_attr($slug); ?>"
                        aria-controls="panel-<?php echo esc_attr($slug); ?>"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        data-vitrine-tab="<?php echo esc_attr($slug); ?>"
                        class="btn-press cursor-pointer rounded-full px-5 py-2.5 text-sm font-bold transition-colors <?php echo $is_active ? 'bg-yellow text-black' : 'bg-white text-black hover:bg-yellow'; ?>">
                        <?php echo esc_html($label); ?>
                        <?php if ($count > 0) : ?>
                            <span class="ml-1 text-xs opacity-70">(<?php echo (int) $count; ?>)</span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Panneaux -->
            <?php foreach ($vitrine_tabs as $slug => $label) :
                $is_active = ($active_vitrine === $slug);
                $items     = $vitrine_grouped[$slug];
            ?>
                <div role="tabpanel"
                    id="panel-<?php echo esc_attr($slug); ?>"
                    aria-labelledby="tab-<?php echo esc_attr($slug); ?>"
                    data-vitrine-panel="<?php echo esc_attr($slug); ?>"
                    <?php echo $is_active ? '' : 'hidden'; ?>>

                    <?php if (!empty($items)) : ?>
                        <ul class="grid list-none grid-cols-1 gap-8 p-0 sm:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($items as $v_post) :
                                $v_img     = $get_acf('vitrine_image', $v_post->ID);
                                $v_img_url = is_array($v_img) ? ($v_img['url'] ?? '') : $v_img;
                                $v_img_alt = is_array($v_img) && !empty($v_img['alt']) ? $v_img['alt'] : get_the_title($v_post->ID);

                                $v_desc       = $get_acf('vitrine_description', $v_post->ID);
                                $v_sous_titre = $get_acf('vitrine_sous_titre', $v_post->ID);
                                $v_contact    = $get_acf('vitrine_contact', $v_post->ID);
                            ?>
                                <li class="min-w-0">
                                    <article class="news-card flex h-full flex-col overflow-hidden rounded-[2rem] border-[3px] border-purple bg-white p-6">
                                        <?php if ($v_img_url) : ?>
                                            <div class="mb-4 aspect-video overflow-hidden rounded-2xl">
                                                <img src="<?php echo esc_url($v_img_url); ?>"
                                                    alt="<?php echo esc_attr($v_img_alt); ?>"
                                                    class="news-card__image h-full w-full object-cover"
                                                    loading="lazy">
                                            </div>
                                        <?php endif; ?>

                                        <span class="mb-2 inline-block w-fit rounded-full bg-purple px-3 py-1 text-xs font-bold uppercase text-white">
                                            <?php echo esc_html($label); ?>
                                        </span>

                                        <h3 class="mb-2 font-title text-xl font-black leading-snug text-orange">
                                            <?php echo esc_html(get_the_title($v_post->ID)); ?>
                                        </h3>

                                        <?php if ($v_sous_titre) : ?>
                                            <p class="mb-2 text-sm font-bold text-purple">
                                                <?php echo esc_html($v_sous_titre); ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if ($v_desc) : ?>
                                            <p class="mb-4 text-sm leading-relaxed text-black/80 sm:text-base">
                                                <?php echo esc_html(wp_trim_words(wp_strip_all_tags($v_desc), 22, '…')); ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if ($v_contact) : ?>
                                            <p class="mt-auto border-t border-black/10 pt-3 text-xs font-semibold text-black/75 sm:text-sm">
                                                <?php echo esc_html($v_contact); ?>
                                            </p>
                                        <?php endif; ?>

                                        <a href="<?php echo esc_url(get_permalink($v_post->ID)); ?>"
                                            class="btn-press mt-4 inline-flex w-fit items-center justify-center rounded-full bg-yellow px-5 py-2.5 text-sm font-bold leading-none text-black hover:bg-orange hover:text-white">
                                            Voir plus →
                                        </a>
                                    </article>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <div class="rounded-[2rem] border-2 border-black bg-white p-8 shadow-[0_6px_0_#0f0e0f]">
                            <p class="text-base font-semibold text-black">
                                Aucune publication dans « <?php echo esc_html($label); ?> » pour le moment.
                            </p>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================================================
         5. FORMULAIRE BÉNÉVOLAT
         ============================================================ -->
    <section id="benevolat"
        class="full-bleed relative bg-[#f8f7f3] bg-[linear-gradient(to_right,rgba(15,14,15,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(15,14,15,0.04)_1px,transparent_1px)] bg-[size:2.75rem_2.75rem] px-4 pt-6 pb-20 md:pb-24"
        <?php echo $paper_tex_url ? 'style="background-image: url(' . esc_url($paper_tex_url) . '); background-size: cover;"' : ''; ?>>

        <div class="relative z-10 mx-auto max-w-6xl">
            <div class="grid grid-cols-1 overflow-hidden rounded-[2.5rem] border-[3px] border-black bg-white shadow-[0_8px_0_#0f0e0f] lg:grid-cols-12">

                <div class="flex flex-col justify-between bg-yellow p-8 text-black lg:col-span-5 lg:p-10">
                    <div>
                        <span class="mb-4 inline-block rounded-full bg-purple px-4 py-1 text-xs font-bold uppercase tracking-wider text-white">
                            Implication
                        </span>
                        <h2 class="mb-4 font-title text-3xl font-black uppercase leading-tight text-black sm:text-4xl">
                            Devenir bénévole
                        </h2>
                        <p class="text-base leading-relaxed text-black/85">
                            <?php echo esc_html($get_acf('benevolat_intro', false, 'Donnez un coup de main à l’animation, au Radio Bingo ou lors de nos événements locaux !')); ?>
                        </p>
                    </div>

                    <ul class="mt-8 list-none space-y-2 border-t-2 border-black/15 pt-6 text-sm font-bold text-black">
                        <li>✦ Ambiance conviviale et équipe passionnée</li>
                        <li>✦ Formation offerte sur place</li>
                        <li>✦ Horaire flexible selon vos disponibilités</li>
                    </ul>
                </div>

                <div class="p-8 lg:col-span-7 lg:p-10">
                    <?php if ($benevolat_status === 'succes') : ?>
                        <div role="status" class="mb-6 rounded-2xl border-2 border-black bg-yellow p-4 text-sm font-bold text-black sm:text-base">
                            Merci ! Votre candidature bénévole a bien été envoyée.
                        </div>
                    <?php elseif ($benevolat_status === 'erreur') : ?>
                        <div role="alert" class="mb-6 rounded-2xl border-2 border-black bg-orange p-4 text-sm font-bold text-white sm:text-base">
                            Une erreur est survenue. Veuillez vérifier les champs obligatoires.
                        </div>
                    <?php endif; ?>

                    <?php $benevolat_shortcode = $get_acf('benevolat_shortcode'); ?>

                    <?php if ($benevolat_shortcode) : ?>
                        <div class="text-base">
                            <?php echo do_shortcode($benevolat_shortcode); ?>
                        </div>
                    <?php else : ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <input type="hidden" name="action" value="efc_benevolat_submit">
                            <input type="hidden" name="redirect_to" value="<?php echo esc_url(get_permalink()); ?>">
                            <?php wp_nonce_field('efc_benevolat_action', 'efc_benevolat_nonce'); ?>

                            <div class="hidden" aria-hidden="true">
                                <label for="efc_site_web">Ne pas remplir</label>
                                <input type="text" id="efc_site_web" name="site_web" tabindex="-1" autocomplete="off">
                            </div>

                            <div>
                                <label for="ben_nom" class="mb-1.5 block text-sm font-bold text-black">
                                    Nom complet <span class="text-orange">*</span>
                                </label>
                                <input type="text" id="ben_nom" name="nom" required
                                    class="w-full rounded-xl border-2 border-black/25 bg-white px-4 py-2.5 text-base text-black focus:border-purple focus:outline-none">
                            </div>

                            <div>
                                <label for="ben_courriel" class="mb-1.5 block text-sm font-bold text-black">
                                    Courriel <span class="text-orange">*</span>
                                </label>
                                <input type="email" id="ben_courriel" name="courriel" required
                                    class="w-full rounded-xl border-2 border-black/25 bg-white px-4 py-2.5 text-base text-black focus:border-purple focus:outline-none">
                            </div>

                            <div>
                                <label for="ben_tel" class="mb-1.5 block text-sm font-bold text-black">
                                    Téléphone
                                </label>
                                <input type="tel" id="ben_tel" name="telephone"
                                    class="w-full rounded-xl border-2 border-black/25 bg-white px-4 py-2.5 text-base text-black focus:border-purple focus:outline-none">
                            </div>

                            <div>
                                <label for="ben_interet" class="mb-1.5 block text-sm font-bold text-black">
                                    Secteur d’intérêt
                                </label>
                                <select id="ben_interet" name="interet"
                                    class="w-full rounded-xl border-2 border-black/25 bg-white px-4 py-2.5 text-base text-black focus:border-purple focus:outline-none">
                                    <option value="Animation / émission">Animation / émission</option>
                                    <option value="Radio Bingo">Radio Bingo</option>
                                    <option value="Événements et technique">Événements et technique</option>
                                    <option value="Soutien administratif">Soutien administratif</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="ben_message" class="mb-1.5 block text-sm font-bold text-black">
                                    Disponibilités et message <span class="text-orange">*</span>
                                </label>
                                <textarea id="ben_message" name="message" rows="3" required
                                    class="w-full rounded-xl border-2 border-black/25 bg-white px-4 py-2.5 text-base text-black focus:border-purple focus:outline-none"></textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <button type="submit"
                                    class="btn-press cursor-pointer rounded-full bg-purple px-8 py-3.5 text-sm font-bold leading-none text-white hover:bg-orange">
                                    Envoyer ma candidature →
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('[data-vitrine-tab]');
        const panels = document.querySelectorAll('[data-vitrine-panel]');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const slug = tab.dataset.vitrineTab;
                tabs.forEach((t) => {
                    const active = t.dataset.vitrineTab === slug;
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                    t.classList.toggle('bg-yellow', active);
                    t.classList.toggle('bg-white', !active);
                });
                panels.forEach((p) => {
                    p.hidden = p.dataset.vitrinePanel !== slug;
                });

                const url = new URL(window.location.href);
                url.searchParams.set('vitrine', slug);
                window.history.replaceState({}, '', url);
            });
        });
    });
</script>

<?php get_footer(); ?>