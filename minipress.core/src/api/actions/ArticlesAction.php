<?php

declare(strict_types=1);

namespace minipress\appli\api\actions;

use minipress\appli\application_core\application\useCases\article\ArticleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArticlesAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $service = new ArticleService();
        $articles = $service->getArticles();
        
        $response->getBody()->write(json_encode($articles));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}