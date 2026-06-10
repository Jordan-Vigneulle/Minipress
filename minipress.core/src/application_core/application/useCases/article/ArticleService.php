<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article;

use minipress\appli\application_core\domain\entities\Article;

class ArticleService implements ArticleServiceInterface
{
    public function getArticlesPourAdmin(): array
    {
        return Article::orderBy('date', 'desc')->get()->all();
    }
    
    public function basculerPublication(int $id): void
    {
        $article = Article::find($id);
        if ($article === null) {
            throw new \RuntimeException("Article non trouvé avec l'identifiant : " . $id);
        }
        $article->est_publie = !$article->est_publie;
        $article->save();
    }
}
