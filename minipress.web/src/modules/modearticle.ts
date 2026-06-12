'use strict';

export function afficherModeArticle(): void{
    // Cacher la nav et les sections inutiles
    document.getElementById('test-bar').style.display = 'none';
    document.getElementById('la_categorie').style.display = 'none';
    document.getElementById('les_articles_orderby').style.display = 'none';
    document.getElementById('les_articles_par_categorie').style.display = 'none';
    document.getElementById('les_articles_par_user').style.display = 'none';

    // Afficher le bouton retour
    document.getElementById('btn-retour').style.display = 'inline-block';
}

export function quitterModeArticle(): void {
    // Réafficher la nav et les sections
    document.getElementById('test-bar').style.display = '';
    document.getElementById('la_categorie').style.display = '';
    document.getElementById('les_articles_orderby').style.display = '';
    document.getElementById('les_articles_par_categorie').style.display = '';
    document.getElementById('les_articles_par_user').style.display = '';

    // Vider et cacher l'article
    document.getElementById('un_article').innerHTML = '';
    document.getElementById('btn-retour').style.display = 'none';
}