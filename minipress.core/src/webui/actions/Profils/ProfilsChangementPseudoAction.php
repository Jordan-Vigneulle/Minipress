<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Profils;

use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\application_core\application\useCases\user\UserService;
use minipress\appli\webui\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

class ProfilsChangementPseudoAction
{

    public function __invoke(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();
        $flash = new Messages();
        if (!$user) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }

        $data = $request->getParsedBody();
        $newUsername = $data['username'] ?? '';

        try {
            (new UserService())->changeUsername($user, $newUsername);
            $flash->addMessage('success', 'Pseudo mis à jour avec succès');
        } catch (\RuntimeException $e) {
            $flash->addMessage('error', 'Erreur : ' . $e->getMessage());
        }

        return $response
            ->withHeader('Location', $routeParser->urlFor('profil'))
            ->withStatus(302);
    }
}