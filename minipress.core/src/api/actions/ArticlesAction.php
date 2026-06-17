<?php

declare(strict_types=1);

namespace minipress\appli\api\actions;

use minipress\appli\application_core\application\useCases\Articles\ArticleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class ArticlesAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $sort = $request->getQueryParams()['sort'] ?? null;

        $service = new ArticleService();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        try {
            $articles = $service->getPublishedArticles($sort);
            $articles = array_map(function ($article) use ($routeParser) {
                $article['uri'] = $routeParser->urlFor('api_Article', ['id' => $article['id']]);
                return $article;
            }, $articles);
        } catch (\InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $response->getBody()->write(json_encode($articles));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
