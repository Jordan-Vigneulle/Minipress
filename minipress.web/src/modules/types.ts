'use strict';

export interface Categorie {
    id: number;
    titre: string;
    articles: Article[];
}

export interface Categories {
    categories: Categorie[];
}

export interface Article {
    id: number;
    titre: string;
    resume: string;
    contenu: string;
    date: Date;
    id_categorie: number;
    id_utilisateur: number;
    est_publie: boolean;
}





