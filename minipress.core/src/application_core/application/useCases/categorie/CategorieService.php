<?php

namespace minipress\appli\application_core\application\useCases\categorie;

use minipress\appli\application_core\application\useCases\categorie\CategorieServiceInterface;
use minipress\appli\application_core\domain\entities\Categorie;
use minipress\appli\application_core\domain\entities\Article;


class CategorieService implements CategorieServiceInterface {

    public static function getCategories(): array{
        return Categorie::all()->toArray();
    }

    public function getArticlesByCategorie(int $categ_id): array {
        $categorie = Categorie::where('id', $categ_id)->first();
        if (!$categorie) {
            throw new \Exception("Aucune catégorie trouvée avec l'identifiant $categ_id");
        }

        $articles = Article::where('id_categorie', $categ_id)->get();
        if ($articles->isEmpty()) {
            throw new \Exception("Aucun article trouvé pour la catégorie $categ_id");
        }

        return [
            'categorie' => $categorie->toArray(),
            'articles' => $articles->toArray(),
        ];
    }
}