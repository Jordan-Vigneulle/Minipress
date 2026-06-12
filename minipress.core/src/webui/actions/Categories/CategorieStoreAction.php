<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use minipress\appli\application_core\application\useCases\Categories\create\CategorieCreate;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class CategorieStoreAction
{
    public function __invoke(Request $request, Response $response, array $args): Response {

        
        $flash = new \Slim\Flash\Messages();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $data = $request->getParsedBody() ?? [];
        $titre = $data['titre'];

        try {

            if (!is_null($titre) && is_string($data['titre'])){
                $service = new CategorieCreate();
                $service->create($titre);
            }

        } catch (\Exception $e) {
            $flash->addMessage('error', 'Une erreur est survenue, veuillez réessayer.');
            return $response->withHeader('Location', $routeParser->urlFor('CategorieCreate'))->withStatus(302);
        }

        $flash->addMessage('success', 'La catégorie a bien été créée');
        return $response->withHeader('Location', $routeParser->urlFor('CategorieCreate'))->withStatus(302);  
    }
}