<?php
declare(strict_types=1);

namespace gift\appli\webui\actions\Profils;

use gift\appli\application_core\application\useCases\user\AuthnService;
use gift\appli\application_core\application\useCases\user\UserService;
use gift\appli\application_core\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

class ProfilsChangementPseudoAction
{
    private Messages $flash;

    public function __construct(Messages $flash)
    {
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if (!$user) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }

        $data = $request->getParsedBody();
        $newUsername = $data['username'] ?? '';

        try {
            (new UserService())->changeUsername($user, $newUsername);
            $this->flash->addMessage('success', 'Pseudo mis à jour avec succès');
        } catch (\RuntimeException $e) {
            $this->flash->addMessage('error', 'Erreur : ' . $e->getMessage());
        }

        return $response
            ->withHeader('Location', $routeParser->urlFor('profil'))
            ->withStatus(302);
    }
}