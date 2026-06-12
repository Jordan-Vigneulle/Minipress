'use strict';

import { loadAll } from "./modules/articleloader";
import { url, url_articles, url_categories } from "./modules/config";
import { displayArticle, displayArticleByCategorie, displayArticleByUser, displayArticleOrderby, displayCategories } from "./modules/ui";

const inputValue = (selector: string): number =>
    Number((document.querySelector(selector) as HTMLInputElement).value);

const articlesOrderby = (order: string = "") => {
    const query = order ? `?order=${order}` : "";
    loadAll(url_articles, query)
        .then((articles) => {
            console.log(articles);
            displayArticleOrderby(articles);
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
            displayArticleByCategorie(data);   // ← l'objet complet, pas data.articles
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
    if (!id_user) return;
    loadAll(url, `/auteurs/${id_user}/articles`)
        .then((articles) => {
            console.log(articles);
            displayArticleByUser(articles);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

document.addEventListener("click", (event) => {
    const bouton = event.target as HTMLElement;
    if (bouton.matches("#btn-categories")) categories();
    if (bouton.matches("#btn-articles-orderby")) articlesOrderby();
    if (bouton.matches("#btn-articles-categorie")) articleByCategorie(inputValue('#input-categorie'));
    if (bouton.matches("#btn-article")) article(inputValue('#input-article'));
    if (bouton.matches("#btn-articles-user")) articlesByUser(inputValue('#input-user'));
});