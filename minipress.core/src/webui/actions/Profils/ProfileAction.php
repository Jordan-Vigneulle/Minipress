<?php

namespace gift\appli\webui\actions\Profils;

use gift\appli\application_core\application\useCases\user\AuthnService;
use gift\appli\application_core\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class ProfileAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if ($user === null) {
            throw new \Slim\Exception\HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }

        $queryParams = $request->getQueryParams();

        $view = Twig::fromRequest($request);
        return $view->render($response, 'Profils/profilsView.twig', [
            'user' => $user,
            'success' => $queryParams['success'] ?? null,
            'error' => $queryParams['error'] ?? null,
        ]);
    }
}