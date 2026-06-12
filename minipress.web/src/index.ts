'use strict';

import { quitterModeArticle } from "./modules/modearticle";
import { loadAll } from "./modules/articleloader";
import { url, url_articles, url_categories } from "./modules/config";
import { displayArticle, displayArticleByCategorie, displayArticleByUser, displayArticleOrderby, displayCategories } from "./modules/ui";

let order = "date-desc";
const inputValue = (selector: string): number =>
    Number((document.querySelector(selector) as HTMLInputElement).value);

const articlesOrderby = () => {
    if(order != "date-desc") {
        order = "date-desc";
    }else{
        order = "date-asc";
    }
    const query = order ? `?sort=${order}` : "";
    loadAll(url_articles, query)
        .then((articles) => {
            console.log(articles);
            displayArticleOrderby(articles);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const inputText = (selector: string): string =>
    (document.querySelector(selector) as HTMLInputElement).value.trim().toLowerCase();

const articlesIncludeTitle = (order: string = "") => {
    const keyword = inputText('#input-keyword');
    const query = order ? `?order=${order}` : "";
    loadAll<any[]>(url_articles, query)
        .then((articles) => {
            const filtered = keyword
                ? articles.filter(a => a.titre.toLowerCase().includes(keyword))
                : articles;
            displayArticleOrderby(filtered);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const articlesIncludeResume = (order: string = "") => {
    const keyword = inputText('#input-keyword-resume');
    const query = order ? `?order=${order}` : "";
    loadAll<any[]>(url_articles, query)
        .then((articles) => {
            const filtered = keyword
                ? articles.filter(a =>
                a.titre.toLowerCase().includes(keyword) ||
                a.resume.toLowerCase().includes(keyword)
                ): articles;
            displayArticleOrderby(filtered);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const categories = () => {
    loadAll(url_categories)
        .then((categories) => {
            console.log(categories);
            displayCategories(categories);
        })
        .catch((error) => console.error("Erreur au chargement des catégories: ", error));
};

const articleByCategorie = (id_categorie: number) => {
    if (!id_categorie) return;
    loadAll<any>(url_categories, `/${id_categorie}/articles`)
        .then((data) => {
            console.log(data);
            displayArticleByCategorie(data);   // l'objet complet, pas data.articles
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const article = (id: number) => {
    if (!id) return;
    loadAll(url_articles, `/${id}`)
        .then((article) => {
            console.log(article);
            displayArticle(article);
        })
        .catch((error) => console.error("Erreur au chargement de l'article: ", error));
};

const articlesByUser = (id_user: number) => {
    console.log('articlesByUser appelé avec id =', id_user);
    if (!id_user) return;
    loadAll(url, `/auteurs/${id_user}/articles`)
        .then((articles) => {
            console.log(articles);
            displayArticleByUser(articles);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

document.addEventListener("click", (event) => {
    const cible = event.target as HTMLElement;

    const cat = cible.closest('.categorie') as HTMLElement | null;
    if (cat) {
        event.preventDefault();
        articleByCategorie(Number(cat.dataset.id));
        return;
    }

    const auteur = cible.closest('.auteur') as HTMLElement | null;
    if (auteur) {
        event.preventDefault();
        articlesByUser(Number(auteur.dataset.id));
        return;
    }

    const carte = cible.closest('.card-article') as HTMLElement | null;
    if (carte) {
        article(Number(carte.dataset.id));
        return;
    }

    // boutons de la barre de test
    if (cible.matches("#btn-retour")) { event.preventDefault(); quitterModeArticle(); }
    if (cible.matches("#btn-categories")) { event.preventDefault(); categories(); }
    if (cible.matches("#btn-articles-orderby")) { event.preventDefault(); articlesOrderby(); }
    if (cible.matches("#btn-articles-categorie")) { event.preventDefault(); articleByCategorie(inputValue('#input-categorie')); }
    if (cible.matches("#btn-article")) { event.preventDefault(); article(inputValue('#input-article')); }
    if (cible.matches("#btn-articles-user")) { event.preventDefault(); articlesByUser(inputValue('#input-user')); }
    if (cible.matches("#btn-articles-include-titre")) { event.preventDefault(); articlesIncludeTitle(); }
    if (cible.matches("#btn-articles-include-resume")) { event.preventDefault(); articlesIncludeResume()}
});

categories();
//articlesOrderby();