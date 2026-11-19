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
        <section class="px-4 py-12">
            <div>
                <div class="flex">

                    <!-- titre et texte -->
                    <div class=" mb-50 -rotate-3 scale-110 mt-[12%]">
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


                    <!-- météo en direct -->
                    <?php if ($meteo) : ?>
                        <div>

                            <!-- maintenant -->
                            <div>
                                <h3>Matane, QC</h3>
                                <!-- doc human_time_diff : https://developer.wordpress.org/reference/functions/human_time_diff/ -->
                                <p>Mis à jour il y a <?php echo esc_html(human_time_diff($meteo['mis_a_jour'])); ?></p>
                                <img src="<?php echo esc_url(efc_meteo_icone($meteo['code'], $meteo['jour'])); ?>" alt="<?php echo esc_attr(efc_meteo_description($meteo['code'])); ?>">
                                <p><?php echo esc_html($meteo['temp']); ?>°</p>
                                <p>Ressenti <?php echo esc_html($meteo['ressenti']); ?>° · Max <?php echo esc_html($meteo['max']); ?>° · Min <?php echo esc_html($meteo['min']); ?>°</p>
                            </div>

                            <!-- prochaines heures -->
                            <div>
                                <h4>Prochaines heures</h4>
                                <ul>
                                    <?php foreach ($meteo['prochaines'] as $i => $heure) : ?>
                                        <li>
                                            <p><?php echo $i === 0 ? 'Maintenant' : esc_html(wp_date('G \h', $heure['heure'], new DateTimeZone(EFC_METEO_FUSEAU))); ?></p>
                                            <img src="<?php echo esc_url(efc_meteo_icone($heure['code'], $heure['jour'])); ?>" alt="<?php echo esc_attr(efc_meteo_description($heure['code'])); ?>">
                                            <p><?php echo esc_html($heure['temp']); ?>°</p>
                                            <p class="flex items-center gap-1"><img class="w-3 h-3" src="<?php echo esc_url(get_template_directory_uri() . '/assets/objets/icons/mini-pluie.svg'); ?>" alt="Probabilité de pluie"><?php echo esc_html($heure['pluie']); ?> %</p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- vent -->
                            <div>
                                <h4>Vent</h4>
                                <p><?php echo esc_html($meteo['vent']); ?> km/h</p>
                                <p>Rafales <?php echo esc_html($meteo['rafales']); ?> km/h · <?php echo esc_html($meteo['direction']); ?></p>
                            </div>

                            <!-- humidité -->
                            <div>
                                <h4>Humidité</h4>
                                <p><?php echo esc_html($meteo['humidite']); ?> %</p>
                                <progress max="100" value="<?php echo esc_attr($meteo['humidite']); ?>"></progress>
                                <p>Point de rosée <?php echo esc_html($meteo['rosee']); ?>°</p>
                            </div>

                            <!-- marée -->
                            <?php if ($meteo['maree']) : ?>
                                <div>
                                    <h4>Marée</h4>
                                    <p><?php echo $meteo['maree']['montante'] ? 'Montante' : 'Descendante'; ?></p>
                                    <p>
                                        <?php echo $meteo['maree']['montante'] ? 'Pleine mer' : 'Basse mer'; ?>
                                        à <?php echo esc_html(efc_meteo_heure($meteo['maree']['heure'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- soleil -->
                            <div>
                                <h4>Soleil</h4>
                                <p>Lever <?php echo esc_html(efc_meteo_heure($meteo['lever'])); ?></p>
                                <p>Coucher <?php echo esc_html(efc_meteo_heure($meteo['coucher'])); ?></p>
                            </div>

                            <!-- lien vers un site météo -->
                            <a href="https://meteo.gc.ca/fr/location/index.html?coords=48.849,-67.533" target="_blank" rel="noopener noreferrer">Voir plus</a>

                        </div>
                    <?php endif; ?>

                </div>
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