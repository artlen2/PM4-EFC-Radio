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
        "menu_icon" => "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktYmFuazIiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CiAgPHBhdGggZD0iTTguMjc3LjA4NGEuNS41IDAgMCAwLS41NTQgMGwtNy41IDVBLjUuNSAwIDAgMCAuNSA2aDEuODc1djdIMS41YS41LjUgMCAwIDAgMCAxaDEzYS41LjUgMCAxIDAgMC0xaC0uODc1VjZIMTUuNWEuNS41IDAgMCAwIC4yNzctLjkxNnpNMTIuMzc1IDZ2N2gtMS4yNVY2em0tMi41IDB2N2gtMS4yNVY2em0tMi41IDB2N2gtMS4yNVY2em0tMi41IDB2N2gtMS4yNVY2ek04IDRhMSAxIDAgMSAxIDAtMiAxIDEgMCAwIDEgMCAyTS41IDE1YS41LjUgMCAwIDAgMCAxaDE1YS41LjUgMCAxIDAgMC0xeiIvPgo8L3N2Zz4=",
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
        "menu_icon" => "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktY2FsZW5kYXIzIiB2aWV3Qm94PSIwIDAgMTYgMTYiPgogIDxwYXRoIGQ9Ik0xNCAwSDJhMiAyIDAgMCAwLTIgMnYxMmEyIDIgMCAwIDAgMiAyaDEyYTIgMiAwIDAgMCAyLTJWMmEyIDIgMCAwIDAtMi0yTTEgMy44NTdDMSAzLjM4NCAxLjQ0OCAzIDIgM2gxMmMuNTUyIDAgMSAuMzg0IDEgLjg1N3YxMC4yODZjMCAuNDczLS40NDguODU3LTEgLjg1N0gyYy0uNTUyIDAtMS0uMzg0LTEtLjg1N3oiLz4KICA8cGF0aCBkPSJNNi41IDdhMSAxIDAgMSAwIDAtMiAxIDEgMCAwIDAgMCAybTMgMGExIDEgMCAxIDAgMC0yIDEgMSAwIDAgMCAwIDJtMyAwYTEgMSAwIDEgMCAwLTIgMSAxIDAgMCAwIDAgMm0tOSAzYTEgMSAwIDEgMCAwLTIgMSAxIDAgMCAwIDAgMm0zIDBhMSAxIDAgMSAwIDAtMiAxIDEgMCAwIDAgMCAybTMgMGExIDEgMCAxIDAgMC0yIDEgMSAwIDAgMCAwIDJtMyAwYTEgMSAwIDEgMCAwLTIgMSAxIDAgMCAwIDAgMm0tOSAzYTEgMSAwIDEgMCAwLTIgMSAxIDAgMCAwIDAgMm0zIDBhMSAxIDAgMSAwIDAtMiAxIDEgMCAwIDAgMCAybTMgMGExIDEgMCAxIDAgMC0yIDEgMSAwIDAgMCAwIDIiLz4KPC9zdmc+",
        "supports" => ["title"],
    ];

    register_post_type($custom_post_name, $args);
}

function efc_register_all_post_types()
{
    efc_register_programme();
    efc_register_concours();
}
add_action("init", "efc_register_all_post_types");
