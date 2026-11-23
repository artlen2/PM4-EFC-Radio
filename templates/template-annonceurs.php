<?php
/* Template Name: Annonceurs */
get_header(); ?>
<main class="container mx-auto px-4 py-12 bg-orange">
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
?>
 
    <div>
 
        <section class="max-w-10xl mx-auto px-4 py-10  ">
 
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-20 text-right rotate-[3deg] ">
                <?php echo esc_html($titre ?: get_the_title()); ?>
            </h1>
 
            <div class="grid gap-6 md:grid-cols-2 ">
 
           
                <!-- logos -->
                <?php if ($logo_image_url || $logo_url) : ?>
                 <section class="bg-beige rounded-[24px] border-t-2 border-x-2 border-b-8 border-black p-6 flex flex-col gap-4">
                 <h2 class="text-2xl font-bold text-black">Logos</h2>
 
                 <?php if ($logo_image_url) : ?>
                 
                     <img class="w-full h-48 object-contain" src="<?php echo esc_url($logo_image_url); ?>" alt="Logo de la radio">
                 <?php endif; ?>
 
                 <?php if ($logo_url ) : ?>
                    <p class="text-black text-2xl ">Téléchargez les polices de caractères utilisée par la radio.</p>
                     <a href="<?php echo esc_url($logo_url); ?>" download
                       class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] btn-press">
                       Télécharger les logos
                     </a>
                <?php endif; ?>
                  </section>
                <?php endif; ?>
 
 
                <!-- typos -->
 
                <?php if ($typo_image_url || $typo_url)   : ?>
                    <section class="bg-beige  rounded-[24px] border-t-2 border-x-2 border-b-8 border-black p-6 flex flex-col gap-4">
                       
 
 
 
                        <h2 class="text-2xl font-bold text-black">Typographie</h2>
 
                    <?php if ($typo_image_url) : ?>
                 
                         <img class="w-full h-20 my-14 object-contain " src="<?php echo esc_url($typo_image_url); ?>" alt="Logo de la radio">
                    <?php endif; ?>
 
                    <?php if ($typo_url) :?>
                        <p class="text-black text-2xl  ">Téléchargez les polices de caractères utilisée par la radio.</p>
                        <a href="<?php echo esc_url($typo_url); ?>" download
                            class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] btn-press">
                            Télécharger les typographies
                        </a>
                    <?php endif; ?>
                    </section>
                <?php endif; ?>
 
            </div>
        </section>
    </div>
 
<?php endwhile; ?>
 
</main>
 
<?php get_footer(); ?>