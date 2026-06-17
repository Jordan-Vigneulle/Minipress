"use strict";

import { loadAll } from "./articleloader";
import { url_articles, url_auteurs } from "./config";
import { Article, User } from "./types";

// -------------------------------- Récupération des données des selects
export const inputText = (selector: string): string =>
    (document.querySelector(selector) as HTMLInputElement).value.trim().toLowerCase();

export const selectValueNumber = (selector: string): number =>
    Number((document.querySelector(selector) as HTMLSelectElement).value);

export const selectValueString = (selector: string): string =>
    (document.querySelector(selector) as HTMLSelectElement).value;


// -------------------------------- Remplissage des select au lancement de l'app
const selectTitreArticles = document.querySelector<HTMLSelectElement>('#select-categories');
if (selectTitreArticles) {
    loadAll<Article[]>(url_articles)
        .then((titres) => {
            titres.forEach((titre: Article) => {
                const option = document.createElement('option');
                option.value = String(titre.uri);
                option.textContent = titre.titre;
                selectTitreArticles.appendChild(option);
            });
        })
        .catch((error) => console.error("Erreur au chargement des articles: ", error));
}

const selectUsers = document.querySelector<HTMLSelectElement>('#select-users');
if (selectUsers) {
    loadAll<User[]>(url_auteurs)
        .then((users) => {
            users.forEach((user: User) => {
                const option = document.createElement('option');
                option.value = String(user.id);
                option.textContent = user.pseudo;
                selectUsers.appendChild(option);
            });
        })
        .catch((error) => console.error("Erreur au chargement des utilisateurs: ", error));
}

//=========== Fonctionnalité 7 ============
// const articlesIncludeTitle = () => {
//     clearAll();
//     const keyword = inputText('#input-keyword-titre');
//     loadAll<Article[]>(url_articles)
//         .then((articles) => {
//             const filtered = keyword
//                 ? articles.filter(a => a.titre.toLowerCase().includes(keyword))
//                 : articles;
//             displayArticleOrderby(filtered);
//         })
//         .catch((error) => console.error("Erreur au chargement des articles: ", error));
// };