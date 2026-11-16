<?php
function efc_register_programme()
{
    $custom_post_name = "programme";

    $labels = [
        "name" => _x("Programmes", $custom_post_name),
        "singular_name" => _x("Programme", $custom_post_name),
        "add_new" => _x("Ajouter un programme", $custom_post_name),
        "add_new_item" => _x("Ajouter un nouveau programme", $custom_post_name),
        "edit_item" => _x("Modifier le programme", $custom_post_name),
        "new_item" => _x("Nouveau programme", $custom_post_name),
        "view_item" => _x("Afficher le programme", $custom_post_name),
        "view_items" => _x("Afficher les programmes", $custom_post_name),
        "search_items" => _x("Rechercher dans les programmes", $custom_post_name),
        "not_found" => _x("Programme introuvable", $custom_post_name),
        "not_found_in_trash" => _x("Programme introuvable dans la corbeille", $custom_post_name),
        "parent_item_colon" => _x("Programme parent", $custom_post_name),
        "all_items" => _x("Tous les programmes", $custom_post_name),
        "archives" => _x("Archives des programmes", $custom_post_name),
        "attributes" => _x("Attributs du programme", $custom_post_name),
        "insert_into_item" => _x("Insérer dans le programme", $custom_post_name),
        "uploaded_to_this_item" => _x("Téléverser dans le programme", $custom_post_name),
        "featured_image" => _x("Image du programme", $custom_post_name),
        "set_featured_image" => _x("Attribuer l'image du programme", $custom_post_name),
        "remove_featured_image" => _x("Retirer l'image du programme", $custom_post_name),
        "use_featured_image" => _x("Utiliser l'image du programme", $custom_post_name),
        "menu_name" => _x("Programmation", $custom_post_name),
        "filter_items_list" => _x("Filtrer la liste des programmes", $custom_post_name),
        "filter_by_date" => _x("Filtrer par date", $custom_post_name),
        "items_list_navigation" => _x("Navigation de la liste des programmes", $custom_post_name),
        "items_list" => _x("Liste des programmes", $custom_post_name),
        "item_published" => _x("Programme publié", $custom_post_name),
        "item_published_privately" => _x("Programme publié en privé", $custom_post_name),
        "item_reverted_to_draft" => _x("Programme reconverti en brouillon", $custom_post_name),
        "item_scheduled" => _x("Programme planifié", $custom_post_name),
        "item_link" => _x("Lien du programme", $custom_post_name),
        "item_link_description" => _x("Lien vers la description du programme", $custom_post_name),
    ];

    $args = [
        "label" => "Programmes",                                // Le nom du post-type au pluriel
        "labels" => $labels,                                    // Tous les libellés pour l'interface
        "description" => "Programmes de la station de radio",   // Description du post type
        "public" => true,                                       // Accessible autant dans le backend que le frontend
        "hierarchical" => false,                                // Est-ce que c'est un post avec une hiérarchie parente?
        "has_archive" => false,                                 // Est-ce que vous avez besoin d'une page archive pour ce post?
        "exclude_from_search" => false,                         // Exclure de la recherche
        "show_ui" => true,                                      // Afficher l'admin pour ce post
        "show_in_menu" => true,                                 // Afficher dans le menu de l'admin
        "show_in_nav_menus" => true,                            // Peut-il être inclut dans l'interface Apparence -> Menus?
        "show_in_admin_bar" => true,                            // Afficher dans la barre d'admin du haut
        "show_in_rest" => true,                                 // Active la possibilité de récupérer le post par REST API
        "menu_position" => 9,                                   // Emplacement dans le menu de Wordpress
        "menu_icon" => "dashicons-microphone",
        "supports" => ["title"],                                // Quel theme_support voulez vous autoriser dans l'interface admin .. ici on aura juste le input de titre sans zone de contenu
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_concours()
{
    $custom_post_name = "concours";

    $labels = [
        "name" => _x("Concours", $custom_post_name),
        "singular_name" => _x("Concours", $custom_post_name),
        "add_new" => _x("Ajouter un concours", $custom_post_name),
        "add_new_item" => _x("Ajouter un nouveau concours", $custom_post_name),
        "edit_item" => _x("Modifier le concours", $custom_post_name),
        "new_item" => _x("Nouveau concours", $custom_post_name),
        "view_item" => _x("Afficher le concours", $custom_post_name),
        "view_items" => _x("Afficher les concours", $custom_post_name),
        "search_items" => _x("Rechercher dans les concours", $custom_post_name),
        "not_found" => _x("Concours introuvable", $custom_post_name),
        "not_found_in_trash" => _x("Concours introuvable dans la corbeille", $custom_post_name),
        "parent_item_colon" => _x("Concours parent", $custom_post_name),
        "all_items" => _x("Tous les concours", $custom_post_name),
        "archives" => _x("Archives des concours", $custom_post_name),
        "attributes" => _x("Attributs du concours", $custom_post_name),
        "insert_into_item" => _x("Insérer dans le concours", $custom_post_name),
        "uploaded_to_this_item" => _x("Téléverser dans le concours", $custom_post_name),
        "featured_image" => _x("Photo du concours", $custom_post_name),
        "set_featured_image" => _x("Attribuer la photo du concours", $custom_post_name),
        "remove_featured_image" => _x("Retirer la photo du concours", $custom_post_name),
        "use_featured_image" => _x("Utiliser comme photo du concours", $custom_post_name),
        "menu_name" => _x("Concours", $custom_post_name),
        "filter_items_list" => _x("Filtrer la liste des concours", $custom_post_name),
        "filter_by_date" => _x("Filtrer par date", $custom_post_name),
        "items_list_navigation" => _x("Navigation de la liste des concours", $custom_post_name),
        "items_list" => _x("Liste des concours", $custom_post_name),
        "item_published" => _x("Concours publié", $custom_post_name),
        "item_published_privately" => _x("Concours publié en privé", $custom_post_name),
        "item_reverted_to_draft" => _x("Concours reconverti en brouillon", $custom_post_name),
        "item_scheduled" => _x("Concours planifié", $custom_post_name),
        "item_link" => _x("Lien du concours", $custom_post_name),
        "item_link_description" => _x("Lien vers la description du concours", $custom_post_name),
    ];

    $args = [
        "label" => "Concours",
        "labels" => $labels,
        "description" => "Gestion des concours de la station de radio",
        "public" => true,
        "hierarchical" => false,
        "has_archive" => false,
        "exclude_from_search" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "show_in_rest" => true,
        "menu_position" => 8,
        "menu_icon" => "dashicons-awards",
        "supports" => ["title", "editor", "thumbnail"],
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_equipe()
{
    $custom_post_name = "equipe";

    $labels = [
        "name" => _x("Membres de l'équipe", $custom_post_name),
        "singular_name" => _x("Membre de l'équipe", $custom_post_name),
        "add_new" => _x("Ajouter un membre", $custom_post_name),
        "add_new_item" => _x("Ajouter un nouveau membre", $custom_post_name),
        "edit_item" => _x("Modifier le membre", $custom_post_name),
        "new_item" => _x("Nouveau membre", $custom_post_name),
        "view_item" => _x("Afficher le membre", $custom_post_name),
        "view_items" => _x("Afficher les membres", $custom_post_name),
        "search_items" => _x("Rechercher dans les membres", $custom_post_name),
        "not_found" => _x("Membre introuvable", $custom_post_name),
        "not_found_in_trash" => _x("Membre introuvable dans la corbeille", $custom_post_name),
        "parent_item_colon" => _x("Membre parent", $custom_post_name),
        "all_items" => _x("Tous les membres", $custom_post_name),
        "archives" => _x("Archives des membres", $custom_post_name),
        "attributes" => _x("Attributs du membre", $custom_post_name),
        "insert_into_item" => _x("Insérer dans le membre", $custom_post_name),
        "uploaded_to_this_item" => _x("Téléverser dans le membre", $custom_post_name),
        "featured_image" => _x("Photo du membre", $custom_post_name),
        "set_featured_image" => _x("Attribuer la photo du membre", $custom_post_name),
        "remove_featured_image" => _x("Retirer la photo du membre", $custom_post_name),
        "use_featured_image" => _x("Utiliser comme photo du membre", $custom_post_name),
        "menu_name" => _x("Équipe", $custom_post_name),
        "filter_items_list" => _x("Filtrer la liste des membres", $custom_post_name),
        "filter_by_date" => _x("Filtrer par date", $custom_post_name),
        "items_list_navigation" => _x("Navigation de la liste des membres", $custom_post_name),
        "items_list" => _x("Liste des membres", $custom_post_name),
        "item_published" => _x("Membre publié", $custom_post_name),
        "item_published_privately" => _x("Membre publié en privé", $custom_post_name),
        "item_reverted_to_draft" => _x("Membre reconverti en brouillon", $custom_post_name),
        "item_scheduled" => _x("Membre planifié", $custom_post_name),
        "item_link" => _x("Lien du membre", $custom_post_name),
        "item_link_description" => _x("Lien vers la description du membre", $custom_post_name),
    ];

    $args = [
        "label" => "Équipe",
        "labels" => $labels,
        "description" => "Employés et membres du conseil d'administration",
        "public" => false,                  // Pas de page individuelle, listé sur « Notre équipe »
        "publicly_queryable" => false,
        "hierarchical" => false,
        "has_archive" => false,
        "exclude_from_search" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => false,
        "show_in_admin_bar" => true,
        "show_in_rest" => true,
        "rewrite" => false,
        "menu_position" => 11,
        "menu_icon" => "dashicons-groups",
        "supports" => ["title"],
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_animateur()
{
    $custom_post_name = "animateur";

    $labels = [
        "name" => _x("Animateurs", $custom_post_name),
        "singular_name" => _x("Animateur", $custom_post_name),
        "add_new" => _x("Ajouter un animateur", $custom_post_name),
        "add_new_item" => _x("Ajouter un nouvel animateur", $custom_post_name),
        "edit_item" => _x("Modifier l'animateur", $custom_post_name),
        "new_item" => _x("Nouvel animateur", $custom_post_name),
        "view_item" => _x("Afficher l'animateur", $custom_post_name),
        "view_items" => _x("Afficher les animateurs", $custom_post_name),
        "search_items" => _x("Rechercher dans les animateurs", $custom_post_name),
        "not_found" => _x("Animateur introuvable", $custom_post_name),
        "not_found_in_trash" => _x("Animateur introuvable dans la corbeille", $custom_post_name),
        "parent_item_colon" => _x("Animateur parent", $custom_post_name),
        "all_items" => _x("Tous les animateurs", $custom_post_name),
        "archives" => _x("Archives des animateurs", $custom_post_name),
        "attributes" => _x("Attributs de l'animateur", $custom_post_name),
        "insert_into_item" => _x("Insérer dans l'animateur", $custom_post_name),
        "uploaded_to_this_item" => _x("Téléverser dans l'animateur", $custom_post_name),
        "featured_image" => _x("Photo de l'animateur", $custom_post_name),
        "set_featured_image" => _x("Attribuer la photo de l'animateur", $custom_post_name),
        "remove_featured_image" => _x("Retirer la photo de l'animateur", $custom_post_name),
        "use_featured_image" => _x("Utiliser comme photo de l'animateur", $custom_post_name),
        "menu_name" => _x("Animateurs", $custom_post_name),
        "filter_items_list" => _x("Filtrer la liste des animateurs", $custom_post_name),
        "filter_by_date" => _x("Filtrer par date", $custom_post_name),
        "items_list_navigation" => _x("Navigation de la liste des animateurs", $custom_post_name),
        "items_list" => _x("Liste des animateurs", $custom_post_name),
        "item_published" => _x("Animateur publié", $custom_post_name),
        "item_published_privately" => _x("Animateur publié en privé", $custom_post_name),
        "item_reverted_to_draft" => _x("Animateur reconverti en brouillon", $custom_post_name),
        "item_scheduled" => _x("Animateur planifié", $custom_post_name),
        "item_link" => _x("Lien de l'animateur", $custom_post_name),
        "item_link_description" => _x("Lien vers la description de l'animateur", $custom_post_name),
    ];

    $args = [
        "label" => "Animateurs",
        "labels" => $labels,
        "description" => "Animateurs et animatrices des émissions",
        "public" => false,                  // Pas de page individuelle, affiché dans les programmes et « Notre équipe »
        "publicly_queryable" => false,
        "hierarchical" => false,
        "has_archive" => false,
        "exclude_from_search" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => false,
        "show_in_admin_bar" => true,
        "show_in_rest" => true,
        "rewrite" => false,
        "menu_position" => 12,
        "menu_icon" => "dashicons-megaphone",
        "supports" => ["title"],
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_partenaire()
{
    $custom_post_name = "partenaire";

    $labels = [
        "name" => _x("Partenaires", $custom_post_name),
        "singular_name" => _x("Partenaire", $custom_post_name),
        "add_new" => _x("Ajouter un partenaire", $custom_post_name),
        "add_new_item" => _x("Ajouter un nouveau partenaire", $custom_post_name),
        "edit_item" => _x("Modifier le partenaire", $custom_post_name),
        "new_item" => _x("Nouveau partenaire", $custom_post_name),
        "view_item" => _x("Afficher le partenaire", $custom_post_name),
        "view_items" => _x("Afficher les partenaires", $custom_post_name),
        "search_items" => _x("Rechercher dans les partenaires", $custom_post_name),
        "not_found" => _x("Partenaire introuvable", $custom_post_name),
        "not_found_in_trash" => _x("Partenaire introuvable dans la corbeille", $custom_post_name),
        "parent_item_colon" => _x("Partenaire parent", $custom_post_name),
        "all_items" => _x("Tous les partenaires", $custom_post_name),
        "archives" => _x("Archives des partenaires", $custom_post_name),
        "attributes" => _x("Attributs du partenaire", $custom_post_name),
        "insert_into_item" => _x("Insérer dans le partenaire", $custom_post_name),
        "uploaded_to_this_item" => _x("Téléverser dans le partenaire", $custom_post_name),
        "featured_image" => _x("Logo du partenaire", $custom_post_name),
        "set_featured_image" => _x("Attribuer le logo du partenaire", $custom_post_name),
        "remove_featured_image" => _x("Retirer le logo du partenaire", $custom_post_name),
        "use_featured_image" => _x("Utiliser comme logo du partenaire", $custom_post_name),
        "menu_name" => _x("Partenaires", $custom_post_name),
        "filter_items_list" => _x("Filtrer la liste des partenaires", $custom_post_name),
        "filter_by_date" => _x("Filtrer par date", $custom_post_name),
        "items_list_navigation" => _x("Navigation de la liste des partenaires", $custom_post_name),
        "items_list" => _x("Liste des partenaires", $custom_post_name),
        "item_published" => _x("Partenaire publié", $custom_post_name),
        "item_published_privately" => _x("Partenaire publié en privé", $custom_post_name),
        "item_reverted_to_draft" => _x("Partenaire reconverti en brouillon", $custom_post_name),
        "item_scheduled" => _x("Partenaire planifié", $custom_post_name),
        "item_link" => _x("Lien du partenaire", $custom_post_name),
        "item_link_description" => _x("Lien vers la description du partenaire", $custom_post_name),
    ];

    $args = [
        "label" => "Partenaires",
        "labels" => $labels,
        "description" => "Commerces et organismes partenaires de la station",
        "public" => false,                  // Pas de page individuelle, affiché en bandeau de logos
        "publicly_queryable" => false,
        "hierarchical" => false,
        "has_archive" => false,
        "exclude_from_search" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => false,
        "show_in_admin_bar" => true,
        "show_in_rest" => true,
        "rewrite" => false,
        "menu_position" => 13,
        "menu_icon" => "dashicons-heart",
        "supports" => ["title"],
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_vitrine()
{
    $custom_post_name = "vitrine";

    $labels = [
        "name" => _x("Éléments de vitrine", $custom_post_name),
        "singular_name" => _x("Élément de vitrine", $custom_post_name),
        "add_new" => _x("Ajouter un élément", $custom_post_name),
        "add_new_item" => _x("Ajouter un nouvel élément", $custom_post_name),
        "edit_item" => _x("Modifier l'élément", $custom_post_name),
        "new_item" => _x("Nouvel élément", $custom_post_name),
        "view_item" => _x("Afficher l'élément", $custom_post_name),
        "view_items" => _x("Afficher les éléments", $custom_post_name),
        "search_items" => _x("Rechercher dans la vitrine", $custom_post_name),
        "not_found" => _x("Élément introuvable", $custom_post_name),
        "not_found_in_trash" => _x("Élément introuvable dans la corbeille", $custom_post_name),
        "parent_item_colon" => _x("Élément parent", $custom_post_name),
        "all_items" => _x("Tous les éléments", $custom_post_name),
        "archives" => _x("Archives de la vitrine", $custom_post_name),
        "attributes" => _x("Attributs de l'élément", $custom_post_name),
        "insert_into_item" => _x("Insérer dans l'élément", $custom_post_name),
        "uploaded_to_this_item" => _x("Téléverser dans l'élément", $custom_post_name),
        "featured_image" => _x("Image de l'élément", $custom_post_name),
        "set_featured_image" => _x("Attribuer l'image de l'élément", $custom_post_name),
        "remove_featured_image" => _x("Retirer l'image de l'élément", $custom_post_name),
        "use_featured_image" => _x("Utiliser comme image de l'élément", $custom_post_name),
        "menu_name" => _x("Vitrine locale", $custom_post_name),
        "filter_items_list" => _x("Filtrer la liste des éléments", $custom_post_name),
        "filter_by_date" => _x("Filtrer par date", $custom_post_name),
        "items_list_navigation" => _x("Navigation de la liste des éléments", $custom_post_name),
        "items_list" => _x("Liste des éléments", $custom_post_name),
        "item_published" => _x("Élément publié", $custom_post_name),
        "item_published_privately" => _x("Élément publié en privé", $custom_post_name),
        "item_reverted_to_draft" => _x("Élément reconverti en brouillon", $custom_post_name),
        "item_scheduled" => _x("Élément planifié", $custom_post_name),
        "item_link" => _x("Lien de l'élément", $custom_post_name),
        "item_link_description" => _x("Lien vers la description de l'élément", $custom_post_name),
    ];

    $args = [
        "label" => "Vitrine locale",
        "labels" => $labels,
        "description" => "Commerces, avis de décès, anniversaires, emplois et annonces de la région",
        "public" => true,                   // Page individuelle (single-vitrine.php) pour le « Voir plus »
        "hierarchical" => false,
        "has_archive" => false,
        "exclude_from_search" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "show_in_rest" => true,
        "rewrite" => ["slug" => "vitrine"],
        "menu_position" => 14,
        "menu_icon" => "dashicons-store",
        "supports" => ["title"],
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_all_post_types()
{
    efc_register_programme();
    efc_register_concours();
    efc_register_equipe();
    efc_register_animateur();
    efc_register_partenaire();
    efc_register_vitrine();
}
add_action("init", "efc_register_all_post_types");
