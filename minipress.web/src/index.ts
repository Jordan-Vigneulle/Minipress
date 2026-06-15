'use strict';

import { quitterModeArticle } from "./modules/modearticle";
import { loadAll } from "./modules/articleloader";
import { url, url_articles, url_categories } from "./modules/config";
import { displayArticleByCategorie, displayArticleByUser, displayArticleOrderby, displayCategories, displayArticle} from "./modules/ui";

let order = "date-asc";

const inputText = (selector: string): string =>
    (document.querySelector(selector) as HTMLInputElement).value.trim().toLowerCase();

const selectValue = (selector: string): number =>
    Number((document.querySelector(selector) as HTMLSelectElement).value);

const clearAll = () => {
    document.querySelector('#les_articles_orderby')!.innerHTML = "";
    document.querySelector('#les_articles_par_categorie')!.innerHTML = "";
    document.querySelector('#les_articles_par_user')!.innerHTML = "";
    document.querySelector('#un_article')!.innerHTML = "";
};

const articlesOrderby = (tri: string) => {
    clearAll();
    order = tri;
    console.log("tri demandé :", tri);
    document.querySelectorAll('#btn-date-asc, #btn-date-desc').forEach(b => b.classList.remove('active'));
    document.querySelector(`#btn-date-${tri === 'date-asc' ? 'asc' : 'desc'}`)!.classList.add('active');
    loadAll(url_articles, `?sort=${order}`)
        .then((articles) => {
            console.log("articles reçus :", articles);
            displayArticleOrderby(articles);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

// const articlesIncludeTitle = () => {
//     clearAll();
//     const keyword = inputText('#input-keyword-titre');
//     loadAll<any[]>(url_articles)
//         .then((articles) => {
//             const filtered = keyword
//                 ? articles.filter(a => a.titre.toLowerCase().includes(keyword))
//                 : articles;
//             displayArticleOrderby(filtered);
//         })
//         .catch((error) => console.error("Erreur au chargement des articles: ", error));
// }; Fonctionnalité 7

const articlesIncludeResume = () => {
    clearAll();
    const keyword = inputText('#input-keyword-resume');
    loadAll<any[]>(url_articles)
        .then((articles) => {
            const filtered = keyword
                ? articles.filter(a =>
                    a.titre.toLowerCase().includes(keyword) ||
                    a.resume.toLowerCase().includes(keyword)
                )
                : articles;
            displayArticleOrderby(filtered);
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const categories = () => {
    loadAll(url_categories)
        .then((categories) => displayCategories(categories))
        .catch((error) => console.error("Erreur au chargement des catégories: ", error));
};

const articleByCategorie = (id_categorie: number) => {
    if (!id_categorie) return;
    clearAll();
    loadAll<any>(url_categories, `/${id_categorie}/articles`)
        .then((data) => displayArticleByCategorie(data))
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const article = (id: number) => {
    if (!id) return;
    clearAll();
    loadAll(url_articles, `/${id}`)
        .then((article) => displayArticle(article))
        .catch((error) => console.error("Erreur au chargement de l'article: ", error));
};

const articlesByUser = (id_user: number) => {
    if (!id_user) return;
    clearAll();
    loadAll(url, `/auteurs/${id_user}/articles`)
        .then((articles) => displayArticleByUser(articles))
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
};

const selectTitreArticles = document.querySelector<HTMLSelectElement>('#select-categories');
if (selectTitreArticles) {
    loadAll<any[]>(url_articles)
        .then((titres) => {
            titres.forEach((titre: any) => {
                const option = document.createElement('option');
                option.value = String(titre.id);
                option.textContent = titre.titre;
                selectTitreArticles.appendChild(option);
            });
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
}

const selectUsers = document.querySelector<HTMLSelectElement>('#select-users');
if (selectUsers) {
    loadAll<any[]>(url + '/auteurs')
        .then((users) => {
            users.forEach((user: any) => {
                const option = document.createElement('option');
                option.value = String(user.id);
                option.textContent = user.pseudo;
                selectUsers.appendChild(option);
            });
        })
        .catch((error) => console.error("Erreur au chargement des utilisateurs: ", error));
}

document.addEventListener("click", (event) => {
    const cible = event.target as HTMLElement;
    console.log("cible :", cible);
    console.log("closest .categorie :", cible.closest('.categorie'));
    console.log("closest .card-article :", cible.closest('.card-article'));
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
    console.log("carte trouvée :", carte);
    console.log("cat trouvée :", cat);
    console.log("auteur trouvé :", auteur);
    if (cible.closest("#btn-date-asc")) { event.preventDefault(); articlesOrderby('date-asc'); return; }
    if (cible.closest("#btn-date-desc")) { event.preventDefault(); articlesOrderby('date-desc'); return; }
    if (cible.closest("#btn-article")) { event.preventDefault(); article(selectValue('#select-categories')); return; }
    if (cible.closest("#btn-articles-user")) { event.preventDefault(); articlesByUser(selectValue('#select-users')); return; }
    if (cible.closest("#btn-articles-include-resume")) { event.preventDefault(); articlesIncludeResume(); return; }
    if (cible.closest("#btn-retour")) { event.preventDefault(); quitterModeArticle(); return; }
    if (cible.closest("#btn-clear")) {
        event.preventDefault();
        clearAll();
        (document.querySelector('#select-categories') as HTMLSelectElement).value = "";
        (document.querySelector('#select-users') as HTMLSelectElement).value = "";
        (document.querySelector('#input-keyword-titre') as HTMLInputElement).value = "";
        (document.querySelector('#input-keyword-resume') as HTMLInputElement).value = "";
        return;
    }
});

categories();
