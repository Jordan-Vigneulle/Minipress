<?php

declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article;

use minipress\appli\application_core\domain\entities\Article;

class ArticleService implements ArticleServiceInterface
{
    public function getArticles(?string $sort = null): array
    {
        $query = Article::query();

        switch ($sort) {
            case 'date-asc':
                $query->orderBy('date', 'asc');
                break;
            case 'date-desc':
                $query->orderBy('date', 'desc');
                break;
            case 'auteur':
                $query->join('utilisateur', 'article.id_utilisateur', '=', 'utilisateur.id')
                    ->orderBy('utilisateur.pseudo', 'asc')
                    ->select('article.*');
                break;
            case null:
                $query->orderBy('date', 'desc'); // Tri par défaut 
                break;
            default:
                throw new \InvalidArgumentException("Paramètre de tri invalide : $sort");
        }

        return $query->get()->all();
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

    public function getArticleById(int $id): array
    {
        $article = Article::with('categorie', 'images')->find($id);
        if (!$article) {
            throw new \Exception("Aucun article trouvé avec l'identifiant $id");
        }
        return $article->toArray();
    }

    public function markdownToHTML(string $md): string
    {
        $Parsedown = new \Parsedown();
        $html =  $Parsedown->text($md);

        return $html;
    }
}
