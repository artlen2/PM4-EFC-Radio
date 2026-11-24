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
        <section class="section-meteo" style="background: url('<?php echo esc_url(get_template_directory_uri() . '/images/fond-meteo.png'); ?>') right top / auto 100% no-repeat;">
            <div class="grid grid-cols-1 lg:grid-cols-[28rem_1fr] gap-16 items-start px-4 py-12">

                <!-- titre et texte -->
                <div class="mb-50 -rotate-3 mt-3">
                    <div>
                        <?php if ($meteo_title) : ?>
                            <h2 class="text-orange text-6xl w-110 text-right leading-14"><?php echo esc_html($meteo_title); ?></h2>
                        <?php endif; ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/Personnage2.png'); ?>" alt="img-météo" class="w-60 -mt-35 -translate-x-10 rotate-2">
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

                        <h2 class="text-5xl uppercase mb-3 text-orange-light">Météo</h2>

                        <div class="flex flex-col gap-6">

                            <div class="grid grid-cols-1 xl:grid-cols-[2fr_3fr] gap-10 bg-blue text-white rounded-[2rem] p-4 lg:p-6">
                                <!-- icone à changer pour la météo -->
                                <!-- maintenant -->
                                <div class="flex flex-col">
                                    <h3 class="text-2xl uppercase leading-none">Matane, QC</h3>
                                    <!-- doc human_time_diff : https://developer.wordpress.org/reference/functions/human_time_diff/ -->
                                    <p class="text-xs opacity-80">Mis à jour il y a <?php echo esc_html(human_time_diff($meteo['mis_a_jour'])); ?></p>
                                    <div class="flex flex-1 items-center justify-end gap-3 -translate-y-5">
                                        <img class="w-24 h-24" src="<?php echo esc_url(efc_meteo_icone($meteo['code'], $meteo['jour'])); ?>" alt="<?php echo esc_attr(efc_meteo_description($meteo['code'])); ?>">
                                        <p class="font-title font-black text-6xl leading-none"><?php echo esc_html($meteo['temp']); ?>°</p>
                                    </div>
                                    <p class="text-xs opacity-90 -mt-6">Ressenti <?php echo esc_html($meteo['ressenti']); ?>° · Max <?php echo esc_html($meteo['max']); ?>° · Min <?php echo esc_html($meteo['min']); ?>°</p>
                                </div>

                                <!-- prochaines heures -->
                                <div class="flex flex-col gap-3">
                                    <h4 class="text-sm text-white font-title font-black uppercase tracking-wide">Prochaines heures</h4>
                                    <ul class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                        <?php foreach ($meteo['prochaines'] as $i => $heure) : ?>
                                            <li class="flex flex-col items-center gap-1 rounded-2xl py-5 px-3 text-black <?php echo $i === 0 ? 'bg-beige' : 'bg-white'; ?>">
                                                <p class="text-xs font-bold text-black/60 whitespace-nowrap"><?php echo $i === 0 ? 'En direct' : esc_html(wp_date('G \h', $heure['heure'], new DateTimeZone(EFC_METEO_FUSEAU))); ?></p>
                                                <img class="w-8 h-8" src="<?php echo esc_url(efc_meteo_icone($heure['code'], $heure['jour'])); ?>" alt="<?php echo esc_attr(efc_meteo_description($heure['code'])); ?>">
                                                <p class="font-title font-black text-4xl leading-none  text-black"><?php echo esc_html($heure['temp']); ?>°</p>
                                                <p class="flex items-center gap-1 text-xs font-bold text-blue"><img class="w-3 h-3" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/icons/mini-pluie.svg'); ?>" alt="Probabilité de pluie"><?php echo esc_html($heure['pluie']); ?> %</p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- 4 tuiles -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                                <!-- vent -->
                                <div class="flex flex-col gap-4 min-h-56 bg-[#EFF0F4] rounded-[2rem] p-6">
                                    <div>
                                        <h4 class="text-sm text-yellow font-title font-black uppercase tracking-wide mb-2">Vent</h4>
                                        <p class="flex items-baseline gap-2">
                                            <span class="font-title font-black text-7xl leading-none"><?php echo esc_html($meteo['vent']); ?></span>
                                            <span class="text-lg font-bold">km/h</span>
                                        </p>
                                    </div>
                                    <p class="mt-auto text-sm text-black/60">Rafales <?php echo esc_html($meteo['rafales']); ?> km/h · <?php echo esc_html($meteo['direction']); ?></p>
                                </div>

                                <!-- humidité -->
                                <div class="flex flex-col gap-4 min-h-56 bg-[#3d3b3b] text-white rounded-[2rem] p-6">
                                    <div>
                                        <h4 class="text-sm text-blue font-title font-black uppercase tracking-wide mb-2">Humidité</h4>
                                        <p class="flex items-baseline gap-2 text-white">
                                            <span class="font-title font-black text-7xl leading-none"><?php echo esc_html($meteo['humidite']); ?></span>
                                            <span class="text-lg font-bold">%</span>
                                        </p>
                                    </div>
                                    <div class="mt-auto flex flex-col gap-3">
                                        <!-- barre humidité -->
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
                                            <h4 class="text-sm text-beige font-title font-black uppercase tracking-wide mb-2">Marée</h4>
                                            <!-- montante / descendante -->
                                            <?php if ($meteo['maree']['montante']) : ?>
                                                <p class="font-title font-black text-2xl  uppercase leading-none wrap-break-word">Montante</p>
                                            <?php else : ?>
                                                <p class="font-title font-black text-lg  uppercase leading-none wrap-break-word">Descendante</p>
                                            <?php endif; ?>
                                        </div>
                                        <!-- vagues -->
                                        <!-- src: https://codepen.io/andyfitz/pen/aZrKdV -->
                                        <div class="absolute inset-x-0 bottom-0" aria-hidden="true">
                                            <svg class="block w-full h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
                                                <defs>
                                                    <path id="maree-vague" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18v44h-352z" />
                                                </defs>
                                                <g class="maree-parallax">
                                                    <!-- vague de derrière -->
                                                    <use href="#maree-vague" x="50" y="0" fill="#ABCEE6" />
                                                    <!-- vague du devant -->
                                                    <use href="#maree-vague" x="50" y="8" fill="#FFF0CB" />
                                                </g>
                                            </svg>
                                            <!-- bloc plein sous les vagues, même couleur que la vague du devant -->
                                            <div class="h-16 -mt-px bg-[#FFF0CB]"></div>
                                        </div>
                                        <p class="relative mt-auto text-sm text-blue">
                                            <?php echo $meteo['maree']['montante'] ? 'Pleine mer' : 'Basse mer'; ?>
                                            à <?php echo esc_html(efc_meteo_heure($meteo['maree']['heure'])); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- soleil et voir plus -->
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-4 bg-yellow rounded-[2rem] p-6">
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
                                    <a class="flex flex-1 items-center justify-center min-h-16 bg-white rounded-full btn-press text-2xl font-bold" href="https://meteo.gc.ca/fr/location/index.html?coords=48.849,-67.533" target="_blank" rel="noopener noreferrer">Voir plus</a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <!-- état des routes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-stretch px-4 py-12 -mt-40 mb-20 lg:h-[600px] lg:box-content">
                <!-- annonce -->
                <div class="relative min-w-0 bg-white rounded-[3rem] p-6 lg:p-8 overflow-hidden">
                    <h2 class="text-5xl uppercase mb-3 text-orange-light w-70 relative z-10 mt-3">État des routes</h2>
                    <h3 class="text-3xl uppercase mb-3 text-black relative z-10">Avertissements en vigueur</h3>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/highway.png'); ?>" alt="#" class="z-5 pointer-events-none w-150 absolute top-15 -right-10 ">
                    <div class="relative bg-yellow rounded-[2rem] p-6 flex flex-col gap-4 z-20 mt-15 h-[300px]">

                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/alert.png'); ?>" alt="#" class="w-35 absolute -top-15 left-0 pointer-none">

                        <?php if ($meteo_date) : ?>
                            <p class="font-bold archivo text-white text-right  text-2xl uppercase"><?php echo esc_html($meteo_date); ?></p>
                        <?php endif; ?>

                        <?php if ($meteo_announcement) : ?>
                            <p class="text-black text-[1.25rem]"><?php echo nl2br(esc_html($meteo_announcement)); ?></p>
                        <?php endif; ?>

                    </div>

                </div>
                <!-- état des routes -->
                <div class="h-[400px] lg:h-full">
                    <div class="carte-routes h-full">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d49965.67457830342!2d-67.56261623299348!3d48.83919038373369!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfr!2sca!4v1795459922725!5m2!1sfr!2sca" width="100%" style="border:0; display:block; height:100%;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        <!-- bouton Québec 511 par-dessus le bouton « Ouvrir dans Maps » de Google -->
                        <a class="btn-quebec511" href="https://www.quebec511.info/fr/Carte/Default.aspx" target="_blank" rel="noopener noreferrer">
                            Ouvrir Québec 511
                            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                                <path fill="currentColor" d="M19 19H5V5h7V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z" />
                            </svg>
                        </a>
                    </div>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php get_footer(); ?>