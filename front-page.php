<?php get_header(); ?>
<main class="container mx-auto px-4 py-12 bg-beige">
    <!-- Météo -->
    <?php
    $meteo_title = get_field('meteo-title');
    $meteo_text = get_field('meteo-text');
    $meteo_announcement = get_field('meteo-announcement');
    $meteo_date = get_field('meteo-date');

    // météo en direct (includes/meteo.php)
    $meteo = function_exists('efc_get_meteo') ? efc_get_meteo() : null;

    if ($meteo_title || $meteo_text || $meteo_announcement) : ?>
        <section>
            <div class="grid grid-cols-1 lg:grid-cols-[28rem_1fr] gap-16 items-start px-4 py-12">

                <!-- titre et texte -->
                <div class="mb-50 -rotate-3 ">
                    <div>
                        <?php if ($meteo_title) : ?>
                            <h2 class="text-orange text-6xl w-110 text-right leading-14"><?php echo esc_html($meteo_title); ?></h2>
                        <?php endif; ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/Personnage2.png'); ?>" alt="img-météo" class="w-50 -mt-35 -translate-x-10 rotate-2">
                    </div>
                    <?php if ($meteo_text) : ?>
                        <p class="text-3xl font-light ml-10"><?php echo nl2br(esc_html($meteo_text)); ?></p>
                    <?php endif; ?>
                </div>

                <!-- météo -->
                <?php if ($meteo) : ?>
                    <div class="relative min-w-0 bg-white rounded-[3rem] p-6 lg:p-8">

                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/soleil.png'); ?>" alt="" class="pointer-events-none absolute -top-16 right-28 w-40 hidden md:block">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/nuage.png'); ?>" alt="" class="pointer-events-none absolute -top-14 -right-12 w-64 hidden md:block">

                        <h2 class="text-orange text-6xl uppercase mb-6">Météo</h2>

                        <div class="flex flex-col gap-6">

                            <div class="grid grid-cols-1 xl:grid-cols-[2fr_3fr] gap-6 bg-blue text-white rounded-[2rem] p-6 lg:p-8">

                                <!-- maintenant -->
                                <div class="flex flex-col">
                                    <h3 class="text-2xl uppercase leading-none">Matane, QC</h3>
                                    <!-- doc human_time_diff : https://developer.wordpress.org/reference/functions/human_time_diff/ -->
                                    <p class="text-xs opacity-80">Mis à jour il y a <?php echo esc_html(human_time_diff($meteo['mis_a_jour'])); ?></p>
                                    <div class="flex flex-1 items-center justify-end gap-3 my-4">
                                        <img class="w-28 h-28" src="<?php echo esc_url(efc_meteo_icone($meteo['code'], $meteo['jour'])); ?>" alt="<?php echo esc_attr(efc_meteo_description($meteo['code'])); ?>">
                                        <p class="font-title font-black text-8xl leading-none"><?php echo esc_html($meteo['temp']); ?>°</p>
                                    </div>
                                    <p class="text-xs opacity-90">Ressenti <?php echo esc_html($meteo['ressenti']); ?>° · Max <?php echo esc_html($meteo['max']); ?>° · Min <?php echo esc_html($meteo['min']); ?>°</p>
                                </div>

                                <!-- prochaines heures -->
                                <div class="flex flex-col gap-3">
                                    <h4 class="text-sm text-white font-title font-black uppercase tracking-wide">Prochaines heures</h4>
                                    <ul class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                        <?php foreach ($meteo['prochaines'] as $i => $heure) : ?>
                                            <li class="flex flex-col items-center gap-1 rounded-2xl py-3 px-1 text-black <?php echo $i === 0 ? 'bg-beige' : 'bg-white'; ?>">
                                                <p class="text-xs font-bold text-black/60 whitespace-nowrap"><?php echo $i === 0 ? 'Maintenant' : esc_html(wp_date('G \h', $heure['heure'], new DateTimeZone(EFC_METEO_FUSEAU))); ?></p>
                                                <img class="w-8 h-8" src="<?php echo esc_url(efc_meteo_icone($heure['code'], $heure['jour'])); ?>" alt="<?php echo esc_attr(efc_meteo_description($heure['code'])); ?>">
                                                <p class="font-title font-black text-4xl leading-none"><?php echo esc_html($heure['temp']); ?>°</p>
                                                <p class="flex items-center gap-1 text-xs font-bold text-blue"><img class="w-3 h-3" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/icons/mini-pluie.svg'); ?>" alt="Probabilité de pluie"><?php echo esc_html($heure['pluie']); ?> %</p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- 4 tuiles -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                                <!-- vent -->
                                <div class="flex flex-col gap-4 min-h-56 bg-beige rounded-[2rem] p-6">
                                    <div>
                                        <h4 class="text-sm text-orange font-title font-black uppercase tracking-wide mb-4">Vent</h4>
                                        <p class="flex items-baseline gap-2">
                                            <span class="font-title font-black text-7xl leading-none"><?php echo esc_html($meteo['vent']); ?></span>
                                            <span class="text-lg font-bold">km/h</span>
                                        </p>
                                    </div>
                                    <p class="mt-auto text-sm text-black/60">Rafales <?php echo esc_html($meteo['rafales']); ?> km/h · <?php echo esc_html($meteo['direction']); ?></p>
                                </div>

                                <!-- humidité -->
                                <div class="flex flex-col gap-4 min-h-56 bg-[#3d3b3b] text-beige rounded-[2rem] p-6">
                                    <div>
                                        <h4 class="text-sm text-blue font-title font-black uppercase tracking-wide mb-4">Humidité</h4>
                                        <p class="flex items-baseline gap-2">
                                            <span class="font-title font-black text-7xl leading-none"><?php echo esc_html($meteo['humidite']); ?></span>
                                            <span class="text-lg font-bold">%</span>
                                        </p>
                                    </div>
                                    <div class="mt-auto flex flex-col gap-3">
                                        <!-- barre d'humidité (une div plutôt que <progress>, plus facile à styliser) -->
                                        <div class="h-2 rounded-full bg-white/20" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr($meteo['humidite']); ?>">
                                            <div class="h-full rounded-full bg-blue" style="width: <?php echo esc_attr($meteo['humidite']); ?>%"></div>
                                        </div>
                                        <p class="text-sm">Point de rosée <?php echo esc_html($meteo['rosee']); ?>°</p>
                                    </div>
                                </div>

                                <!-- marée -->
                                <?php if ($meteo['maree']) : ?>
                                    <div class="relative overflow-hidden flex flex-col gap-4 min-w-0 min-h-56 bg-blue text-white rounded-[2rem] p-6">
                                        <div class="relative">
                                            <h4 class="text-sm text-beige font-title font-black uppercase tracking-wide mb-4">Marée</h4>
                                            <!-- « Descendante » est long : taille plus petite dans les tuiles étroites pour rester aligné -->
                                            <p class="font-title font-black text-3xl xl:text-2xl 2xl:text-3xl uppercase leading-none break-words"><?php echo $meteo['maree']['montante'] ? 'Montante' : 'Descendante'; ?></p>
                                        </div>
                                        <!-- vagues -->
                                        <svg class="absolute inset-x-0 bottom-0 w-full h-24" viewBox="0 0 400 100" preserveAspectRatio="none" aria-hidden="true">
                                            <path d="M0 30 Q50 0 100 25 T200 25 T300 25 T400 25 V100 H0Z" fill="#fefdfd" opacity=".5"/>
                                            <path d="M0 45 Q50 20 100 40 T200 40 T300 40 T400 40 V100 H0Z" fill="#fef2ca"/>
                                        </svg>
                                        <p class="relative mt-auto text-sm text-blue">
                                            <?php echo $meteo['maree']['montante'] ? 'Pleine mer' : 'Basse mer'; ?>
                                            à <?php echo esc_html(efc_meteo_heure($meteo['maree']['heure'])); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- soleil et voir plus -->
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-4 bg-orange rounded-[2rem] p-6">
                                        <h4 class="text-sm text-white font-title font-black uppercase tracking-wide">Soleil</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm text-black/60">Lever</p>
                                                <p class="text-2xl font-bold whitespace-nowrap"><?php echo esc_html(efc_meteo_heure($meteo['lever'])); ?></p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-black/60">Coucher</p>
                                                <p class="text-2xl font-bold whitespace-nowrap"><?php echo esc_html(efc_meteo_heure($meteo['coucher'])); ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- lien vers un site météo -->
                                    <a class="flex flex-1 items-center justify-center min-h-16 bg-white border-2 border-black rounded-full shadow-[0_6px_0_var(--color-black)] text-2xl font-bold hover:scale-105 ease-in-out duration-300" href="https://meteo.gc.ca/fr/location/index.html?coords=48.849,-67.533" target="_blank" rel="noopener noreferrer">Voir plus</a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <!-- annonce -->
            <div>
                <?php if ($meteo_date) : ?>
                    <p><?php echo esc_html($meteo_date); ?></p>
                <?php endif; ?>
                <?php if ($meteo_announcement) : ?>
                    <p><?php echo nl2br(esc_html($meteo_announcement)); ?></p>
                <?php endif; ?>
            </div>
            <!-- état des routes -->
            <div>
                <iframe width="100%" height="450"
                    src="https://embed.waze.com/fr/iframe?zoom=11&amp;lat=48.8486&amp;lon=-67.5333&amp;ct=livemap"
                    title="État des routes en Matanie"
                    loading="lazy"
                    allowfullscreen></iframe>

                <!-- lien vers les conditions routières officielles -->
                <a href="https://www.quebec511.info/fr/Carte/Default.aspx" target="_blank" rel="noopener noreferrer">
                    Conditions routières officielles
                </a>

            </div>
        </section>
    <?php endif; ?>

    <?php get_footer(); ?>