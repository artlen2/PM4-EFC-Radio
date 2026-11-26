<?php
/* Template Name: Soutenir */
get_header();

if (!function_exists('efc_soutenir_field')) {
    function efc_soutenir_field($name, $fallback = '')
    {
        $value = function_exists('get_field') ? get_field($name) : '';
        if ($value === null || $value === false || $value === '') {
            $value = get_post_meta(get_the_ID(), '_efc_' . $name, true);
        }
        return $value !== '' && $value !== null ? $value : $fallback;
    }
}

// custom posts
while (have_posts()) : the_post();
    $asset = get_template_directory_uri() . '/assets/objets/';
    $member_title = efc_soutenir_field('member_title', get_the_title());
    $member_text = efc_soutenir_field(
        'member_text',
        'Tentez votre chance au Radio Bingo en direct sur nos ondes et participez aux concours offerts par nos partenaires ! De beaux prix vous attendent, et chaque participation aide à faire vivre votre radio communautaire.'
    );
    $member_button = efc_soutenir_field('member_button', 'S’inscrire');
    $member_url = efc_soutenir_field('member_url', '#');
    $donate_title = efc_soutenir_field('donate_title', 'Faites un don');
    $donate_badge = efc_soutenir_field('donate_badge', 'Chaque geste compte');
    $donate_text = efc_soutenir_field(
        'donate_text',
        'Votre don aide notre radio communautaire à faire vivre une programmation locale, vivante et proche des gens.'
    );
    $donate_form_shortcode = efc_soutenir_field('donate_form_shortcode', '');
?>

<!--  -->

<main class="overflow-x-clip bg-violet font-sans text-black">
    <svg class="hero-wave -mb-px -mt-px block h-16 w-full rotate-180 bg-white text-yellow md:h-24"
        viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor"
            d="M0 40 Q60 0 120 40 T240 40 T360 40 T480 40 T600 40 T720 40 T840 40 T960 40 T1080 40 T1200 40 T1320 40 T1440 40 V100 H0 Z" />
    </svg>
