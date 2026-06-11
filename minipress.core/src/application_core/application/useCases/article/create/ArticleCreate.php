<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article\create;

use minipress\appli\application_core\application\useCases\article\create\IArticleCreate;
use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\application_core\domain\entities\Article;
use minipress\appli\webui\providers\AuthnProvider;

class ArticleCreate implements IArticleCreate
{
    public function create(
        string $title,
        string $resume,
        string $contenu,
        string $categorieId,
    ): void {
        $article = new Article();
        $article->titre = $title;
        $article->resume = $resume;
        $article->contenu = $contenu;
        $article->date = (new \DateTime())->format('Y-m-d H:i:s');
        $article->id_utilisateur = (new AuthnProvider(new AuthnService()))->getSignedInUser()->id;
        $article->id_categorie = $categorieId;
        $article->save();
    }
}