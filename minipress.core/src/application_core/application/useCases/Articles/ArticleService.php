<?php

declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\Articles;

use minipress\appli\application_core\domain\entities\Article;

class ArticleService implements ArticleServiceInterface
{
    public function getArticles(): array
    {
        return Article::all()->toArray();
    }

    public function getPublishedArticles(?string $sort = null): array
    {
        $query = Article::select('id', 'titre', 'resume', 'date', 'id_utilisateur')
            ->where('est_publie', 1)
            ->with('utilisateur:id,pseudo');

        switch ($sort) {
            case 'date-asc':
                $query->orderBy('date', 'asc');
                break;
            case 'date-desc':
                $query->orderBy('date', 'desc');
                break;
            case 'auteur':
                $query->join('utilisateur', 'article.id_utilisateur', '=', 'utilisateur.id')
                    ->select('article.id', 'article.titre', 'article.resume', 'article.date', 'article.id_utilisateur')
                    ->orderBy('utilisateur.pseudo', 'asc');
                break;
            case null:
                $query->orderBy('date', 'desc');
                break;
            default:
                throw new \InvalidArgumentException("Paramètre de tri invalide : $sort");
        }

        return $query->get()->toArray();
    }

    public function togglePublication(int $id): void
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

    public function getPublishedArticleById(int $id): array
    {
        $article = Article::with('categorie', 'images', 'utilisateur:id,pseudo')
            ->where('est_publie', 1)
            ->find($id);
        
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
