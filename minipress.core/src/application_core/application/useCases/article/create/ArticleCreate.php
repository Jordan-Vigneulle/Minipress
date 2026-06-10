<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article\create;

use minipress\appli\application_core\application\useCases\article\create\IArticleCreate;
use minipress\appli\application_core\domain\entities\Article;

class ArticleCreate implements IArticleCreate
{
    public function create(
        string $title,
        string $resume,
        string $contenu,
        string $categorieId,
    ): string {
        $article = new Article();
        $article->titre = $title;
        $article->resume = $resume;
        $article->contenu = $contenu;
        $article->date = (new \DateTime())->format('Y-m-d H:i:s');
        $article->id_utilisateur = 1; // à remplacer par l'utilisateur connecté
        $article->id_categorie = $categorieId;
        $article->save();

        return (string) $article->id;
    }
}