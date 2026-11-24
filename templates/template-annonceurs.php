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
 
        <section class="max-w-10xl mx-auto px-4 py-10 mb-32 ">
 
            <h1 class=" md:text-5xl font-bold text-white mb-20 text-right rotate-[3deg] ">
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
                    <p class="text-black text-2xl ">Téléchargez les logos utilisée par la radio.</p>
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
                        <p class="text-black text-2xl  ">Téléchargez les polices d'écritures utilisée par la radio.</p>
                        <a href="<?php echo esc_url($typo_url); ?>" download
                            class="bg-white text-black font-bold no-underline text-center p-4 rounded-[30px] btn-press">
                            Télécharger les typographies
                        </a>
                    <?php endif; ?>
                    </section>
                <?php endif; ?>

                  
 
            </div>

           
        </section>


        <!-- nous joindre -->

     <section class="bg-beige p-8 md:p-24 flex flex-col md:flex-row gap-12 md:gap-100 relative mb-32 md:scale-110 overflow-hidden md:overflow-visible">

    <div class="flex flex-col items-center md:block">
        <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/disco-ball.png"
             alt="" aria-hidden="true"
             class="w-16 self-end md:w-38 md:absolute md:top-0 md:right-20">

        <h1 class="text-4xl md:text-5xl text-violet rotate-[-3deg]">Nous joindre</h1>

        <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/phone2.png"
             alt="main qui tient un téléphone"
             class="rotate-[30deg] w-32 mt-6 md:mt-0 md:w-50 md:ml-30">
    </div>

    <div class="md:scale-110">

        <div class="bg-violet w-full md:w-110 p-8 md:p-12 pb-16 md:pb-24 md:pr-[35%] min-h-[18rem] [-webkit-mask-size:100%_100%] [mask-size:100%_100%] md:[-webkit-mask-size:100%_80%] md:[mask-size:100%_80%]"
             style="-webkit-mask-image: url('<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/Union.png');
                    mask-image: url('<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/objets/Union.png');
                    -webkit-mask-position: center; mask-position: center;
                    -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat;">

            <h2 class="text-2xl text-beige mb-8 mt-8 md:w-50">Équipe des ventes :</h2>

            <ul class="w-full md:w-100">
                <li class="mb-6 text-beige break-words">
                    COURRIEL :
                    <a class="hover:text-yellow ease-in-out duration-200"
                       href="mailto:radiolamatanie@gmail.com">radiolamatanie@gmail.com</a>
                </li>
                <li class="mb-6 text-beige">
                    TÉLÉPHONE :
                    <a class="hover:text-yellow ease-in-out duration-200"
                       href="tel:+15812327434">(581) 232-7434</a>
                </li>
            </ul>

        </div>
    </div>

</section>

        
    
 
<?php endwhile; ?>
 
</main>
 
<?php get_footer(); ?>