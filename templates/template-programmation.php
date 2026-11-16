<?php
$jour = get_field('date');
$heure_debut = get_field('time_start');
$heure_fin = get_field('time_end'); 
$titre = get_field('title');
$description = get_field('description');
$image = get_field('image');  // je récupère tout les champs SCF pour l'utiliser dans le code
 
?>
 
<div> 
    <?php echo $wrapper; ?>> 

    <?php if ($image) : ?>  <!--  appelle l'image pour l'afficher, et choisi la taille  -->
        <div class="card__image"> 
            <?php echo wp_get_attachment_image($image['ID'], 'medium_large'); ?> 
        </div>
    <?php endif; ?>

<div class="card__body bg-black block max-w-sm p-6 border border-default rounded-base shadow-xs">  <!--    appelle le corps de la carte avec l'horaire défini dans le SCF -->
    <p class="card__horaire">
      <?php echo esc_html($date); ?>
      <?php if ($time_start) : ?>
        · <?php echo esc_html($time_start); ?>
        <?php if ($time_end) : ?> – <?php echo esc_html($time_end); ?><?php endif; ?>
      <?php endif; ?>
    </p>

    <?php if ($title) : ?> <!--  appelle le titre -->
      <h3 class="card__titre mb-3 text-2xl font-semibold tracking-tight text-heading leading-8 color-white"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <?php if ($description) : ?> <!--  appelle la description -->
      <div class="card__description text-body mb-6 color-white"><?php echo wp_kses_post($description); ?>
    </div> <!-- supprime les scripts dans la description -->
    <?php endif; ?>

    <div>
    <svg class="w-4 h-4 ms-1.5 rlt:rotate-1800 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="color-orange" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 8l4 4 4-4"/> </svg>  </div>
    
  </div>
</div>

