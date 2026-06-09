<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Profils;

use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\application_core\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

class ProfilsChangementMDPAction
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
            throw new HttpUnauthorizedException($request, "Accès refusé");
        }

        $data = $request->getParsedBody();
        $oldPwd = $data['old_password'] ?? '';
        $newPwd = $data['new_password'] ?? '';
        $confirmPwd = $data['confirm_password'] ?? '';

        if ($newPwd !== $confirmPwd) {
            $this->flash->addMessage('error', "Les nouveaux mots de passe ne correspondent pas.");
            return $response->withHeader('Location', $routeParser->urlFor('profil'))->withStatus(302);
        }

        try {
            (new AuthnService())->changePassword($user, $oldPwd, $newPwd);
            $this->flash->addMessage('success', "Mot de passe mis à jour avec succès.");
        } catch (\RuntimeException $e) {
            $this->flash->addMessage('error', "Échec de la mise à jour : " . $e->getMessage());
        }

        return $response->withHeader('Location', $routeParser->urlFor('profil'))->withStatus(302);
    }
}