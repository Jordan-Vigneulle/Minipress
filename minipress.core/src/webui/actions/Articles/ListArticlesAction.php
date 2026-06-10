<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Articles;

use minipress\appli\application_core\application\useCases\article\ArticleService;
use minipress\appli\application_core\application\useCases\article\ArticleServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ListArticlesAction
{
    private ArticleServiceInterface $articleService;

    public function __construct()
    {
        $this->articleService = new ArticleService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $articles = $this->articleService->getArticles();

        $view = Twig::fromRequest($request);
        return $view->render($response, 'articles/liste_articles.twig', [
            'articles' => $articles
        ]);
    }
}
