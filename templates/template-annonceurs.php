<?php
/* Template Name: Annonceurs */


get_header(); ?>

<svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-[#fdbe00] z-20 md:h-24 bg-orange" viewBox="0 0 1440 100"
    preserveAspectRatio="none" aria-hidden="true">
    <path fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<main class=" w-full ">
    <?php

    // url du fichier
    function kit_media_url($champ)
    {
        if (is_array($champ)) {
            return $champ['url'] ?? '';
        }
        if (is_numeric($champ)) {
            return wp_get_attachment_url($champ);
        }
        return $champ ?: '';
    }

    while (have_posts()) : the_post();
        $titre       = get_field('titre');
        $logo_image_url    = get_field('logo_image');
        $logo_url    = kit_media_url(get_field('logo'));
        $typo_image_url = get_field('typo_image');
        $typo_url  = kit_media_url(get_field('typographie'));
        $courriel = get_field('courriel');
        $telephone = get_field('telephone');

    ?>


        <div>

            <section class="bg-orange px-4 py-10 ">

                <div class="container mx-auto">

                    <h1 class="text-4xl md:text-7xl font-bold text-white my-12 text-right rotate-[3deg]">
                        <?php echo esc_html($titre ?: get_the_title()); ?>
                    </h1>

                    <div class="grid gap-6 md:grid-cols-2 pb-12">

                        <!-- logos -->
                        <?php if ($logo_image_url || $logo_url) : ?>
                            <article class="bg-beige rounded-[24px] border-t-2 border-x-2 border-b-8 border-black p-6 flex flex-col gap-4">
                                <h2 class="text-2xl font-bold text-black">Logos</h2>

                                <?php if ($logo_image_url) : ?>
                                    <img class="w-full h-48 object-contain" src="<?php echo esc_url($logo_image_url); ?>" alt="Logo de la radio">
                                <?php endif; ?>

                                <?php if ($logo_url) : ?>
                                    <p class="text-black text-2xl">Téléchargez les logos utilisés par la radio.</p>

                                    <a href="<?php echo esc_url($logo_url); ?>" download
                                        class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] btn-press">
                                        Télécharger les logos
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endif; ?>

                        <!-- typos -->
                        <?php if ($typo_image_url || $typo_url) : ?>
                            <article class="bg-beige rounded-[24px] border-t-2 border-x-2 border-b-8 border-black p-6 flex flex-col gap-4">
                                <h2 class="text-2xl font-bold text-black">Typographie</h2>

                                <?php if ($typo_image_url) : ?>
                                    <img class="w-full h-20 my-14 object-contain" src="<?php echo esc_url($typo_image_url); ?>" alt="Aperçu de la typographie">
                                <?php endif; ?>

                                <?php if ($typo_url) : ?>
                                    <p class="text-black text-2xl">Téléchargez les polices d'écriture utilisées par la radio.</p>
                                    <a href="<?php echo esc_url($typo_url); ?>" download
                                        class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] btn-press">
                                        Télécharger les typographies
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endif; ?>

                    </div>
                </div>

            </section>

            <svg class="hero-wave -mt-px -mb-px block h-16 w-full bg-beige rotate-180 text-orange md:h-24" viewBox="0 0 1440 100"
                preserveAspectRatio="none" aria-hidden="true">
                <path fill="currentColor"
                    d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
            </svg>


            <!-- nous joindre -->
            <section class="bg-beige px-4 md:px-8 py-8 md:py-24 overflow-hidden md:overflow-visible">

                <article class="container mx-auto mx-32 relative flex flex-col scale-110 md:flex-row md:items-center   md:gap-24">

                    <div class="flex flex-col items-center ml-32 md:block">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/disco-ball.png"
                            alt="" aria-hidden="true"
                            class="w-16 self-end z-1  md:w-38 md:absolute md:-top-24 md:right-50">

                        <h2 class="text-4xl md:text-6xl text-violet rotate-[-3deg]">On jase?</h2>

                        <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/phone2.png"
                            alt="main qui tient un téléphone"
                            class="rotate-[30deg] w-32 mt-6 md:mt-0 md:w-50 md:ml-30">


                    </div>

                    <div class="ml-50">
                        <div class="bg-violet w-full  md:w-150 p-14 md:p-12 pb-16 md:pb-24 md:pr-[35%] min-h-[18rem] [-webkit-mask-size:100%_70%] [mask-size:100%_70%] md:[-webkit-mask-size:100%_70%] md:[mask-size:100%_70%]"
                            style="-webkit-mask-image: url('<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/Union.png');
                        mask-image: url('<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/Union.png');
                        -webkit-mask-position: center; mask-position: center;
                        -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat;">

                            <h2 class="text-5xl text-beige mb-8 mt-24 md:w-100">Équipe des ventes :</h2>

                            <ul class="w-full md:w-100">
                                <li class="mb-6 text-beige break-words">COURRIEL :
                                    <a class="hover:text-yellow ease-in-out duration-200" href="mailto:radiolamatanie@gmail.com">
                                        <?php echo esc_html($courriel); ?></a>
                                </li>

                                <li class="mb-6 text-beige">TÉLÉPHONE :
                                    <a class="hover:text-yellow ease-in-out duration-200" href="tel:+15812327434">
                                        <?php echo esc_html($telephone); ?></a>
                                </li>
                            </ul>

                        </div>
                    </div>

                </article>

            </section>



        </div>
    <?php endwhile; ?>


</main>

<?php get_footer(); ?>