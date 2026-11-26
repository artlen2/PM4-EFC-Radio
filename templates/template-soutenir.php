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

if (!function_exists('efc_soutenir_image')) {
    function efc_soutenir_image($name, $fallback)
    {
        $value = efc_soutenir_field($name, $fallback);
        if (is_array($value)) {
            return $value['url'] ?? $fallback;
        }
        if (is_numeric($value)) {
            return wp_get_attachment_image_url((int) $value, 'large') ?: $fallback;
        }
        return $value ?: $fallback;
    }
}

// custom posts
while (have_posts()) : the_post();
    $asset = get_template_directory_uri() . '/assets/objets/';
    $member_title = efc_soutenir_field('member_title', 'DEVENIR MEMBRE');
    $member_text = efc_soutenir_field(
        'member_text',
        'Tentez votre chance au Radio Bingo en direct sur nos ondes et participez aux concours offerts par nos partenaires ! De beaux prix vous attendent, et chaque participation aide à faire vivre votre radio communautaire.'
    );
    $member_button = efc_soutenir_field('member_button', 'S’inscrire');
    $member_url = efc_soutenir_field('member_url', '#');
    $member_image = efc_soutenir_image('member_image', $asset . 'disque.png');
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

        <div class="container relative mx-auto grid min-h-[22rem] items-center gap-5 px-6 py-10 md:min-h-[25rem] md:grid-cols-[minmax(0,1fr)_18rem] md:gap-8 md:px-10 md:py-12">
            <img src="<?php echo esc_url($asset . 'micro.png'); ?>" alt="" aria-hidden="true"
                class="absolute left-0 top-6 z-10 w-16 md:-left-8 md:top-1/2 md:w-40 md:-translate-y-1/2 lg:-left-12 lg:w-48">

            <div class="relative z-10 pt-20 md:py-4 md:pl-32">
                <div class="flex flex-wrap items-center gap-4">
                    <h1 id="member-title" class="font-title text-4xl font-black uppercase text-orange md:text-5xl">
                        <?php echo esc_html($member_title); ?>
                    </h1>
                </div>

                <div class="mt-5 max-w-lg">
                    <p class="text-base leading-relaxed md:text-lg"><?php echo esc_html($member_text); ?></p>
                    <a href="<?php echo esc_url($member_url); ?>"
                        class="btn-press mt-5 inline-flex rounded-full bg-yellow px-7 py-2 font-bold text-white no-underline">
                        <?php echo esc_html($member_button); ?>
                    </a>
                </div>
            </div>

            <div class="relative mx-auto flex w-full max-w-[17rem] items-center justify-center md:ml-0 md:max-w-none md:-translate-x-6">
                <img src="<?php echo esc_url($asset . 'Star2.png'); ?>" alt="" aria-hidden="true"
                    class="absolute -right-2 -top-5 w-24 md:-right-1 md:-top-8 md:w-32">
                <button type="button" data-soutenir-mascot
                    class="relative z-10 w-full cursor-pointer border-0 bg-transparent p-0 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple"
                    aria-label="Faire sauter la mascotte">
                    <img src="<?php echo esc_url($member_image); ?>" alt="" aria-hidden="true"
                        class="block w-full object-contain" loading="lazy">
                </button>
            </div>
        </div>
    </section>

    <svg class="hero-wave -mb-px -mt-px block h-16 w-full bg-white text-violet md:h-24"
        viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor"
            d="M0 40 Q60 0 120 40 T240 40 T360 40 T480 40 T600 40 T720 40 T840 40 T960 40 T1080 40 T1200 40 T1320 40 T1440 40 V100 H0 Z" />
    </svg>
</main>

<?php endwhile; ?>
<?php get_footer(); ?>
