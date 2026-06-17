<?php

declare(strict_types=1);

namespace minipress\appli\api\actions;

use minipress\appli\application_core\application\useCases\Categories\CategorieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class ArticlesByCategorieAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $id = $args['id'] ?? null;
        if (!$id || !ctype_digit($id)) {
            throw new \Slim\Exception\HttpBadRequestException($request, "id manquant");
        }

        $data = (new CategorieService())->getPublishedArticlesByCategorie((int)$id);

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $data['articles'] = array_map(fn($article) => [
            ...$article,
            'url' => $routeParser->urlFor('api_ArticlesByCategorie', ['id' => $article['id']]),
        ], $data['articles']);

        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}