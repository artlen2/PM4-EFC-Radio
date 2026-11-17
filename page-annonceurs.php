<?php
/* Template Name: Annonceurs */
get_header();

// Retourne l'URL d'un champ fichier, peu importe son format de retour (tableau, ID ou URL)
function kit_media_url($champ) {
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
    $logo_url    = kit_media_url(get_field('logo'));
    $typo_url    = kit_media_url(get_field('typographie'));
?>

<div class="bg-orange">

  <section class="max-w-5xl mx-auto px-4 py-10  ">

    <h1 class="text-3xl md:text-5xl font-bold text-white mb-8 text-right rotate-[3deg] ">
        <?php echo esc_html($titre ?: get_the_title()); ?>
    </h1>

    <div class="grid gap-6 md:grid-cols-2 ">

        <?php if ($logo_url) : ?>
            <section class="bg-beige rounded-[24px] border-t-2 border-x-2 border-b-8 border-black p-6 flex flex-col gap-4">
                <h2 class="text-xl font-bold text-black">Logos</h2>
                <img class="w-full h-48 object-contain" src="<?php echo esc_url($logo_url); ?>" alt="Logo de la radio">
                <a href="<?php echo esc_url($logo_url); ?>" download
                   class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] border-t-2 border-x-2 border-b-8 border-black hover:scale-105 ease-in-out duration-300">
                    Télécharger le logo
                </a>
            </section>
        <?php endif; ?>

        <?php if ($typo_url) : ?>
            <section class="bg-beige rounded-[24px] border-t-2 border-x-2 border-b-8 border-black p-6 flex flex-col gap-4">
                <h2 class="text-xl font-bold text-black">Typographie</h2>
                <p class="text-black">Téléchargez les polices de caractères utilisée par la radio.</p>
                <a href="<?php echo esc_url($typo_url); ?>" download
                   class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] border-t-2 border-x-2 border-b-8 border-black hover:scale-105 ease-in-out duration-300">
                    Télécharger les typographies
                </a>
            </section>
        <?php endif; ?>

    </div>
   </section>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>