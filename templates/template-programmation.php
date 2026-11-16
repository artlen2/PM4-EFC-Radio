<?php
$jour = get_field('jour');
$heure_debut = get_field('heure_debut');
$heure_fin = get_field('heure_fin'); 
$titre = get_field('titre');
$description = get_field('description');
$image = get_field('image');  // je nomme tout les champs SCF pour l'utiliser dans le code

$wrapper = get_block_wrapper_attributes(['class' => 'class']); 
?>
<div> 
    <?php echo $wrapper; ?>> 
    <?php if ($image) : ?>
        <div class="card__image"> 
            <?php echo wp_get_attachment_image($image['ID'], 'medium_large'); ?> 
        </div>
    <?php endif; ?>

    