<?php

namespace minipress\appli\webui\actions\Profils;

use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\application_core\domain\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Slim\Exception\HttpUnauthorizedException;

class ProfileAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if ($user === null) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
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