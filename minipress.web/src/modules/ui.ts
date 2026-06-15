'use strict';
import Handlebars from 'handlebars';
import { afficherModeArticle } from './modearticle';
import { markdownToHtml } from "ts-markdown-parser";


export function displayCategories(categories: any) {
    const templateCategories = Handlebars.compile(document.querySelector<HTMLScriptElement>('#categorieTemplate')!.innerHTML);

    document.querySelector('#la_categorie')!.innerHTML = templateCategories({ categories });
}

export function displayArticle(article: any) {
    const templateArticles = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleTemplate')!.innerHTML);
    
    article.contenu = markdownToHtml(article.contenu, {});
    article.date = formaterDate(article.date);

    afficherModeArticle();

    document.querySelector('#un_article')!.innerHTML = templateArticles({ article });
}

export function displayArticleOrderby(articles: any) {
    const templateArticles = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleOrderbyTemplate')!.innerHTML);

    const articlesAvecResume = articles.map((a: any) => ({
        ...a,
        resume: markdownToHtml(a.resume, {}),
        date: formaterDate(a.date)
    }));
 
    document.querySelector('#les_articles_orderby')!.innerHTML = templateArticles({ articles: articlesAvecResume });
}

export function displayArticleByCategorie(data: any) {
    const template = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleByCategorieTemplate')!.innerHTML);

    const articlesAvecResume = data.articles.map((a: any) => ({
        ...a,
        resume: markdownToHtml(a.resume, {}),
        date: formaterDate(a.date)
    }));

    document.querySelector('#les_articles_par_categorie')!.innerHTML = template({ ...data, articles: articlesAvecResume });
}

export function displayArticleByUser(data: any) {
    const template = Handlebars.compile(document.querySelector<HTMLScriptElement>('#articleByUserTemplate')!.innerHTML);

    const articlesAvecResume = data.articles.map((a: any) => ({
        ...a,
        resume: markdownToHtml(a.resume, {}),
        date: formaterDate(a.date)
    }));
    
    document.querySelector('#les_articles_par_user')!.innerHTML = template({ ...data, articles: articlesAvecResume });
}

const formaterDate = (dateStr: string): string => {
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};