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
    $benefit_title = efc_soutenir_field('benefit_title', 'Quels avantages ?');
    $benefit_badge = efc_soutenir_field('benefit_badge', 'En cours');
    $benefit_text = efc_soutenir_field(
        'benefit_text',
        'Envoyez-nous votre plus belle photo du phare de Matane et courez la chance de gagner un souper pour deux, des billets pour le Festival de la crevette.'
    );
    $partner_title = efc_soutenir_field('partner_title', 'Devenir partenaire');
    $partner_text = efc_soutenir_field(
        'partner_text',
        'Associez votre entreprise à une radio bien ancrée dans sa communauté et faites connaître vos services en Matanie.'
    );
    $partner_button = efc_soutenir_field('partner_button', 'En savoir plus');
    $partner_url = efc_soutenir_field('partner_url', '#');
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

    <!-- la partie quels avantages -->

    <section class="relative left-1/2 z-10 mt-16 w-screen -translate-x-1/2 overflow-x-clip bg-violet px-5 pb-12 pt-16 text-white md:mt-24 md:pb-20 md:pt-24" aria-label="Devenir partenaire et avantages">
        <svg class="pointer-events-none absolute -top-14 left-0 z-20 h-16 w-full text-violet md:-top-20 md:h-24"
            viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor"
                d="M0 40 Q60 0 120 40 T240 40 T360 40 T480 40 T600 40 T720 40 T840 40 T960 40 T1080 40 T1200 40 T1320 40 T1440 40 V100 H0 Z" />
        </svg>
        <img src="<?php echo esc_url($asset . 'vecteur.png'); ?>" alt="" aria-hidden="true"
            class="pointer-events-none absolute left-0 top-1/2 z-0 w-full -translate-y-1/2 opacity-40">
        <img src="<?php echo esc_url($asset . 'Ellipse9.png'); ?>" alt="" aria-hidden="true"
            class="pointer-events-none absolute left-[28%] top-8 z-0 w-36 opacity-70 md:w-48">
        <img src="<?php echo esc_url($asset . 'Star5.png'); ?>" alt="" aria-hidden="true"
            class="pointer-events-none absolute bottom-6 right-4 z-0 w-[clamp(7rem,16vw,13rem)] opacity-60 md:bottom-8 md:right-[5%]">

        <div class="relative z-10 mx-auto grid w-full max-w-7xl items-center gap-12 sm:gap-16 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] lg:gap-20">
            <article class="btn-press relative z-10 w-full max-w-[34rem] cursor-pointer -rotate-3 rounded-3xl bg-white p-5 text-black sm:p-6 md:p-8">
                <span class="mb-4 inline-block -rotate-2 rounded-full bg-yellow px-4 py-1 text-xs font-black uppercase text-white">
                    <?php echo esc_html($benefit_badge); ?>
                </span>
                <h2 class="font-title text-2xl font-black uppercase md:text-3xl"><?php echo esc_html($benefit_title); ?></h2>
                <p class="mt-3 text-base leading-snug md:text-lg"><?php echo esc_html($benefit_text); ?></p>
            </article>

            <div class="relative mx-auto w-full max-w-[34rem] lg:-translate-y-4">
                <div class="soutenir-union relative z-10 flex min-h-[19rem] flex-col justify-center px-[12%] py-10 sm:px-[14%] md:min-h-[22rem] md:px-[15%] md:py-14">
                    <h2 class="max-w-[90%] font-title text-2xl font-black uppercase leading-tight text-orange sm:text-3xl md:text-4xl">
                        <?php echo esc_html($partner_title); ?>
                    </h2>
                    <p class="mt-3 max-w-[90%] text-sm font-bold leading-snug text-orange sm:text-base md:text-lg">
                        <?php echo esc_html($partner_text); ?>
                    </p>
                    <a href="<?php echo esc_url($partner_url); ?>"
                        class="btn-press mt-5 inline-flex w-fit rounded-full bg-yellow px-6 py-2.5 font-bold text-white no-underline">
                        <?php echo esc_html($partner_button); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php endwhile; ?>
<?php get_footer(); ?>
