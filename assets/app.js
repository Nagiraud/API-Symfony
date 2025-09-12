import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Script pour gérer le menu hamburger
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const headerCategory = document.querySelector('#header-category');
    const headerConnection = document.querySelector('#header_connection');

    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        headerCategory.classList.toggle('active');

        // Afficher/masquer aussi la section de connexion si elle existe
        if (headerConnection) {
            headerConnection.classList.toggle('active');
        }
    });
});
