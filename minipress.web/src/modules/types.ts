'use strict';

export interface Categorie {
    id: number;
    titre: string;
}

export interface Categories {
    categories: Categorie[];
}

export interface Article {
    id: number;
    titre: string;
    resume: string;
    contenu: string;
    date: string ;
    id_categorie: number;
    id_utilisateur: number;
    est_publie: boolean;
    utilisteur : {
        id: number,
        pseudo: string;
    };
}

export interface User{
    id: number;
    email: string;
    role: number;
    chemin_acces_img: string;
    pseudo: string;
}





