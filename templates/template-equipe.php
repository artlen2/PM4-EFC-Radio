<?php
/*
Template Name: À propos
*/
get_header();

// 1. Récupérer tous les membres de l'équipe publiés
$all_team_members = get_posts([
    'post_type'      => 'equipe',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

// 2. Classer les membres selon leur rôle
//    Résultat : ['administrateur' => [membre1, membre2], ...]
$members_grouped_by_role = [];

foreach ($all_team_members as $team_member) {
    $member_role_value = get_field('role', $team_member->ID);

    // Si le champ retourne un tableau (valeur + libellé), on garde la valeur
    if (is_array($member_role_value)) {
        $member_role_value = $member_role_value['value'];
    }

    // Si aucun rôle n'est choisi, on met le membre dans un groupe par défaut
    if (empty($member_role_value)) {
        $member_role_value = 'sans-role';
    }

    $members_grouped_by_role[$member_role_value][] = $team_member;
}

// 3. Récupérer les libellés (et l'ordre) des rôles depuis le champ Select
//    Exemple : ['admin' => 'Administrateur', 'employe' => 'Employé']
$role_labels = [];

if (!empty($all_team_members)) {
    $role_field_definition = get_field_object('role', $all_team_members[0]->ID);

    if ($role_field_definition && !empty($role_field_definition['choices'])) {
        $role_labels = $role_field_definition['choices'];
    }
}

// Si un membre a un rôle absent de la liste des choix, on l'affiche quand même
foreach (array_keys($members_grouped_by_role) as $existing_role_value) {
    if (!isset($role_labels[$existing_role_value])) {
        $role_labels[$existing_role_value] = ucfirst($existing_role_value);
    }
}

// ------------------------------------------------------------
// 4. Récupérer tous les partenaires publiés
$all_partners = get_posts([
    'post_type'      => 'partenaire',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
]);
?>

<main>

    <!-- En-tête : titre de la page + texte de la mission (écrit dans l'éditeur de la page) -->
    <section class="max-w-6xl mx-auto px-6 py-16 text-center">
        <h1 class="text-5xl font-black mb-4">
            <?php the_title(); ?>
        </h1>

        <?php if (get_the_content()) : ?>
            <div class="text-lg max-w-2xl mx-auto">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Équipe : une section par rôle -->
    <?php foreach ($role_labels as $role_value => $role_label) : ?>

        <?php
        // On saute les rôles qui n'ont aucun membre
        if (empty($members_grouped_by_role[$role_value])) {
            continue;
        }
        ?>

        <section class="max-w-6xl mx-auto px-6 pb-16">

            <h2 class="text-3xl font-black mb-8">
                <?php echo esc_html($role_label); ?>
            </h2>

            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 list-none p-0 m-0">

                <?php foreach ($members_grouped_by_role[$role_value] as $team_member) : ?>
                    <?php
                    // Lire les champs SCF du membre
                    $member_full_name   = get_field('prenom_nom', $team_member->ID);
                    $member_photo       = get_field('photo', $team_member->ID);
                    $member_description = get_field('description', $team_member->ID);

                    // Si le champ nom est vide, on utilise le titre de l'article
                    if (empty($member_full_name)) {
                        $member_full_name = $team_member->post_title;
                    }

                    // La photo peut être un tableau (Image Array) ou une URL
                    $member_photo_url = '';
                    if ($member_photo) {
                        $member_photo_url = is_array($member_photo) ? $member_photo['url'] : $member_photo;
                    }
                    ?>

                    <li>
                        <article class="flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-black/10">

                            <?php if ($member_photo_url) : ?>
                                <img src="<?php echo esc_url($member_photo_url); ?>"
                                    alt="<?php echo esc_attr($member_full_name); ?>"
                                    class="w-full aspect-square object-cover"
                                    loading="lazy">
                            <?php else : ?>
                                <!-- Pas de photo : on affiche la première lettre du nom -->
                                <div class="w-full aspect-square bg-black/5 flex items-center justify-center text-5xl font-black"
                                    aria-hidden="true">
                                    <?php echo esc_html(mb_substr($member_full_name, 0, 1)); ?>
                                </div>
                            <?php endif; ?>

                            <div class="flex flex-col gap-2 p-5">
                                <h3 class="text-lg font-bold">
                                    <?php echo esc_html($member_full_name); ?>
                                </h3>

                                <p class="text-xs font-semibold uppercase tracking-widest">
                                    <?php echo esc_html($role_label); ?>
                                </p>

                                <?php if ($member_description) : ?>
                                    <p class="text-sm leading-relaxed">
                                        <?php echo esc_html($member_description); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                        </article>
                    </li>

                <?php endforeach; ?>

            </ul>

        </section>

    <?php endforeach; ?>

    <!-- Message si aucun membre n'existe du tout -->
    <?php if (empty($all_team_members)) : ?>
        <p class="text-center py-16">Aucun membre à afficher pour le moment.</p>
    <?php endif; ?>

    <!-- Partenaires -->
    <?php if (!empty($all_partners)) : ?>
        <section class="max-w-6xl mx-auto px-6 pb-20">

            <h2 class="text-3xl font-black mb-8 text-center">Nos partenaires</h2>

            <ul class="flex flex-wrap items-center justify-center gap-x-10 gap-y-8 list-none p-0 m-0">

                <?php foreach ($all_partners as $partner) : ?>
                    <?php
                    // Lire les champs SCF du partenaire
                    $partner_logo = get_field('logo', $partner->ID);
                    $partner_link = get_field('link', $partner->ID);

                    // Le logo peut être un tableau (Image Array) ou une URL
                    $partner_logo_url = '';
                    if ($partner_logo) {
                        $partner_logo_url = is_array($partner_logo) ? $partner_logo['url'] : $partner_logo;
                    }
                    ?>

                    <li>
                        <!-- Si un lien existe, le logo devient cliquable -->
                        <?php if ($partner_link) : ?>
                            <a href="<?php echo esc_url($partner_link); ?>" target="_blank" rel="noopener noreferrer">
                            <?php endif; ?>

                            <?php if ($partner_logo_url) : ?>
                                <img src="<?php echo esc_url($partner_logo_url); ?>"
                                    alt="<?php echo esc_attr($partner->post_title); ?>"
                                    class="h-16 w-auto object-contain"
                                    loading="lazy">
                            <?php else : ?>
                                <span class="font-bold"><?php echo esc_html($partner->post_title); ?></span>
                            <?php endif; ?>

                            <?php if ($partner_link) : ?>
                            </a>
                        <?php endif; ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>