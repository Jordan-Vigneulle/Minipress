'use strict';
import Handlebars from 'handlebars';
import { afficherModeArticle } from './modeArticle';

export function displayArticleOrderby(articles: any) {
    const templateArticles = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleOrderbyTemplate')!.innerHTML);
    document.querySelector('#les_articles_orderby')!.innerHTML = templateArticles({ articles });
}

export function displayCategories(categories: any) {
    const templateCategories = Handlebars.compile(document.querySelector<HTMLScriptElement>('#categorieTemplate')!.innerHTML);

    document.querySelector('#la_categorie')!.innerHTML = templateCategories({ categories });
}

export function displayArticleByCategorie(data: any) {
    const template = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleByCategorieTemplate')!.innerHTML);
    document.querySelector('#les_articles_par_categorie')!.innerHTML = template(data); // data = { categorie, articles }
}

export function displayArticle(article: any) {
    const templateArticles = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleTemplate')!.innerHTML);
    document.querySelector('#un_article')!.innerHTML = templateArticles({ article });
   
    afficherModeArticle();
}

export function displayArticleByUser(data: any) {
    const template = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleByUserTemplate')!.innerHTML);    
    document.querySelector('#les_articles_par_user')!.innerHTML = template(data); // data = { auteur, articles }
}


