/**
 * Bouton en JS
 * Exemple de script JS lié à un template WP (*Voir functions.php)
 *
 * @summary Simple bouton cliquable
 * @author S.Charette
 *
 * Created at     : 2024-04-24 10:14:28 
 * Last modified  : 2024-11-02 09:27:47
 */

// S'assurer que les éléments du DOM chargés
window.addEventListener("DOMContentLoaded", (evt) => {

    // Récupérer les éléments nécessaires
    let button = document.querySelector("#sample-btn");
    let text = document.querySelector("#text-container");

    // Rendre le bouton cliquable
    button.addEventListener("click", () => {
        text.textContent = "Le bouton a été cliqué!";
    })



});