

<?php
get_header();

$jours = [
    'lundi' => [
        'label' => 'Lundi',
        'emissions' => [
            [
                'titre' => 'Première heure',
                'heure' => '15:30',
                'description' => 'Pour bien amorcer la fin de journée : l\'actualité de la région, la météo, l\'état des routes et de la bonne musique. L\'heure idéale pour savoir ce qui se passe en Matanie.',
            ],
            [
                'titre' => 'Tout terrain',
                'heure' => '16:00',
                'description' => 'Une émission qui touche à tout : culture, sport, plein air et vie communautaire. Des invités d\'ici, des découvertes musicales et des sujets qui font jaser, toujours avec le sourire.',
            ],
            [
                'titre' => 'Pas comme d\'habitude',
                'heure' => '16:30',
                'description' => 'Une heure qui sort des sentiers battus : musique émergente, chroniques décalées et surprises en direct. Pour finir la journée sur une note différente, en bonne compagnie.',
            ],
        ],
    ],
    'mardi' => ['label' => 'Mardi', 'emissions' => []],
    'mercredi' => ['label' => 'Mercredi', 'emissions' => []],
    'jeudi' => ['label' => 'Jeudi', 'emissions' => []],
    'vendredi' => ['label' => 'Vendredi', 'emissions' => []],
    'samedi' => ['label' => 'Samedi', 'emissions' => []],
    'dimanche' => ['label' => 'Dimanche', 'emissions' => []],
];

while (have_posts()) : the_post(); ?>
<div class="prog-page">
    <section class  ="prog-section">
        <h1 class="prog-title"><?php the_title(); ?></h1>
        <div class="prog-content">
            <?php the_content(); ?>
        </div>
    </section>
    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