<!-- texture papier en arriere -->
    <section class="relative isolate overflow-hidden bg-white" aria-labelledby="member-title">
        <img src="<?php echo esc_url($asset . 'overlay-texturepapier.png'); ?>" alt="" aria-hidden="true"
            class="pointer-events-none absolute inset-0 size-full object-cover opacity-40">
        <img src="<?php echo esc_url($asset . 'Star2.png'); ?>" alt="" aria-hidden="true"
            class="pointer-events-none absolute right-[clamp(1rem,8vw,7rem)] top-6 z-10 w-[clamp(7rem,14vw,13rem)]">

        <div class="container relative mx-auto grid min-h-[32rem] items-center gap-5 px-6 py-16 md:min-h-[40rem] md:grid-cols-[minmax(0,1fr)_20rem] md:gap-10 md:px-10 md:py-24">
            <img src="<?php echo esc_url($asset . 'micro.png'); ?>" alt="" aria-hidden="true"
                class="absolute left-2 top-5 z-10 w-20 md:-left-8 md:top-1/2 md:w-48 md:-translate-y-1/2 lg:-left-46 lg:w-118">

            <div class="relative z-10 pt-24 md:py-4 md:pl-40 lg:pl-48">
                <div class="flex flex-wrap items-center gap-4">
                    <h1 id="member-title" class="font-title text-4xl font-black uppercase text-orange sm:text-5xl md:text-6xl">
                        <?php echo esc_html($member_title); ?>
                    </h1>
                </div>

                <div class="mt-6 max-w-2xl">
                    <p class="text-lg leading-relaxed md:text-xl"><?php echo esc_html($member_text); ?></p>
                    <a href="<?php echo esc_url($member_url); ?>"
                        class="btn-press mt-6 inline-flex rounded-full bg-yellow px-8 py-3 text-lg font-bold text-white no-underline">
                        <?php echo esc_html($member_button); ?>
                    </a>
                </div>
            </div>

            <div class="relative z-20 mx-auto flex w-full max-w-[15rem] items-center justify-center sm:max-w-[17rem] md:ml-0 md:max-w-none md:-translate-x-4 md:translate-y-4">
                <button type="button" data-soutenir-mascot
                    class="relative z-20 w-full cursor-pointer border-0 bg-transparent p-0 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple"
                    aria-label="Faire sauter la mascotte">
                    <img src="<?php echo esc_url($asset . 'disque.png'); ?>" alt="" aria-hidden="true"
                        class="block w-full object-contain" loading="lazy">
                </button>
            </div>
        </div>
    </section>

    <section class="donation-scene relative isolate left-1/2 z-10 w-screen -translate-x-1/2 overflow-hidden bg-orange px-5 py-16 text-black sm:px-8 md:py-20" aria-labelledby="donate-title">
        <svg class="pointer-events-none absolute inset-x-0 top-0 h-9 w-full text-violet sm:h-12" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 0 H1440 V45 Q1380 100 1320 45 T1200 45 T1080 45 T960 45 T840 45 T720 45 T600 45 T480 45 T360 45 T240 45 T120 45 T0 45 Z" />
        </svg>
        <div class="donation-spark donation-spark-one" aria-hidden="true"></div>
        <div class="donation-spark donation-spark-two" aria-hidden="true"></div>
        <img src="<?php echo esc_url($asset . 'Star3.png'); ?>" alt="" aria-hidden="true"
            class="donation-float pointer-events-none absolute left-[4%] top-20 z-0 w-16 sm:left-[7%] sm:w-20">
        <img src="<?php echo esc_url($asset . 'Star.png'); ?>" alt="" aria-hidden="true"
            class="donation-float donation-float-delayed pointer-events-none absolute right-[4%] top-24 z-0 w-10 sm:right-[8%] sm:w-14">

        <div class="relative z-10 mx-auto grid w-full max-w-7xl items-center gap-8 pt-5 md:min-h-[42rem] md:grid-cols-[minmax(0,1fr)_minmax(20rem,0.92fr)] md:gap-10 lg:gap-16">
            <div class="relative flex flex-col items-start md:min-h-[42rem]">
                <span class="inline-flex rounded-full bg-yellow px-4 py-1.5 text-xs font-black uppercase text-white shadow-sm sm:text-sm">
                    <?php echo esc_html($donate_badge); ?>
                </span>
                <h2 id="donate-title" class="relative z-10 mt-4 max-w-xl font-title text-4xl font-black uppercase leading-none text-white drop-shadow-sm sm:text-5xl md:text-6xl lg:text-7xl">
                    <?php echo esc_html($donate_title); ?>
                </h2>
                <p class="relative z-10 mt-4 max-w-lg text-base font-semibold leading-relaxed text-black/80 sm:text-lg md:pb-80">
                    <?php echo esc_html($donate_text); ?>
                </p>
                <img src="<?php echo esc_url($asset . 'noir_et_blanc.png'); ?>" alt="" aria-hidden="true"
                    class="pointer-events-none relative mt-6 block h-auto w-full max-w-[34rem] self-center md:absolute md:bottom-0 md:left-0 md:z-0 md:mt-0 md:w-[min(58vw,46rem)] md:max-w-none md:self-start">
            </div>

            <div class="relative z-10 mx-auto w-full max-w-xl">
                <img src="<?php echo esc_url($asset . 'phone1.png'); ?>" alt="" aria-hidden="true"
                    class="pointer-events-none absolute -right-2 -top-7 z-20 w-16 -rotate-6 sm:-right-4 sm:w-20">
                <div class="btn-press relative rounded-[1.75rem] bg-white/95 p-5 sm:rounded-[2rem] sm:p-7 md:p-8">
                    <?php if ($donate_form_shortcode !== '') : ?>
                        <div class="mb-5">
                            <span class="inline-flex rounded-full bg-yellow px-6 py-2 text-sm font-black uppercase text-white shadow-sm sm:px-7 sm:py-2.5 sm:text-base">
                                <?php echo esc_html($donate_badge); ?>
                            </span>
                            <h3 class="mt-4 font-title text-2xl font-black uppercase sm:text-3xl"><?php echo esc_html($donate_title); ?></h3>
                            <p class="mt-2 text-sm font-semibold leading-relaxed text-black/70 sm:text-base"><?php echo esc_html($donate_text); ?></p>
                        </div>
                        <div class="donation-form-content">
                            <?php echo do_shortcode($donate_form_shortcode); ?>
                        </div>
                    <?php else : ?>
                        <span class="inline-flex rounded-full bg-yellow px-4 py-1 text-xs font-black uppercase text-white">
                            Formulaire de don
                        </span>
                        <h3 class="mt-4 font-title text-2xl font-black uppercase sm:text-3xl">Le paiement par carte s'en vient !</h3>
                        <p class="mt-3 text-sm font-semibold leading-relaxed text-black/70 sm:text-base">
                            Le formulaire de don apparaîtra ici dès qu'un plugin de paiement sécurisé sera choisi et connecté.
                        </p>
                        <div class="mt-6 flex items-center gap-3 rounded-2xl bg-beige p-4 text-sm font-bold text-black/75">
                            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-violet text-xl text-white" aria-hidden="true">♡</span>
                            <span>Vos informations de paiement seront traitées de façon sécurisée par le prestataire choisi.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <svg class="pointer-events-none absolute inset-x-0 bottom-0 z-20 h-10 w-full text-white sm:h-14" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 55 Q60 0 120 55 T240 55 T360 55 T480 55 T600 55 T720 55 T840 55 T960 55 T1080 55 T1200 55 T1320 55 T1440 55 V100 H0 Z" />
        </svg>
    </section>

<?php endwhile; ?>
<?php get_footer(); ?>
