<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Articles;

use minipress\appli\application_core\application\useCases\article\ArticleService;
use minipress\appli\application_core\application\useCases\article\ArticleServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class TogglePublishAction
{
    private ArticleServiceInterface $articleService;

    public function __construct()
    {
        $this->articleService = new ArticleService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        if ($id !== null) {
            try {
                $this->articleService->basculerPublication((int)$id);
            } catch (\RuntimeException $e) {
                throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
            }
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $redirectUrl = $routeParser->urlFor('liste_articles');

        return $response
            ->withHeader('Location', $redirectUrl)
            ->withStatus(302);
    }
}
