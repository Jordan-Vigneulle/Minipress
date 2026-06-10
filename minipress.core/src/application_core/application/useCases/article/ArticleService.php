<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article;

use minipress\appli\application_core\domain\entities\Article;

class ArticleService implements ArticleServiceInterface
{
    public function getArticles(): array
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
