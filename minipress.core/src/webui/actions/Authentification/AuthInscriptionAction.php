<?php
declare(strict_types=1);

namespace gift\appli\webui\actions\Authentification;

use gift\appli\application_core\application\useCases\user\AuthnService;
use gift\appli\application_core\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpForbiddenException;
use Slim\Flash\Messages;

class AuthInscriptionAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $provider = new AuthnProvider(new AuthnService());
        $flash = new Messages();

        $data = $request->getParsedBody();
        $userId = $data['user_id'] ?? '';

        if (!hash_equals($_SESSION['csrf_login'] ?? '', $data['csrf_token'] ?? '')) {
            throw new HttpForbiddenException($request);
        }
        unset($_SESSION['csrf_login']);

        if (empty($userId) || empty($data['password'] ?? '')) {
            $flash->addMessage('error', 'Veuillez saisir l\'identifiant et le mot de passe');

            return $response->withHeader(
                'Location',
                $routeParser->urlFor('loginPage')
            )->withStatus(302);
        }

        $user = $provider->signin($userId, $data['password']);

        if ($user === null) {
            $flash->addMessage('error', 'Identifiants incorrects');

            return $response->withHeader(
                'Location',
                $routeParser->urlFor('loginPage')
            )->withStatus(302);
        }

        return $response->withHeader(
            'Location',
            $routeParser->urlFor('allCategories')
        )->withStatus(302);
    }
}