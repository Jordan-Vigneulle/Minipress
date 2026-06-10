<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article\create;

use minipress\appli\application_core\application\useCases\article\create\IArticleCreate;
use minipress\appli\application_core\domain\entities\Article;
use Ramsey\Uuid\Uuid;

class ArticleCreate implements IArticleCreate
{
    public function create(
        string $title,
        string $resume,
        string $contenu,
        // string $createurId,
    ): string {

        $article = new Article();
        $article->titre = $title;
        $article->resume = $resume;
        $article->contenu = $contenu;
        $article->date = (new \DateTime())->format('Y-m-d H:i:s');
        // $article->createur_id = $createurId;
        $article->save();
        var_dump($article->id, $article->getAttributes());
        die;
        return $article->id;
    }
}