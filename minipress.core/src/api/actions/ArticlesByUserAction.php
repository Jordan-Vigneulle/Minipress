<?php

declare(strict_types=1);

namespace minipress\appli\api\actions;

use minipress\appli\application_core\application\useCases\Users\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class ArticlesByUserAction
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
            $service = new UserService();
            $user = $service->getPublishedArticlesByUser((int)$id);
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            if (isset($user['articles']) && is_array($user['articles'])) {
                $user['articles'] = array_map(function($article) use ($routeParser) {
                    return [
                        ...$article,
                        'uri' => $routeParser->urlFor('api_Article', ['id' => $article['id']]),
                    ];
                }, $user['articles']);
            }
        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        $response->getBody()->write(json_encode($user));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
