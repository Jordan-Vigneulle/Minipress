<?php

namespace minipress\appli\application_core\application\useCases\Categories;

use minipress\appli\application_core\application\useCases\Categories\CategorieServiceInterface;
use minipress\appli\application_core\domain\entities\Categorie;
use minipress\appli\application_core\domain\entities\Article;


class CategorieService implements CategorieServiceInterface {

    public static function getCategories(): array {
        return Categorie::all()->toArray();
    }

    public static function getCategoriesWithPublishedArticles(): array{
        return Categorie::with(['articles' => function ($query) {
                                    $query->where('est_publie', 1);
                                }])->get()->toArray();
    }

    public function getPublishedArticlesByCategorie(int $categ_id): array {
        $categorie = Categorie::where('id', $categ_id)->first();
        if (!$categorie) {
            throw new \Exception("Aucune catégorie trouvée avec l'identifiant $categ_id");
        }

        $articles = Article::where([['id_categorie', $categ_id], ['est_publie', 1]])->with('utilisateur:id,pseudo')->get();
        if ($articles->isEmpty()) {
            throw new \Exception("Aucun article trouvé pour la catégorie $categ_id");
        }

        return [
            'categorie' => $categorie->toArray(),
            'articles' => $articles->toArray(),
        ];
    }
}