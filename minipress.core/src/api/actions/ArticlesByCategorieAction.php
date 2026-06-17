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

        try {
            $service = new CategorieService();
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $categorie = $service->getPublishedArticlesByCategorie((int)$id);
            $categorie = array_map(function ($article) use ($routeParser) {
                $article['url'] = $routeParser->urlFor('api_ArticlesByCategorie', ['id' => $article['id']]);
                return $article;
            }, $categorie);
        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }
        
        $response->getBody()->write(json_encode($categorie));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}