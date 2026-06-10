<?php

namespace minipress\appli\application_core\application\useCases\categorie;

use minipress\appli\application_core\application\useCases\categorie\IGestionCategorie;
use minipress\appli\application_core\domain\entities\Categorie;
use minipress\appli\application_core\domain\entities\Article;


class GestionCategorie implements IGestionCategorie {

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

    public function getArticleById(int $id): array {
        $article = Article::with('categorie', 'images')->find($id);
        if (!$article) {
            throw new \Exception("Aucun article trouvé avec l'identifiant $id");
        }
        return $article->toArray();
    }

    public function markdownToHTML(string $md): string {
        $Parsedown = new \Parsedown();
        $html =  $Parsedown->text($md);

        return $html;
    }
}